<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_sel.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_nick.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_autocomplete.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_natoc_input.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_natoc_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_natoc_uplink.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_natoc_autocomplete.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_class.php');



// =============================================================================
function outhtml_script_form_model() {

$str = <<<SCRIPTSTRING

function js_form_model_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	return true;
}


function form_model_reload() {

	// alert('cc');
	
	var elem = document.getElementById('form_model_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/form_model.php?i=' + item_id + '&h=full' + '';
			return ajax_my_get_query(url);
		}
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_form_model_content($param) {

	$out = '';
	
	// second block
	$out .= '<div style=" ">';

		$out .= '<div style=" margin-bottom: 10px; ">';
		
			$hasmodel = get_item_ship_has_model($param['i']);
			
			if ($hasmodel == 'Y') {

				$out .= '<div style=" ">';
			
					// проект
					$out .= '<div style=" float: left; width: 320px; margin-bottom: 10px; ">';
					
						$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Проект корабля (код «шифр»):</div>';
						
						$out .= '<div style=" clear: left; "></div>';
							
						$out .= '<div style=" float: left; ">';
							$out .= outhtml_shipmodel_sel_div(array('i' => $param['i']));
						$out .= '</div>';
						
						
						
						$out .= '<div style=" float: left; margin-left: 5px; ">';
							$out .= outhtml_shipmodel_upstore_div(array('i' => $param['i']));
						$out .= '</div>';

						$out .= '<div style=" float: left; margin-left: 5px; ">';
							$out .= outhtml_shipmodel_uplink_div(array('i' => $param['i']));
						$out .= '</div>';
						
						// ВРЕМЕННО
						/*
						if ($GLOBALS['user_id'] == 2) {
						
							$out .= '<div style=" float: left; margin-left: 5px; ">';
								$out .= outhtml_shipmodel_text_sep_div(array('i' => $param['i']));
							$out .= '</div>';
							
						}
						*/
						
						$out .= '<div style=" clear: both; "></div>';
						
					$out .= '</div>';

					// nato
					$out .= '<div style=" float: right; width: 180px; ">';

						$out .= '<div style=" float: left; margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Классификация НАТО:</div>';

						$out .= '<div style=" clear: left; "></div>';

						$out .= '<div style=" float: left; ">';
							$out .= outhtml_model_natoc_input_div(array('i' => $param['i']));
						$out .= '</div>';
						
						$out .= '<div style=" float: left; ">';
							$out .= outhtml_model_natoc_upstore_div(array('i' => $param['i']));
						$out .= '</div>';
						
						$out .= '<div style=" float: left; ">';
							$out .= outhtml_model_natoc_uplink_div(array('i' => $param['i']));
						$out .= '</div>';
						
						//
						$out .= '<div style=" clear: both; "></div>';
						
						//
						$out .= '<div style=" ">';
							$out .= outhtml_model_natoc_autocomplete_div(array('i' => $param['i']));
						$out .= '</div>';
						
						$out .= '<div style=" clear: both; "></div>';

					$out .= '</div>';

					// clear
					$out .= '<div style=" clear: both; "></div>';
					
					//
					$out .= '<div style=" ">';
						$out .= outhtml_model_autocomplete_div(array('i' => $param['i']));
					$out .= '</div>';
					
					

				$out .= '</div>';
			
			}

			// классификация
			$out .= '<div style=" ">';

				$out .= outhtml_form_class_div(array('i' => $param['i']));
			
			$out .= '</div>';

		$out .= '</div>';
		
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_form_model_div($param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	//

	$out = '';
	
	$out .= '<input type="hidden" id="form_model_item_id" value="'.$param['i'].'" />';
	
	$out .= '<div id="form_model_div" >';
	
		$out .= outhtml_form_model_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_form_model_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'form_model_div';
		$param['html'] = outhtml_form_model_content($param);
	}
		
	return true;
}


// =============================================================================
function jqfn_form_model($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_form_model_callback';

	if (!isset($param['c'])) $param['c'] = '';

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_form_model_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/form_model.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_ic');
			jqfn_form_model($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>