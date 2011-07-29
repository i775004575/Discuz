<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if (!discuz_core::$plugin_support['Scorpio_Event']) return false;

Scorpio_Hook::add('Class_discuz_core::init:After', '_eClass_discuz_core_init_After');

/**
 * Event:
 * 		Class_discuz_core::init:After
 */
function _eClass_discuz_core_init_After($_EVENT, $discuz) {
	static $initated = false;

	if(!$initated) {

		/**
		 * 如果是訪客 或者 搜尋引擎時
		 */
		if (!$discuz->var['uid'] || IS_ROBOT) {
			// 隱藏 LOGO (因為是 flash 減少流量)
			discuz_core::$tpl['header_logo_hide'] = true;
		}

	}

	$initated = true;
}

Scorpio_Hook::add('Func_adshow:Before_return', '_goodnight_eFunc_adshow_Before_return');

/**
 * Event:
 * 		Func_adshow:Before_return
 */
function _goodnight_eFunc_adshow_Before_return($_EVENT, $_conf) {
	extract($_conf, EXTR_REFS);

	if ($params[0] == 'headerbanner' && $params[1] == 'wp a_h') {
		$adshow_return = '';

		$switchstop = true;
	}
}

?>