<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: cache_forumrecommend.php 20322 2011-02-21 09:00:53Z liulanbo $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

/**
 * 各版塊的推薦群組
 * hack:
 *		單一群組可推薦多個版塊
 *		版塊也可進行推薦
 *
$data['forumrecommend'] = array (
  55 =>
  array (
    0 =>
    array (
      'fid' => '221',
      'name' => '断罪の楽園',
      'icon' => 'static/image/common/groupicon.gif',
    ),
  ),
  75 =>
  array (
    0 =>
    array (
      'fid' => '227',
      'name' => 'fhgfh',
      'icon' => 'data/attachment/group/70/group_227_icon.jpg',
    ),
  ),
);
*/
function build_cache_forumrecommend() {
	// bluelovers
	require_once libfile('function/group');
	// bluelovers

	$data = array();
	$query = DB::query("SELECT fid FROM ".DB::table('forum_forum')." WHERE type<>'group' AND status<>3");

	while($row = DB::fetch($query)) {
		require_once libfile('function/group');
		$squery = DB::query("SELECT f.fid, f.name, f.threads, f.lastpost, ff.icon, ff.membernum, ff.description FROM ".DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff ON ff.fid=f.fid WHERE recommend='$row[fid]'");
		while($group = DB::fetch($squery)) {
			$group['icon'] = get_groupimg($group['icon'], 'icon');
			$lastpost = array(0, 0, '', '');
			$group['lastpost'] = is_string($group['lastpost']) ? explode("\t", $group['lastpost']) : $group['lastpost'];
			$group['lastpost'] =count($group['lastpost']) != 4 ? $lastpost : $group['lastpost'];
			list($lastpost['tid'], $lastpost['subject'], $lastpost['dateline'], $lastpost['author']) = $group['lastpost'];
			if($lastpost['tid']) {
				$lastpost['dateline'] = dgmdate($lastpost['dateline'], 'Y-m-d H:i:s');
				if($lastpost['author']) {
					$lastpost['encode_author'] = rawurlencode($lastpost['author']);
				}
				$group['lastpost'] = $lastpost;
			} else {
				$group['lastpost'] = '';
			}
			$data[$row['fid']][] = $group;
		}
	}

	save_syscache('forumrecommend', $data);
}

?>