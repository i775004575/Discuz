<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_smilies.php 19831 2011-01-19 07:54:16Z monkey $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$imgextarray = array('jpg', 'gif');
$id = $_G['gp_id'];
if($operation == 'export' && $id) {
	$smileyarray = DB::fetch_first("SELECT name, directory FROM ".DB::table('forum_imagetype')." WHERE typeid='$id' AND type='smiley'");
	if(!$smileyarray) {
		cpheader();
		cpmsg('smilies_type_nonexistence', '', 'error');
	}

	$smileyarray['smilies'] = array();
	$query = DB::query("SELECT typeid, displayorder, code, url FROM ".DB::table('common_smiley')." WHERE typeid='$id' AND type='smiley'");
	while($smiley = DB::fetch($query)) {
		$smileyarray['smilies'][] = $smiley;
	}

	$smileyarray['version'] = strip_tags($_G['setting']['version']);
	exportdata('Discuz! Smilies', $smileyarray['name'], $smileyarray);
}

cpheader();

if(!$operation) {

	if(!submitcheck('smiliessubmit')) {

		shownav('style', 'smilies_edit');
		showsubmenu('nav_smilies', array(
			array('smilies_type', 'smilies', 1),
			array('smilies_import', 'smilies&operation=import', 0),
		));
		showtips('smilies_tips_smileytypes');
		showformheader('smilies');
		showtableheader();
		showsubtitle(array('', 'display_order', 'enable', 'smilies_type', 'dir', 'smilies_nums', ''));

		$smtypes = 0;
		$dirfilter = array();
		$query = DB::query("SELECT * FROM ".DB::table('forum_imagetype')." WHERE type='smiley' ORDER BY displayorder");
		while($type = DB::fetch($query)) {
			$squery = DB::query("SELECT COUNT(*) FROM ".DB::table('common_smiley')." WHERE typeid='$type[typeid]'");
			$smiliesnum = DB::result($squery, 0);
			showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$type[typeid]\" ".($smiliesnum ? 'disabled' : '').">",
				"<input type=\"text\" class=\"txt\" name=\"displayordernew[$type[typeid]]\" value=\"$type[displayorder]\" size=\"2\">",
				"<input class=\"checkbox\" type=\"checkbox\" name=\"availablenew[$type[typeid]]\" value=\"1\" ".($type['available'] ? 'checked' : '').">",
				"<input type=\"text\" class=\"txt\" name=\"namenew[$type[typeid]]\" value=\"$type[name]\" size=\"15\">",
				"./static/image/smiley/$type[directory]",
				"$smiliesnum<input type=\"hidden\" name=\"smiliesnum[$type[typeid]]\" value=\"$smiliesnum\" />",
				"<a href=\"".ADMINSCRIPT."?action=smilies&operation=update&id=$type[typeid]\" class=\"act\" onclick=\"return confirm('$lang[smilies_update_confirm1]$type[directory]$lang[smilies_update_confirm2]$type[name]$lang[smilies_update_confirm3]')\">$lang[smilies_update]</a>&nbsp;".
				"<a href=\"".ADMINSCRIPT."?action=smilies&operation=export&id=$type[typeid]\" class=\"act\">$lang[export]</a>&nbsp;".
				"<a href=\"".ADMINSCRIPT."?action=smilies&operation=edit&id=$type[typeid]\" class=\"act\">$lang[detail]</a>"
			));
			$dirfilter[] = $type['directory'];
			$smtypes++;
		}

		$smdir = DISCUZ_ROOT.'./static/image/smiley';
		$smtypedir = dir($smdir);
		$dirnum = 0;
		while($entry = $smtypedir->read()) {
			if($entry != '.' && $entry != '..' && !in_array($entry, $dirfilter) && preg_match("/^\w+$/", $entry) && strlen($entry) < 30 && is_dir($smdir.'/'.$entry)){
				$smiliesdir = dir($smdir.'/'.$entry);
				$smnums = 0;
				$smilies = '';
				while($subentry = $smiliesdir->read()) {
					if(in_array(strtolower(fileext($subentry)), $imgextarray) && preg_match("/^[\w\-\.\[\]\(\)\<\> &]+$/", substr($subentry, 0, strrpos($subentry, '.'))) && strlen($subentry) < 30 && is_file($smdir.'/'.$entry.'/'.$subentry)) {
						$smilies .= '<input type="hidden" name="smilies['.$dirnum.']['.$smnums.'][available]" value="1"><input type="hidden" name="smilies['.$dirnum.']['.$smnums.'][displayorder]" value="0"><input type="hidden" name="smilies['.$dirnum.']['.$smnums.'][url]" value="'.$subentry.'">';
						$smnums++;
					}
				}
				showtablerow('', array('class="td25"', 'class="td28"'), array(
					($dirnum ? '&nbsp;' : $lang['add_new']),
					'<input type="text" class="txt" name="newdisplayorder['.$dirnum.']" value="'.($smtypes + $dirnum + 1).'" size="2" />',
					'<input class="checkbox" type="checkbox" name="newavailable['.$dirnum.']" value="1"'.($smnums ? ' checked="checked"' : ' disabled="disabled"').' />',
					'<input type="text" class="txt" name="newname['.$dirnum.']" value="" size="15" />',
					'./static/image/smiley/'.$entry.'<input type="hidden" name="newdirectory['.$dirnum.']" value="'.$entry.'">',
					"$smnums<input type=\"hidden\" name=\"smnums[$dirnum]\" value=\"$smnums\" />",
					$smilies,
					'',
					''

				));
				$dirnum++;
			}
		}

		if(!$dirnum) {
			showtablerow('', array('', 'colspan="8"'), array(
				cplang('add_new'),
				cplang('smiliesupload_tips')
			));
		}

		showsubmit('smiliessubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();

	} else {

		if(is_array($_G['gp_namenew'])) {
			foreach($_G['gp_namenew'] as $id => $val) {
				$_G['gp_availablenew'][$id] = $_G['gp_availablenew'][$id] && $_G['gp_smiliesnum'][$id] > 0 ? 1 : 0;
				DB::query("UPDATE ".DB::table('forum_imagetype')." SET available='{$_G['gp_availablenew'][$id]}', name='".htmlspecialchars(trim($val))."', displayorder='{$_G['gp_displayordernew'][$id]}' WHERE typeid='$id'");
			}
		}

		if($ids = dimplode($_G['gp_delete'])) {
			if(DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_smiley')." WHERE type='smiley' AND typeid IN ($ids)")) {
				cpmsg('smilies_delete_invalid', '', 'error');
			}
			DB::query("DELETE FROM ".DB::table('forum_imagetype')." WHERE typeid IN ($ids)");
		}

		if(is_array($_G['gp_newname'])) {
			foreach($_G['gp_newname'] as $key => $val) {
				$val = trim($val);
				if($val) {
					$smurl = './static/image/smiley/'.$newdiredctory[$key];
					if(!is_dir(DISCUZ_ROOT.$smurl)) {
						cpmsg('smilies_directory_invalid', '', 'error', array('smurl' => $smurl));
					}
					$newavailable[$key] = $newavailable[$key] && $smnums[$key] > 0 ? 1 : 0;
					$data = array(
						'available' => $newavailable[$key],
						'name' => htmlspecialchars($val),
						'type' => 'smiley',
						'displayorder' => $newdisplayorder[$key],
						'directory' => $_G['gp_newdirectory'][$key],
					);
					DB::insert('forum_imagetype', $data);
					if($smilies[$key]) {
						addsmilies(DB::insert_id(), $smilies[$key]);
					}
				}
			}
		}

		updatecache(array('smileytypes', 'smilies', 'smileycodes', 'smilies_js'));
		cpmsg('smilies_edit_succeed', 'action=smilies', 'succeed');

	}

} elseif($operation == 'edit' && $id) {

	$type = DB::fetch_first("SELECT typeid, name, directory FROM ".DB::table('forum_imagetype')." WHERE typeid='$id' AND type='smiley'");
	$smurl = './static/image/smiley/'.$type['directory'];
	$smdir = DISCUZ_ROOT.$smurl;
	if(!is_dir($smdir)) {
		cpmsg('smilies_directory_invalid', '', 'error', array('smurl' => $smurl));
	}
	$fastsmiley = unserialize(DB::result_first("SELECT svalue FROM ".DB::table('common_setting')." WHERE skey='fastsmiley'"));

	if(!$do) {

		if(!submitcheck('editsubmit')) {

			$smiliesperpage = 100;
			$start_limit = ($page - 1) * $smiliesperpage;

			$num = DB::result_first("SELECT COUNT(*) FROM ".DB::table('common_smiley')." WHERE typeid='$id' AND type='smiley'");
			$multipage = multi($num, $smiliesperpage, $page, ADMINSCRIPT.'?action=smilies&operation=edit&id='.$id);

			$smileynum = 1;
			$smilies = '';
			// 增加第二排序條件 id
			$query = DB::query("SELECT * FROM ".DB::table('common_smiley')." WHERE typeid='$id' AND type='smiley' ORDER BY displayorder, id LIMIT $start_limit, $smiliesperpage");
			while($smiley =	DB::fetch($query)) {
				$smilies .= showtablerow('', array('class="td25"', 'class="td28 td24"', 'class="td25"', 'class="td23"', 'class="td23"', 'class="td24"'), array(
					"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$smiley[id]\">",
					"<input type=\"text\" class=\"txt\" size=\"2\" name=\"displayorder[$smiley[id]]\" value=\"$smiley[displayorder]\">",
					"<input class=\"checkbox\" type=\"checkbox\" name=\"fast[]\" ".(in_array($smiley['id'], $fastsmiley[$id]) ? 'checked="checked"' : '')." value=\"$smiley[id]\">",
					"<img src=\"$smurl/$smiley[url]\" border=\"0\" onload=\"if(this.height>30) {this.resized=true; this.height=30;}\" onmouseover=\"if(this.resized) this.style.cursor='pointer';\" onclick=\"if(!this.resized) {return false;} else {window.open(this.src);}\">",
					$smiley['id'],
					"<input type=\"text\" class=\"txt\" size=\"25\" name=\"code[$smiley[id]]\" value=\"".dhtmlspecialchars($smiley['code'])."\" id=\"code_$smileynum\" smileyid=\"$smiley[id]\" />",
					"<input type=\"hidden\" value=\"$smiley[url]\" id=\"url_$smileynum\">$smiley[url]"
				), TRUE);
				$imgfilter[] = $smiley[url];
				$smileynum ++;
			}

			//BUG:自然排序時以 10 為開頭

			echo <<<EOT
<script type="text/JavaScript">
	function addsmileycodes(smiliesnum, pre) {
		smiliesnum = parseInt(smiliesnum);
		if(smiliesnum > 1) {
			for(var i = 1; i < smiliesnum; i++) {
				var prefix = trim($(pre + 'prefix').value);
				var suffix = trim($(pre + 'suffix').value);
				var page = parseInt('$page');
				var middle = $(pre + 'middle').value == 1 ? $(pre + 'url_' + i).value.substr(0,$(pre + 'url_' + i).value.lastIndexOf('.')) : ($(pre + 'middle').value == 2 ? i + (page > 0 ? page - 1 : 0) * 10 : $(pre + 'code_'+ i).attributes['smileyid'].nodeValue);
				if(!prefix || prefix == '$lang[smilies_prefix]' || !suffix || suffix == '$lang[smilies_suffix]') {
					alert('$lang[smilies_prefix_tips]');
					return;
				}
				suffix = !suffix || suffix == '$lang[smilies_suffix]' ? '' : suffix;
				$(pre + 'code_' + i).value = prefix + middle + suffix;
			}
		}
	}
	function autoaddsmileycodes(smiliesnum) {
		smiliesnum = parseInt(smiliesnum);
		if(smiliesnum > 1) {
			for(var i = 1; i < smiliesnum; i++) {
				$('code_' + i).value = '{:' + '$id' + '_' + $('code_'+ i).attributes['smileyid'].nodeValue + ':}';
			}
		}

	}
	function clearinput(obj, defaultval) {
		if(obj.value == defaultval) {
			obj.value = '';
		}
	}
</script>
EOT;

			shownav('style', 'nav_smilies');
			showsubmenu(cplang('smilies_edit').' - '.$type['name'], array(
				array('smilies_type', 'smilies', 0),
				array('admin', "smilies&operation=edit&id=$id", !$do),
				array('add', "smilies&operation=edit&do=add&id=$id", $do == 'add')
			));
			showformheader("smilies&operation=edit&id=$id");
			showhiddenfields(array('page' => $_G['gp_page']));
			showtableheader('', 'nobottom');
			showsubtitle(array('', 'display_order', 'smilies_fast', 'smilies_edit_image', 'smilies_id', 'smilies_edit_code', 'smilies_edit_filename'));
			echo $smilies;
			showtablerow('', array('', 'colspan="5"'), array(
				'',
				$lang['smilies_edit_add_code'].' <input type="text" class="txt" style="margin-right:0;width:40px;" size="2" value="{:" title="'.$lang['smilies_prefix'].'" id="prefix" onclick="clearinput(this, \''.$lang['smilies_prefix'].'\')" /> + <select id="middle"><option value="1">'.$lang['smilies_edit_order_file'].'</option><option value="2">'.$lang['smilies_edit_order_radom'].'</option><option value="3">'.$lang['smilies_id'].'</option></select> + <input type="text" class="txt" style="margin-right:0;width:40px;" size="2" value=":}" title="'.$lang['smilies_suffix'].'" id="suffix" onclick="clearinput(this, \''.$lang['smilies_suffix'].'\')" /> <input type="button" class="btn" onclick="addsmileycodes(\''.$smileynum.'\', \'\');" value="'.$lang['apply'].'" /> &nbsp;&nbsp; <input type="button" class="btn" onclick="autoaddsmileycodes(\''.$smileynum.'\');" value="'.$lang['smilies_edit_addcode_auto'].'" />'
			));
			showsubmit('editsubmit', 'submit', 'del', '', $multipage);
			showtablefooter();
			showformfooter();

		} else {

			if($ids = dimplode($_G['gp_delete'])) {
				DB::query("DELETE FROM	".DB::table('common_smiley')." WHERE id IN ($ids)");
			}

			$unsfast = array();
			if(is_array($_G['gp_displayorder'])) {
				foreach($_G['gp_displayorder'] as $key => $val) {
					if(!in_array($key, $_G['gp_fast'])) {
						$unsfast[] = $key;
					}
					$_G['gp_displayorder'][$key] = intval($_G['gp_displayorder'][$key]);
					$_G['gp_code'][$key] = trim($_G['gp_code'][$key]);
					$codeadd = !empty($_G['gp_code'][$key]) ? ", code='{$_G['gp_code'][$key]}'" : '';
					DB::query("UPDATE ".DB::table('common_smiley')." SET displayorder='{$_G['gp_displayorder'][$key]}' $codeadd WHERE id='$key'");
				}
			}

			$fastsmiley[$id] = array_diff(array_unique(array_merge((array)$fastsmiley[$id], (array)$_G['gp_fast'])), $unsfast);
			DB::insert('common_setting', array('skey' => 'fastsmiley', 'svalue' => addslashes(serialize($fastsmiley))), false, true);

			updatecache(array('smilies', 'smileycodes', 'smilies_js'));
			cpmsg('smilies_edit_succeed', "action=smilies&operation=edit&id=$id&page=$_G[gp_page]", 'succeed');

		}

	} elseif($do == 'add') {

		if(!submitcheck('editsubmit')) {

			shownav('style', 'nav_smilies');
			showsubmenu(cplang('smilies_edit').' - '.$type[name], array(
				array('smilies_type', 'smilies', 0),
				array('admin', "smilies&operation=edit&id=$id", !$do),
				array('add', "smilies&operation=edit&do=add&id=$id", $do == 'add')
			));
			showtips('smilies_tips');
			showtagheader('div', 'addsmilies', TRUE);
			showtableheader('smilies_add', 'notop fixpadding');
			showtablerow('', '', "<span class=\"bold marginright\">$lang[smilies_type]:</span>$type[name]");
			showtablerow('', '', "<span class=\"bold marginright\">$lang[dir]:</span>$smurl $lang[smilies_add_search]");
			showtablerow('', '', '<input type="button" class="btn" value="'.$lang['search'].'" onclick="ajaxget(\''.ADMINSCRIPT.'?action=smilies&operation=edit&do=add&id='.$id.'&search=yes\', \'addsmilies\', \'addsmilies\', \'auto\');doane(event);">');
			showtablefooter();
			showtagfooter('div');
			if($_G['gp_search']) {

				$newid = 1;
				$newimages = '';
				$imgfilter =  array();
				$query = DB::query("SELECT url FROM ".DB::table('common_smiley')." WHERE typeid='$id' AND type='smiley'");
				while($img = DB::fetch($query)) {
					$imgfilter[] = $img[url];
				}

				// bluelovers
				$_pic_files = array();
				// bluelovers

				$smiliesdir = dir($smdir);
				while($entry = $smiliesdir->read()) {
					if(in_array(strtolower(fileext($entry)), $imgextarray) && !in_array($entry, $imgfilter) && preg_match("/^[\w\-\.\[\]\(\)\<\> &]+$/", substr($entry, 0, strrpos($entry, '.'))) && strlen($entry) < 30 && is_file($smdir.'/'.$entry)) {
				// bluelvoers
						array_push($_pic_files, $entry);
					}
				}

				// 搜索出的表情依照數字排序
				sort($_pic_files, SORT_NUMERIC);

				foreach ($_pic_files as $entry) {
					if (1) {
				// bluelovers
						$newimages .= showtablerow('', array('class="td25"', 'class="td28 td24"', 'class="td23"'), array(
							"<input class=\"checkbox\" type=\"checkbox\" name=\"smilies[$newid][available]\" value=\"1\" checked=\"checked\">",
							"<input type=\"text\" class=\"txt\" size=\"2\" name=\"smilies[$newid][displayorder]\" value=\"0\">",
							"<img src=\"$smurl/$entry\" border=\"0\" onload=\"if(this.height>30) {this.resized=true; this.height=30;}\" onmouseover=\"if(this.resized) this.style.cursor='pointer';\" onclick=\"if(!this.resized) {return false;} else {window.open(this.src);}\">",
							"<input type=\"hidden\" size=\"25\" name=\"smilies[$newid][url]\" value=\"$entry\" id=\"addurl_$newid\">$entry"
						), TRUE);
						$newid ++;
					}
				}
				$smiliesdir->close();

				ajaxshowheader();

				if($newimages) {

					showformheader("smilies&operation=edit&do=add&id=$id");
					showtableheader('smilies_add', 'notop fixpadding');
					showsubtitle(array('', 'display_order', 'smilies_edit_image', 'smilies_edit_filename'));
					echo $newimages;
					showtablerow('', array('class="td25"', 'colspan="3"'), array(
						'<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'add\')" class="checkbox" checked="checked">'.$lang['enable'],
						'<input type="submit" class="btn" name="editsubmit" value="'.$lang['submit'].'"> &nbsp; <input type="button" class="btn" value="'.$lang['research'].'" onclick="ajaxget(\''.ADMINSCRIPT.'?action=smilies&operation=edit&do=add&id='.$id.'&search=yes\', \'addsmilies\', \'addsmilies\', \'auto\');doane(event);">'
					));
					showtablefooter();
					showformfooter();

				} else {

					showtableheader('smilies_add', 'notop');
					showtablerow('', 'class="lineheight"', cplang('smilies_edit_add_tips', array('smurl' => $smurl)));
					showtablerow('', '', '<input type="button" class="btn" value="'.$lang['research'].'" onclick="ajaxget(\''.ADMINSCRIPT.'?action=smilies&operation=edit&do=add&id='.$id.'&search=yes\', \'addsmilies\', \'addsmilies\', \'auto\');doane(event);">');
					showtablefooter();

				}

				ajaxshowfooter();
			}

		} else {

			if(is_array($_G['gp_smilies'])) {
				addsmilies($id, $_G['gp_smilies']);
			}

			updatecache(array('smilies', 'smileycodes', 'smilies_js'));
			cpmsg('smilies_edit_succeed', "action=smilies&operation=edit&id=$id", 'succeed');
		}
	}

} elseif($operation == 'update' && $id) {

	if(!$smtype = DB::fetch_first("SELECT name, directory FROM ".DB::table('forum_imagetype')." WHERE typeid='$id' AND type='smiley'")) {
		cpmsg('smilies_type_nonexistence', '', 'error');
	} else {
		$smurl = './static/image/smiley/'.$smtype['directory'];
		$smdir = DISCUZ_ROOT.$smurl;
		if(!is_dir($smdir)) {
			cpmsg('smilies_directory_invalid', '', 'error', array('smurl' => $smurl));
		}
	}

	$num = 0;
	$smilies = $imgfilter =  array();
	$query = DB::query("SELECT url FROM ".DB::table('common_smiley')." WHERE typeid='$id' AND type='smiley'");
	while($img = DB::fetch($query)) {
		$imgfilter[] = $img[url];
	}
	$smiliesdir = dir($smdir);
	while($entry = $smiliesdir->read()) {
		if(in_array(strtolower(fileext($entry)), $imgextarray) && !in_array($entry, $imgfilter) && preg_match("/^[\w\-\.\[\]\(\)\<\> &]+$/", substr($entry, 0, strrpos($entry, '.'))) && strlen($entry) < 30 && is_file($smdir.'/'.$entry)) {
			$smilies[] = array('available' => 1, 'displayorder' => 0, 'url' => $entry);
			$num++;
		}
	}
	$smiliesdir->close();

	if($smilies) {
		addsmilies($id, $smilies);
		updatecache(array('smilies', 'smileycodes', 'smilies_js'));
		cpmsg('smilies_update_succeed', "action=smilies", 'succeed', array('smurl' => $smurl, 'num' => $num, 'typename' => $smtype['name']));
	} else {
		cpmsg('smilies_update_error', '', 'error', array('smurl' => $smurl));
	}

} elseif($operation == 'import') {

	if(!submitcheck('importsubmit')) {

		shownav('style', 'smilies_edit');
		showsubmenu('nav_smilies', array(
			array('smilies_type', 'smilies', 0),
			array('smilies_import', 'smilies&operation=import', 1),
		));
		showtips('smilies_tips_import');
		showformheader('smilies&operation=import', 'enctype');
		showtableheader('smilies_import');
		showimportdata();
		showsubmit('importsubmit');
		showtablefooter();
		showformfooter();

	} else {

		require_once libfile('function/importdata');
		$renamed = import_smilies();
		if($renamed) {
			cpmsg('smilies_import_succeed_renamed', 'action=smilies', 'succeed');
		} else {
			cpmsg('smilies_import_succeed', 'action=smilies', 'succeed');
		}

	}

}

function addsmilies($typeid, $smilies = array()) {
	if(is_array($smilies)) {
		$ids = array();
		foreach($smilies as $smiley) {
			if($smiley['available']) {
				$data = array(
					'type' => 'smiley',
					'displayorder' => $smiley['displayorder'],
					'typeid' => $typeid,
					'code' => '',
					'url' => $smiley['url'],
				);
				$ids[] = DB::insert('common_smiley', $data);
			}
		}
		if($ids = dimplode($ids)) {
			DB::query("UPDATE ".DB::table('common_smiley')." SET code=CONCAT('{:', typeid, '_', id, ':}') WHERE id IN ($ids)");
		}
	}
}

?>