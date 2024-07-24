<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipclass_sel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/class_enlist.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/class_uplink.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/class_autocomplete.php');




// =============================================================================
function outhtml_script_form_class() {

$str = <<<SCRIPTSTRING

function js_form_class_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	return true;
}


function form_class_reload() {
	
	var elem = document.getElementById('form_class_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/form_class.php?i=' + item_id + '&h=full' + '';
			return ajax_my_get_query(url);
		}
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_form_class_content($param) {

	$out = '';
	
	// классификация
	$out .= '<div style=" ">';
	
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Классификация:</div>';

		$out .= '<div style=" clear: left; "></div>';
		
		$out .= '<div style=" float: left; ">';
			$out .= outhtml_shipclass_sel_div(array('i' => $param['i']));
		$out .= '</div>';

		$out .= '<div style=" float: left; margin-left: 5px; ">';
			$d = array('i' => $param['i']);
			if (isset($param['full_enlist'])) {
				$d['full_enlist'] = $param['full_enlist'];
			}
			$out .= outhtml_class_enlist_div($d);
		$out .= '</div>';
		
		$out .= '<div style=" float: left; margin-left: 5px; ">';
			$out .= outhtml_class_uplink_div(array('i' => $param['i']));
		$out .= '</div>';

		$out .= '<div style=" clear: both; "></div>';

		$out .= '<div style=" ">';
			$d = array('i' => $param['i']);
			if (isset($param['full_enlist'])) {
				$d['full_enlist'] = $param['full_enlist'];
				$d['c'] = 'enlist';
			}
			$out .= outhtml_class_autocomplete_div($d);
		$out .= '</div>';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_form_class_div($param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	//

	$out = '';
	
	$out .= '<input type="hidden" id="form_class_item_id" value="'.$param['i'].'" />';
	
	$out .= '<div id="form_class_div" >';
	
		$out .= outhtml_form_class_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_form_class_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'form_class_div';
		$param['html'] = outhtml_form_class_content($param);
	}
		
	return true;
}


// =============================================================================
function jqfn_form_class($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_form_class_callback';

	if (!isset($param['c'])) $param['c'] = '';

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_form_class_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/form_class.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_ic');
			jqfn_form_class($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>