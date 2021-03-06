<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: member_lostpasswd.php 22109 2011-04-21 10:04:57Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

define('NOROBOT', TRUE);

$discuz_action = 141;

if(submitcheck('lostpwsubmit')) {
	loaducenter();

	// bluelovers
	// 初始化 $tmp
	$tmp = array();
	$tmp_stop = 0;

	if (empty($_G['gp_username'])) {
		// 如果沒有 username 則直接判定 $tmp_stop = 1
		$tmp_stop = 1;
	} else {
	// bluelovers

		list($tmp['uid'], , $tmp['email']) = uc_get_user($_G['gp_username']);
		if($_G['gp_email'] != $tmp['email']
			// bluelovers
			// 防止未知的漏洞
			|| empty($tmp['email'])
			|| empty($tmp['uid'])
			// bluelovers
		) {
			// bluelovers
			$tmp_stop = 1;
		}

	}

	if ($tmp_stop
		&& (
			!empty($tmp['uid'])
			|| !empty($_G['gp_email'])
		)
	) {
		if ($tmp['uid']) {
			// 允許只依靠 username 來查詢
			$tmp_stop = 0;
		} elseif (!empty($_G['gp_email'])) {
			$tmp = array();
			list($tmp['uid'], , $tmp['email']) = uc_get_user($_G['gp_email'], 2);

			if ($tmp['uid']
				&& !empty($tmp['email'])
				&& $_G['gp_email'] == $tmp['email']
			) {
				// 允許只依靠 email 來查詢
				$tmp_stop = 0;
			}
		}
	}

	if ($tmp_stop) {
		// bluelovers
		showmessage('getpasswd_account_notmatch');
	}
	$member = DB::fetch_first("SELECT uid, username, adminid, email FROM ".DB::table('common_member')." WHERE uid='$tmp[uid]'");
	if(!$member) {
		showmessage('getpasswd_account_notmatch');
	} elseif($member['adminid'] == 1 || $member['adminid'] == 2) {
		showmessage('getpasswd_account_invalid');
	}

	if($member['email'] != $tmp['email']) {
		DB::query("UPDATE ".DB::table('common_member')." SET email='".addslashes($tmp['email'])."' WHERE uid='".addslashes($tmp['uid'])."'");
	}

	$idstring = random(6);
	DB::query("UPDATE ".DB::table('common_member_field_forum')." SET authstr='$_G[timestamp]\t1\t$idstring' WHERE uid='$member[uid]'");
	require_once libfile('function/mail');
	$get_passwd_subject = lang('email', 'get_passwd_subject');
	$get_passwd_message = lang(
		'email',
		'get_passwd_message',
		array(
			'username' => $member['username'],
			'bbname' => $_G['setting']['bbname'],
			'siteurl' => $_G['siteurl'],
			'uid' => $member['uid'],
			'idstring' => $idstring,
			'clientip' => $_G['clientip'],
		)
	);
	sendmail("$_G[gp_username] <$tmp[email]>", $get_passwd_subject, $get_passwd_message);
	showmessage('getpasswd_send_succeed', $_G['siteurl'], array(), array('showdialog' => 1, 'locationtime' => true));
}

?>