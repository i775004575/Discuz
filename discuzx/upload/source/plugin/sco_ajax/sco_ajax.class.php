<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include_once libfile('class/sco_dx_plugin', 'source', 'extensions/');

class plugin_sco_ajax extends _sco_dx_plugin {
	function plugin_sco_ajax() {
		$this->_init($this->_get_identifier(__METHOD__));
	}
}

class plugin_sco_ajax_forum extends plugin_sco_ajax {
	function ajax_viewthread() {
		$this->_hook('Script_forum_ajax:After_action_else', array(
				&$this,
				'_hook_ajax_viewthread'
		));
	}

	function _hook_ajax_viewthread() {
		global $_G;

		$this->_my_ajax_viewthread();

		extract($this->attr['global']);
		$plugin_self = &$this;

		include $this->_template('ajax_viewthread');

		dexit();
	}

	function _my_ajax_viewthread() {
		$this->_my_check_allowview();

		$postlist = array();

		$sql = $this->_my_postlist_sql();
		$query = DB::query($sql);
		while($post = DB::fetch($query)) {
			$postusers[$post['authorid']] = array();
			if($post['first']) {
				$_G['forum_firstpid'] = $post['pid'];
				if(IS_ROBOT || $_G['adminid'] == 1) $summary = str_replace(array("\r", "\n"), '', messagecutstr(strip_tags($post['message']), 160));
			}
			$postlist[$post['pid']] = $this->_my_viewthread_procpost($post);
		}

		$this
			->_setglobal('postlist', &$postlist)
		;
	}

	function _my_viewthread_procpost($post) {

		$_G['forum_postcount']++;

		return $post;
	}

	function _my_postlist_sql() {
		global $_G;

		$posttableid = $_G['forum_thread']['posttableid'];
		$posttable = $_G['forum_thread']['posttable'];

		$page = $_G['page'];

		$usemagic = array('user' => array(), 'thread' => array());

		$replynotice = getstatus($_G['forum_thread']['status'], 6);

		$hiddenreplies = getstatus($_G['forum_thread']['status'], 2);

		$rushreply = getstatus($_G['forum_thread']['status'], 3);

		$savepostposition = getstatus($_G['forum_thread']['status'], 1);

		$_G['ppp'] = $_G['forum']['threadcaches'] && !$_G['uid'] ? $_G['setting']['postperpage'] : $_G['ppp'];
		$totalpage = ceil(($_G['forum_thread']['replies'] + 1) / $_G['ppp']);
		$page > $totalpage && $page = $totalpage;


		$start_limit = $_G['forum_numpost'] = max(0, ($page - 1) * $_G['ppp']);
		if($start_limit > $_G['forum_thread']['replies']) {
			$start_limit = $_G['forum_numpost'] = 0;
			$page = 1;
		}

		$pageadd = "ORDER BY p.dateline LIMIT $start_limit, $_G[ppp]";

		$query = "SELECT p.* $postfieldsadd FROM ".DB::table($posttable)." p $specialadd1 ";

		$isdel_post = $cachepids = $positionlist = $postusers = $skipaids = array();
		if($savepostposition && empty($onlyauthoradd) && empty($specialadd2) && empty($_G['gp_viewpid']) && $ordertype != 1) {
			$start = ($page - 1) * $_G['ppp'] + 1;
			$end = $start + $_G['ppp'];
			$q2 = DB::query("SELECT pid, position FROM ".DB::table('forum_postposition')." WHERE tid='$_G[tid]' AND position>='$start' AND position<'$end' ORDER BY position");
			$realpost = $lastposition = 0;
			while ($post = DB::fetch($q2)) {
				$cachepids[$post[position]] = $post['pid'];
				$positionlist[$post['pid']] = $post['position'];
				$lastposition = $post['position'];
			}
			$realpost = count($positionlist);
			if($realpost != $_G['ppp']) {
				$k = 0;
				for($i = $start; $i < $end; $i ++) {
					if(!empty($cachepids[$i])) {
						$k = $cachepids[$i];
						$isdel_post[$k] = array('message' => lang('forum/misc', 'post_deleted'), 'number' => $i);
					} elseif($i < $maxposition || ($lastposition && $i < $lastposition)) {
						$isdel_post[$k] = array('message' => lang('forum/misc', 'post_deleted'), 'number' => $i);
					}
					$k ++;
				}
			}
			$cachepids = dimplode($cachepids);
			$pagebydesc = false;
		}

		$query .= $savepostposition && $cachepids ? "WHERE p.pid IN ($cachepids)" : ("WHERE p.tid='$_G[gp_tid]'".($_G['forum_auditstatuson'] || in_array($_G['forum_thread']['displayorder'], array(-2, -3, -4)) && $_G['forum_thread']['authorid'] == $_G['uid'] ? '' : " AND p.invisible='0'")." $specialadd2 $onlyauthoradd $pageadd");

		$this
			->_setglobal('page', &$page)
			->_setglobal('totalpage', &$totalpage)
		;

		return $query;
	}

