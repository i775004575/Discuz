<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: task_avatar.php 16433 2010-09-07 00:04:33Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class task_avatar {

	var $version = '1.0';
	var $name = 'avatar_name';
	var $description = 'avatar_desc';
	var $copyright = '<a href="http://www.comsenz.com" target="_blank">Comsenz Inc.</a>';
	var $icon = '';
	var $period = '';
	var $periodtype = 0;
	var $conditions = array();

	function csc($task = array()) {
		global $_G;

		// bluelovers
		if (empty($_G['member']['avatarstatus']) && uc_check_avatar($_G['member']['uid'])) {

		}
		// bluelovers

		if(!empty($_G['member']['avatarstatus'])) {
			return true;
		} else {
			return array('csc' => 0, 'remaintime' => 0);
		}
	}

	function view() {
		return lang('task/avatar', 'avatar_view');
	}

}

?>