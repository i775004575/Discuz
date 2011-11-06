<?php

/**
 * @author bluelovers
 * @copyright 2011
 */

if (!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

include_once libfile('class/sco_dx_plugin', 'source', 'extensions/');

class plugin_sco_attach extends _sco_dx_plugin {

	function __construct() {
		$this->identifier = $this->_get_identifier(__CLASS__);
	}

}

class plugin_sco_attach_forum extends plugin_sco_attach {

	function attachment_message() {
		$_v = $this->_parse_method(__METHOD__, 1);

		if (
			CURSCRIPT == $_v[1]
			&& CURMODULE == $_v[2]
		) {
			$this->_hook(
				'Func_dshowmessage:Before_custom', array(
					$this,
					'_my_hook_attachment_message'
			));
		}
	}

	function _my_hook_attachment_message($_EVENT, $_conf) {
		global $_G;

		// copy from forum_index
		list($navtitle, $metadescription, $metakeywords) = get_seosetting('forum');
		if(!$navtitle) {
			$navtitle = $_G['setting']['navs'][2]['navname'];
			$nobbname = false;
		} else {
			$nobbname = true;
		}
		if(!$metadescription) {
			$metadescription = $navtitle;
		}
		if(!$metakeywords) {
			$metakeywords = $navtitle;
		}

		if (!empty($_G['forum_attach_filename'])) {
			$_conf['_data_dshowmessage_']['globalvars']['navtitle'] =
				strreplace_strip_split(array(), array(),
				lang('template', 'e_attach')
				. ': '
				. $_G['forum_attach_filename']
				. ' - '
				. $_conf['navtitle']
				)
			;

			$_conf['_data_dshowmessage_']['globalvars']['navigation'] =
				'<em>&raquo;</em> '
				. '<span>' . lang('template', 'e_attach'). '</span> '
				. '<em>&raquo;</em> '
				. '<span>' . $_G['forum_attach_filename']. '</span> '
			;

			$_conf['_data_dshowmessage_']['globalvars']['metadescription'] .=
				','.lang('template', 'e_attach')
				.','.$_G['forum_attach_filename']
			;
			$_conf['_data_dshowmessage_']['globalvars']['metakeywords'] .=
				','.$_G['forum_attach_filename']
				.','.lang('template', 'e_attach')
			;
		}

		$_conf['_data_dshowmessage_']['globalvars']['metadescription'] .= ','.$metadescription;
		$_conf['_data_dshowmessage_']['globalvars']['metakeywords'] .= ','.$metakeywords;
	}

}

?>