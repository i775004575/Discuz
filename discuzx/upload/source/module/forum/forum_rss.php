<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: forum_rss.php 19039 2010-12-14 08:40:59Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

error_reporting(E_ALL ^ E_NOTICE);

// bluelovers
@ignore_user_abort();
@set_time_limit(0);
// bluelovers

define('IN_DISCUZ', TRUE);
define('DISCUZ_ROOT', '');

loadcache('forums');

if(!$_G['setting']['rssstatus']) {
	exit('RSS Disabled');
}

$ttl = $_G['setting']['rssttl'] ? $_G['setting']['rssttl']: 30;
$num = 20;

$_G['groupid'] = 7;
$_G['uid'] = 0;
$_G['username'] = $_G['member']['password'] = '';
$rssfid = empty($_GET['fid']) ? 0 : intval($_GET['fid']);
$forumname = '';

// bluelovers
$_allow_format = array();

$_allow_format[] = 'baidu';
$_allow_format[] = 'sitemap';
$_allow_format[] = 'rss';
$_allow_format[] = 'atom';

$_G['gp_format'] = in_array($_G['gp_format'], $_allow_format) ? $_G['gp_format'] : 'rss';

$_rss_output_data = array();

switch ($_G['gp_format']) {

	case 'baidu':
		$_rss_output_data['ttl'] = 24;
		$_rss_output_data['data_format'] = 'Y-m-d H:i:s';

		$num = 500;
		break;

	case 'sitemap':

		$_rss_output_data['data_format'] = 'Y-m-d\TH:i:sP';

		$num = 500;
		break;

	case 'rss':
	default:
		$num = 20;
		break;
}
// bluelovers

if(empty($rssfid)) {
	foreach($_G['cache']['forums'] as $fid => $forum) {
		if(rssforumperm($forum)) {
			$fidarray[] = $fid;
		}
	}
} else {
	$forum = isset($_G['cache']['forums'][$rssfid]) && $_G['cache']['forums'][$rssfid]['type'] != 'group' ? $_G['cache']['forums'][$rssfid] : array();
	if(!isset($_G['cache']['forums'][$rssfid])) {
		$forum = $_G['cache']['forums'][$rssfid] = DB::fetch_first("SELECT f.*, ff.* FROM ".DB::table('forum_forum')." f LEFT JOIN ".DB::table('forum_forumfield')." ff ON ff.fid=f.fid WHERE f.fid='$rssfid' AND f.type='sub' LIMIT 1");
	}
	if($forum && rssforumperm($forum)) {
		$fidarray = array($rssfid);
		$forumname = dhtmlspecialchars($_G['cache']['forums'][$rssfid]['name']);
	} else {
		exit('Specified forum not found');
	}
}

$charset = $_G['config']['output']['charset'];
/*
dheader("Content-type: application/xml");
echo 	"<?xml version=\"1.0\" encoding=\"".$charset."\"?>\n".
	"<rss version=\"2.0\">\n".
	"  <channel>\n".
	(count($fidarray) > 1 ?
		"    <title>{$_G[setting][bbname]}</title>\n".
		"    <link>{$_G[siteurl]}forum.php</link>\n".
		"    <description>Latest $num threads of all forums</description>\n"
		:
		"    <title>{$_G[setting][bbname]} - $forumname</title>\n".
		"    <link>{$_G[siteurl]}forum.php?mod=forumdisplay&amp;fid=$rssfid</link>\n".
		"    <description>Latest $num threads of $forumname</description>\n"
	).
	"    <copyright>Copyright(C) {$_G[setting][bbname]}</copyright>\n".
	"    <generator>Discuz! Board by Comsenz Inc.</generator>\n".
	"    <lastBuildDate>".gmdate('r', TIMESTAMP)."</lastBuildDate>\n".
	"    <ttl>$ttl</ttl>\n".
	"    <image>\n".
	"      <url>{$_G[siteurl]}static/image/logo/logo_88_31.gif</url>\n".
	"      <title>{$_G[setting][bbname]}</title>\n".
	"      <link>{$_G[siteurl]}</link>\n".
	"    </image>\n";
*/

// bluelovers
$itemlist = array();
// bluelovers