	function _my_check_allowview() {
		global $_G;

		$extraparam = array(
			'login' => 0,

			// Ajax 只顯示信息文本
			'msgtype' => 3,
			'showdialog' => false,
		);

		// 群組權限

		if ($_G['forum']['status'] == 3) {
			include_once libfile('function/group');
			$status = groupperm($_G['forum'], $_G['uid']);
			if($status == 1) {
				// 'forum_group_status_off' => '該{_G/setting/navs/3/navname}已關閉',
				showmessage('forum_group_status_off');
			} elseif($status == 2) {
				// 'forum_group_noallowed' => '抱歉，您沒有權限訪問該{_G/setting/navs/3/navname}',
				showmessage('forum_group_noallowed');
			} elseif($status == 3) {
				// 'forum_group_moderated' => '請等待群主審核',
				showmessage('forum_group_moderated');
			}
		}

		// 版塊權限

		if(empty($_G['forum']['allowview'])) {

			if(!$_G['forum']['viewperm'] && !$_G['group']['readaccess']) {
				showmessage('group_nopermission', NULL, array('grouptitle' => $_G['group']['grouptitle']), array('login' => 0));
			} elseif($_G['forum']['viewperm'] && !forumperm($_G['forum']['viewperm'])) {
				showmessagenoperm('viewperm', $_G['fid'], null, $extraparam);
			}

		} elseif($_G['forum']['allowview'] == -1) {
			showmessage('forum_access_view_disallow');
		}

		// 版塊權限

		if($_G['forum']['formulaperm']) {
			formulaperm($_G['forum']['formulaperm'], $extraparam);
		}

		// 版塊密碼

		if($_G['forum']['password'] && $_G['forum']['password'] != $_G['cookie']['fidpw'.$_G['fid']]) {
			// 'forum_passwd_incorrect' => '抱歉，您輸入的密碼不正確，不能訪問這個版塊',
			showmessage('forum_passwd_incorrect', NULL);
		}

		// 閱讀權限

		if($_G['forum_thread']['readperm'] && $_G['forum_thread']['readperm'] > $_G['group']['readaccess'] && !$_G['forum']['ismoderator'] && $_G['forum_thread']['authorid'] != $_G['uid']) {
			showmessage('thread_nopermission', NULL, array('readperm' => $_G['forum_thread']['readperm']), array('login' => 0));
		}

		// 付費主題

		$threadtable = $_G['forum_thread']['threadtable'];

		$_G['forum_threadpay'] = FALSE;
		if($_G['forum_thread']['price'] > 0 && $_G['forum_thread']['special'] == 0) {
			if($_G['setting']['maxchargespan'] && TIMESTAMP - $_G['forum_thread']['dateline'] >= $_G['setting']['maxchargespan'] * 3600) {
				DB::query("UPDATE ".DB::table($threadtable)." SET price='0' WHERE tid='$_G[tid]'");
				$_G['forum_thread']['price'] = 0;
			} else {
				$exemptvalue = $_G['forum']['ismoderator'] ? 128 : 16;
				if(!($_G['group']['exempt'] & $exemptvalue) && $_G['forum_thread']['authorid'] != $_G['uid']) {
					$query = DB::query("SELECT relatedid FROM ".DB::table('common_credit_log')." WHERE relatedid='$_G[tid]' AND uid='$_G[uid]' AND operation='BTC'");
					if(!DB::num_rows($query)) {
						include_once libfile('thread/pay', 'include');
						$_G['forum_threadpay'] = TRUE;
					}
				}
			}
		}

		if ($_G['forum_threadpay'] == TRUE) {
			showmessage('thread_pay_error', NULL);
		}
	}

	/**
	 * @param array $key
	 *
	 * $key = array(
	 * 	'template' => 'forumdisplay',
	 * 	'message' => null,
	 *	'values' => null,
	 * )
	 */
	function forumdisplay_thread_output($key) {
		global $_G;

		// 不顯示給訪客使用
		if (!$_G['uid']) return;

		$this->_hook(
			'Tpl_Func_hooktags:Before',
			array(
				&$this,
				'_hook_forumdisplay_thread_output'
		));
	}

	function _hook_forumdisplay_thread_output($_EVENT, $hook_ret, $hook_id, $hook_key) {
		if ($hook_id != 'forumdisplay_thread') return;

		global $_G;

		$hook_ret = $this->_fetch_template($this->_template('forumdisplay_thread'), array(
			'_G' => &$_G,
			'thread' => &$_G['forum_threadlist'][$hook_key],

			'hook_key' => $hook_key,
		)).$hook_ret;
	}
}

?>