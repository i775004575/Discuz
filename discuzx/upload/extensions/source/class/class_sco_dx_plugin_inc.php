<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include_once libfile('class/sco_dx_plugin', 'source', 'extensions/');

class _sco_dx_plugin_inc extends _sco_dx_plugin {

	/**
	 * @return _sco_dx_plugin_inc
	 */
	function &init($identifier) {
		$this->_init($identifier);

		$this->_this(&$this);

		$this->_fix_plugin_setting();

		return $this;
	}

	function submitcheck($var, $allowget = 0, $seccodecheck = 0, $secqaacheck = 0) {
		return submitcheck($var, $allowget, $seccodecheck, $secqaacheck);
	}

	/**
	 * @return _sco_dx_plugin_inc
	 */
	function &view_header() {
		return $this;
	}

	/**
	 * @return _sco_dx_plugin_inc
	 */
	function &view_footer() {
		return $this;
	}

	/**
	 * @return _sco_dx_plugin_inc
	 */
	function &mod($mod, $identifier = '') {
		if (empty($identifier)) $identifier = CURMODULE;

		$identifier = self::_get_identifier($identifier);

		include_once libfile('mod/'.$mod, 'plugin/'.$identifier);

		$class = 'plugin_'.$identifier.'_'.$mod;
		$self = new $class();

		$self
			->init($identifier)
			->set(array(
				'mod' => $mod,
			))
		;

		return $self;
	}

	/**
	 * @return array
	 */
	function _get_mod_list($path = null) {
		$_list = array();

		$dir = dirname(isset($path) ? $path : __FILE__).'/mod/';

		if ($dh = opendir($dir)) {

			while(($entry = readdir($dh)) !== false) {
				if (!is_file($dir.$entry) || !preg_match('/^mod_(.+)\.php$/', $entry, $m)) continue;

				$key = $m[1];

				$_list[$key] = $key;
			}

		}

		return $_list;
	}

	function set($attr) {
		/*
		$this->attr['global'] = $attr;
		*/
		foreach ($attr as $_k => $_v) {
			$this->attr['global'][$_k] = $_v;

			if ($_k == 'module') $this->module = $_v;
		}

		return $this;
	}

	/**
	 * @return _sco_dx_plugin_inc
	 */
	function &run() {
		$operation = $this->_getglobal('op');

		$operation = $operation ? $operation : 'default';

		$method = 'on_op_'.$operation;

		/*
		ob_end_clean();

		ob_start();
		*/

		$this->$method();
		$_content = ob_get_contents();
		ob_end_clean();

		$GLOBALS['_G']['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();

		$this->view_header();
		echo $_content;

		if ($this->_getglobal('debug', 'setting')) {
			var_dump($this);
		}

		$this->view_footer();

		return $this;
	}

	/**
	 * 預設行為
	 *
	 * @return _sco_dx_plugin_inc
	 */
	function &on_op_default() {
		/*
		$this->on_op_list_fups();
		*/

		return $this;
	}

	/**
	 *
	 * @return db_mysql
	 */
	function &_db() {
		static $db;
		if (!isset($db)) $db = DB::object();
		return $db;
	}

}

?>