if($fidarray) {
	$query = DB::query("SELECT * FROM ".DB::table('forum_rsscache')." WHERE fid IN (".dimplode($fidarray).") ORDER BY dateline DESC LIMIT $num");

	// bluelovers
	$_updatersscache_num = max(500, $num);
	$_updatersscache_run = false;

	if (!DB::num_rows($query)) {
		updatersscache($_updatersscache_num, $fidarray);

		$_updatersscache_run = true;

		$query = DB::query("SELECT * FROM ".DB::table('forum_rsscache')." WHERE fid IN (".dimplode($fidarray).") ORDER BY dateline DESC LIMIT $num");
	}
	// bluelovers

	if(DB::num_rows($query)) {
		while($thread = DB::fetch($query)) {
			if(!$_updatersscache_run && TIMESTAMP - $thread['lastupdate'] > $ttl * 60) {
				updatersscache($_updatersscache_num, $fidarray);

				$_updatersscache_run = true;

			/*
				break;
			} else {
			*/
			// bluelovers
			}

			if ($thread) {
			// bluelovers

				list($thread['description'], $attachremote, $attachfile, $attachsize) = explode("\t", $thread['description']);
				if($attachfile) {
					if($attachremote) {
						$filename = $_G['setting']['ftp']['attachurl'].'forum/'.$attachfile;
					} else {
						$filename = $_G['siteurl'].$_G['setting']['attachurl'].'forum/'.$attachfile;
					}
				}
				/*
				echo 	"    <item>\n".
					"      <title>".$thread['subject']."</title>\n".
					"      <link>$_G[siteurl]forum.php?mod=viewthread&amp;tid=$thread[tid]</link>\n".
					"      <description><![CDATA[".dhtmlspecialchars($thread['description'])."]]></description>\n".
					"      <category>".dhtmlspecialchars($thread['forum'])."</category>\n".
					"      <author>".dhtmlspecialchars($thread['author'])."</author>\n".
					($attachfile ? '<enclosure url="'.$filename.'" length="'.$attachsize.'" type="image/jpeg" />' : '').
					"      <pubDate>".gmdate('r', $thread['dateline'])."</pubDate>\n".
					"    </item>\n";
				*/
				// bluelovers
				$_item = $thread;

				$_item['attachremote'] = $attachremote;
				$_item['attachfile'] = $attachfile;
				$_item['attachsize'] = $attachsize;
				$_item['filename'] = $filename;

				$_item['lastpost'] = $_item['lastpost'] ? $_item['lastpost'] : $_item['dateline'];
				$_item['dateline'] = $_item['lastpost'] > $_item['dateline'] ? $_item['lastpost'] : $_item['dateline'];

				$itemlist[] = $_item;
				// bluelovers
			}
		}

	/*
	} else {
		updatersscache($num);
	*/

	}
}

/*
echo 	"  </channel>\n".
	"</rss>";
*/
// bluelovers
dheader("Content-type: application/xml");
include template('subblock/forum/rss/'.$_G['gp_format']);
// bluelovers

function updatersscache($num, $fidarray = array()) {
	global $_G;
	$processname = 'forum_rss_cache';

	// bluelovers
	if (!empty($fidarray)) sort($fidarray);

	$_fids = dimplode($fidarray);

	$_hash = md5($_fids);
	$processname .= $_hash;
	// bluelovers

	if(discuz_process::islocked($processname, 600)) {
		return false;
	}
	/*
	DB::query("DELETE FROM ".DB::table('forum_rsscache')."");
	*/
	// bluelovers
	DB::query("DELETE FROM ".DB::table('forum_rsscache')." WHERE fid IN (".$_fids.")");
	// bluelovers
	require_once libfile('function/post');
	/*
	foreach($_G['cache']['forums'] as $fid => $forum) {
	*/
	// bluelovers
	foreach($fidarray as $fid) {

		$forum = $_G['cache']['forums'][$fid];

	// bluelovers

		if($forum['type'] != 'group') {
			$query = DB::query("SELECT tid, readperm, author, dateline, subject

				, lastpost

				FROM ".DB::table('forum_thread')."
				WHERE fid='$fid' AND displayorder>='0'
				ORDER BY tid DESC LIMIT $num");
			$forum['name'] = addslashes($forum['name']);
			while($thread = DB::fetch($query)) {
				$thread['author'] = $thread['author'] != '' ? addslashes($thread['author']) : 'Anonymous';
				$thread['subject'] = addslashes($thread['subject']);
				$posttable = getposttablebytid($thread['tid']);
				$post = DB::fetch_first("SELECT pid, attachment, message, status FROM ".DB::table($posttable)." WHERE tid='{$thread['tid']}' AND first='1'");
				$attachdata = '';
				if($post['attachment'] == 2) {
					$attach = DB::fetch_first("SELECT remote, attachment, filesize FROM ".DB::table(getattachtablebytid($thread['tid']))." WHERE pid='{$post['pid']}' AND isimage='1' ORDER BY dateline LIMIT 1");
					$attachdata = "\t".$attach['remote']."\t".$attach['attachment']."\t".$attach['filesize'];
				}

				// bluelovers
				$thread['dateline'] = $thread['lastpost'];
				// bluelovers

				$thread['message'] = $post['message'];
				$thread['status'] = $post['status'];
				$thread['description'] = $thread['readperm'] > 0 || $thread['price'] > 0 || $thread['status'] & 1 ? '' : addslashes(messagecutstr($thread['message'], 250 - strlen($attachdata)).$attachdata);
				DB::query("REPLACE INTO ".DB::table('forum_rsscache')." (lastupdate, fid, tid, dateline, forum, author, subject, description)
					VALUES ('$_G[timestamp]', '$fid', '$thread[tid]', '$thread[dateline]', '$forum[name]', '$thread[author]', '$thread[subject]', '$thread[description]')");
			}
		}
	}
	discuz_process::unlock($processname);
	return true;
}

?>