<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include_once libfile('class/sco_dx_plugin_inc', 'source', 'extensions/');

class plugin_sco_plugin_inc extends _sco_dx_plugin_inc {

	function &run() {
		$this->_setglobal('mod_lists', $this->_get_mod_list(__FILE__));

		//$this->_setglobal('debug', true, 'setting');

		$this->_setglobal('plugin_url', $this->_make_url(
			$this->module
			, 'plugin'
			, array(
				'mod' => $this->_getglobal('mod'),
				'op' => $this->_getglobal('op'),
			)
		));

		$this->_setglobal('plugin_self', &$this);

		$this->_setglobal('navigation', '<em>&raquo;</em>'.'Scorpio! Plugin Center');

		parent::run();

		return $this;
	}

}

?>