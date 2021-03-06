<?php

/**
 * DiscuzX Convert
 *
 * $Id: posts.php 15398 2010-08-24 02:26:44Z monkey $
 */

$curprg = basename(__FILE__);
require_once DISCUZ_ROOT.'./include/editor.func.php';

$table_source = $db_source->tablepre . 'posts';
$table_target = $db_target->tablepre . 'forum_post';

$limit = $setting['limit']['posts'] ? $setting['limit']['posts'] : 5000;

// 無奈降低每次執行的數量
$limit = 250;

$start = getgpc('start');
$start = intval($start);
$nextid = 0;

if($start == 0) {
	$db_target->query("TRUNCATE $table_target");
}

$query = $db_source->query("SELECT * FROM $table_source WHERE pid>'$start' LIMIT $limit");
while($row = $db_source->fetch_array($query)) {
	$nextid = $row['pid'];

	// bluelovers
//	$s = '<textarea style="width: 100%; height: 300px">'.$row['message'].'</textarea>';

	$row['message'] = s_trim($row['message']);
	$text = bbcode_fix($row['message']);

//	if (($text != $row['message']) && ($nextid > $start + 500)) showmessage($s.'<textarea style="width: 100%; height: 300px">'.$text.'</textarea>');

	$row['message'] = $text;

	// 回收記憶體
	unset($text);
	// bluelovers

	$row = daddslashes($row, 1);
	$data = implode_field_value($row, ',', db_table_fields($db_target, $table_target));
	$db_target->query("INSERT INTO $table_target SET $data");
}

if($nextid) {
	showmessage("繼續轉換數據表 ".$table_source." pid > $nextid", "index.php?a=$action&source=$source&prg=$curprg&start=$nextid");
} else {
	$maxpid = $db_target->result_first("SELECT MAX(pid) FROM $table_target");
	$maxpid = intval($maxpid) + 1;
	$db_target->query("ALTER TABLE ".$db_target->tablepre.'forum_post_tableid'." AUTO_INCREMENT=$maxpid");
}
?>