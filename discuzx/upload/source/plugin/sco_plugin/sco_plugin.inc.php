<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

include_once libfile('class/sco_dx_plugin', 'source', 'extensions/');

class plugin_sco_plugin extends _sco_dx_plugin {

	public function __construct() {
		$this->_init($this->_get_identifier(__CLASS__));
		$this->_this(&$this);
	}

}

class plugin_sco_plugin_plugin extends plugin_sco_plugin {

	function plugin_message() {
		echo $this->identifier;

		debug($this);
	}

}

?>