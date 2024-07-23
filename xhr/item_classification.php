<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_sel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_autocomplete.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_upstore.php');


//require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_ship_factoryserialnum.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_factoryserialnum_input.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_factoryserialnum_upstore.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_has_model.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_model.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_sel.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_text_sep.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_uplink.php');


require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipclass_sel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/class_uplink.php');


require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_shipyard_input.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_shipyard_autocomplete.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_shipyard_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_shipyard_uplink.php');



// =============================================================================
function outhtml_script_item_classification() {

$str = <<<SCRIPTSTRING

function js_item_classification_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	return true;
}


function item_classification_reload() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/item_classification.php?i=' + item_id + '&h=full' + '';
			return ajax_my_get_query(url);
		}
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_classification_content($param) {

	$out = '';
	
	$out .= '<div style=" background-color: #ffffff; border: solid 1px #b0b0b0; margin-top: 20px; border-radius: 4px; -moz-border-radius: 4px;  margin-top: 5px; margin-right: 5px; padding: 5px 15px 15px 15px; ">';

		// first block
		$out .= '<div style=" margin-bottom: 10px; ">';
	
			// корабль
			$out .= '<div style=" float: left; width: 320px; ">';
				
				$out .= '<div style="  margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Название корабля:</div>';
				
				$out .= '<div style=" float: left; ">';
					$out .= outhtml_ship_sel_div(array('i' => $param['i']));
				$out .= '</div>';
				
				$out .= '<div style=" float: left; ">';
					$out .= outhtml_ship_upstore_div(array('i' => $param['i']));
				$out .= '</div>';
				
				
				
				$out .= '<div style=" clear: both; "></div>';
				
			$out .= '</div>';
			
			// заводской номер
			$out .= '<div style=" float: right; width: 180px; ">';
			
				$out .= '<div style=" float: left; margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Заводской номер корабля:</div>';
				
				$out .= '<div style=" clear: left; "></div>';
				
				$out .= '<div style=" float: left; ">';
					// $out .= outhtml_item_ship_factoryserialnum_div(array('i' => $param['i']));
					$out .= outhtml_ship_factoryserialnum_input_div(array('i' => $param['i']));
				$out .= '</div>';
				
				$out .= '<div style=" float: left; ">';
					$out .= outhtml_ship_factoryserialnum_upstore_div(array('i' => $param['i']));
				$out .= '</div>';
				
				$out .= '<div style=" clear: both; "></div>';
			
			$out .= '</div>';
			
			// clear
			$out .= '<div style=" clear: both; "></div>';

			// подсказка на корабль
			$out .= '<div style=" float: left; ">';

				$out .= outhtml_ship_autocomplete_div(array('i' => $param['i']));

			$out .= '</div>';
			
			// По проекту ли
			
			$out .= '<div style=" float: left; width: 320px; margin-top: 40px; ">';
					$out .= outhtml_ship_has_model_div(array('i' => $param['i']));
					$out .= '&nbsp; Построен по проекту';
			$out .= '</div>';
			
			// clear
			// $out .= '<div style=" clear: both; "></div>';
			
			// верфь
			$out .= '<div style=" float: right; width: 180px; ">';
			
				$out .= '<div style=" float: left; margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Верфь постройки:</div>';
				
				$out .= '<div style=" clear: left; "></div>';

				$out .= '<div style=" float: left; ">';
					$out .= outhtml_ship_shipyard_input_div(array('i' => $param['i']));
				$out .= '</div>';
				
				$out .= '<div style=" float: left; ">';
					$out .= outhtml_ship_shipyard_upstore_div(array('i' => $param['i']));
				$out .= '</div>';
				
				$out .= '<div style=" float: left; ">';
					$out .= outhtml_ship_shipyard_uplink_div(array('i' => $param['i']));
				$out .= '</div>';

			$out .= '</div>';
			
			// clear
			$out .= '<div style=" clear: both; "></div>';
			
			// подсказка на верфь
			$out .= '<div style=" float: right; ">';

				$out .= outhtml_ship_shipyard_autocomplete_div(array('i' => $param['i']));

			$out .= '</div>';

			$out .= '<div style=" clear: both; "></div>';
		
		$out .= '</div>';




		$out .= '<div style=" ">';
			$out .= outhtml_form_model_div(array('i' => $param['i']));
		$out .= '</div>';



		
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_classification_div($param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	//

	$out = '';
	
	$out .= outhtml_script_item_classification();
	
	$out .= outhtml_script_ship_sel();
	$out .= outhtml_script_ship_autocomplete();
	
	$out .= outhtml_script_ship_factoryserialnum_input();
	
	$out .= outhtml_script_ship_shipyard_input();
	$out .= outhtml_script_ship_shipyard_autocomplete();
	$out .= outhtml_script_ship_shipyard_uplink();
	$out .= outhtml_script_ship_shipyard_upstore();
	
	$out .= outhtml_script_ship_has_model();
	
	$out .= outhtml_script_form_model();
	
	$out .= outhtml_script_shipmodel_sel();
	// $out .= outhtml_script_shipmodel_text_sep();
	$out .= outhtml_script_shipmodel_upstore();
	$out .= outhtml_script_shipmodel_uplink();
	$out .= outhtml_script_model_autocomplete();

	$out .= outhtml_script_model_natoc_input();
	$out .= outhtml_script_model_natoc_upstore();
	$out .= outhtml_script_model_natoc_uplink();
	$out .= outhtml_script_model_natoc_autocomplete();
	
	$out .= outhtml_script_shipclass_sel();
	$out .= outhtml_script_class_uplink();
	$out .= outhtml_script_class_enlist();
	$out .= outhtml_script_class_autocomplete();
	
	$out .= '<input type="hidden" id="item_classification_item_id" value="'.$param['i'].'" />';
	
	$out .= '<div id="item_classification_div" >';
	
		$out .= outhtml_item_classification_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_item_classification_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'item_classification_div';
		$param['html'] = outhtml_item_classification_content($param);
	}
		
	return true;
}


// =============================================================================
function jqfn_item_classification($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_item_classification_callback';

	if (!isset($param['c'])) $param['c'] = '';

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_item_classification_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_classification.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_ic');
			jqfn_item_classification($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>