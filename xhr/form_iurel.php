<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_gotit.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_sellit.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_wantit.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_personalnote.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_storageplace.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_initialprice.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_sellprice.php');



// =============================================================================
function outhtml_script_form_iurel() {

$str = <<<SCRIPTSTRING

function js_form_iurel_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}
	
	return true;
}


function form_iurel_reload() {
	
	var elem = document.getElementById('form_iurel_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/form_iurel.php?i=' + item_id + '&h=full' + '';
			return ajax_my_get_query(url);
		}
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_form_iurel_content($param) {

	$out = '';
	
	$gotit = (iurel_get_value($param['i'], $GLOBALS['user_id'], 'gotit') == 'Y');
	$sellit = (iurel_get_value($param['i'], $GLOBALS['user_id'], 'sellit') == 'Y');
	
	$out .= '<input type="hidden" id="form_iurel_item_id" value="'.$param['i'].'" />';

		// raised rect
	
		$out .= '<div id="iurelrect" style=" width: 340px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px;  margin-top: 5px; padding: 15px 15px 15px 15px; color: #606060; line-height: 125%; ">';
		
			// top row
			$out .= '<div style=" margin-bottom: 10px; ">';
			
				// gotit
				
				$out .= '<div style=" float: left; ">';
				
					$out .= '<div style=" float: left; margin-right: 0px; font-size: 12px; padding: 2px 0px 0px 0px; ">';
						$out .= '<nobr>У меня </nobr>';
					$out .= '</div>';
					
					$out .= '<div style=" float: left; margin-left: 5px; font-size: 12px; padding: 0px 0px 0px 0px; ">';
						$out .= outhtml_iurel_gotit_div(array('i' => $param['i']));
					$out .= '</div>';
					
					$out .= '<div style=" clear: both; "></div>';
					
				$out .= '</div>';
				
				// sellit
				
				if ($gotit) {
				
					$out .= '<div style=" float: left; margin-left: 15px; ">';
					
						$out .= '<div style=" float: left; margin-right: 0px; font-size: 12px; padding: 2px 0px 0px 0px; ">';
							$out .= '<nobr>Продаю </nobr>';
						$out .= '</div>';
					
						$out .= '<div style=" float: left; margin-left: 5px; font-size: 12px; padding: 0px 0px 0px 0px; ">';
							$out .= outhtml_iurel_sellit_div(array('i' => $param['i']));
						$out .= '</div>';
						
						$out .= '<div style=" clear: both; "></div>';
						
					$out .= '</div>';
				
				}
				
				// wantit
				
				$out .= '<div style=" float: right; ">';
				
					$out .= '<div style=" float: left; margin-right: 0px; font-size: 12px; padding: 2px 0px 0px 0px; ">';
						$out .= '<nobr>Ищу </nobr>';
					$out .= '</div>';
				
					$out .= '<div style=" float: left; margin-left: 5px; font-size: 12px; padding: 0px 0px 0px 0px; ">';
						$out .= outhtml_iurel_wantit_div(array('i' => $param['i']));
					$out .= '</div>';
					
					$out .= '<div style=" clear: both; "></div>';
					
				$out .= '</div>';
				
				
				
				$out .= '<div style=" clear: both; "></div>';
				
			$out .= '</div>';
			
			// storageplace
			
			$out .= '<div>';
			
				if ($gotit) {
			
					$out .= '<div style=" font-size: 12px; padding: 2px 0px 0px 0px; ">';
						$out .= '<nobr>Место хранения:</nobr>';
					$out .= '</div>';
				
					
					$out .= '<div style=" font-size: 12px; padding: 0px 0px 0px 0px; ">';
						$out .= outhtml_iurel_storageplace_div(array('i' => $param['i']));
					$out .= '</div>';
					
					$out .= '<div style=" clear: both; "></div>';
				
				}
				
			$out .= '</div>';
			
			$out .= '<div style=" clear: both; "></div>';
			
			// купил за
			
			if ($gotit) {
			
				$out .= '<div style=" float: left; margin-top: 12px; margin-bottom: 5px; ">';
			
					$out .= '<div style=" float: left; font-size: 12px; padding: 5px 0px 0px 0px; margin-right: 5px; ">';
						$out .= '<nobr>Купил за</nobr>';
					$out .= '</div>';
				
					
					$out .= '<div style=" float: left; font-size: 12px; padding: 0px 0px 0px 0px; ">';
						$out .= outhtml_iurel_initialprice_div(array('i' => $param['i']));
					$out .= '</div>';
					
					$out .= '<div style=" clear: both; "></div>';
				
				$out .= '</div>';
			}
				
			
			// продаю за
			
			if ($gotit && $sellit) {
			
				$out .= '<div style=" float: right; margin-top: 12px; margin-bottom: 5px; ">';
			
					$out .= '<div style=" float: left; font-size: 12px; padding: 5px 0px 0px 0px; margin-right: 5px; ">';
						$out .= '<nobr>Продаю за</nobr>';
					$out .= '</div>';
				
					
					$out .= '<div style=" float: left; font-size: 12px; padding: 0px 0px 0px 0px; ">';
						$out .= outhtml_iurel_sellprice_div(array('i' => $param['i']));
					$out .= '</div>';
					
					$out .= '<div style=" clear: both; "></div>';
					
				$out .= '</div>';
				
			}
				
			
			
			$out .= '<div style=" clear: both; "></div>';
			
			// personalnote
			
			$out .= '<div>';
			
				$out .= '<div style=" margin-top: 5px; font-size: 12px; padding: 2px 0px 0px 0px; ">';
					$out .= '<nobr>Личные заметки:</nobr>';
				$out .= '</div>';
			
				
				$out .= '<div style=" font-size: 12px; padding: 0px 0px 0px 0px; ">';
					$out .= outhtml_iurel_personalnote_div(array('i' => $param['i']));
				$out .= '</div>';
				
				$out .= '<div style=" clear: both; "></div>';
				
			$out .= '</div>';
			
		$out .= '</div>';
		
	return $out.PHP_EOL;
		
				
		
		//$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Цена продажи:</div>';
		
		// $out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Куплен за:</div>';
		
}


// =============================================================================
function outhtml_form_iurel_div($param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	//

	$out = '';
	
	$out .= outhtml_script_form_iurel();
	$out .= outhtml_script_iurel_gotit();
	$out .= outhtml_script_iurel_wantit();
	$out .= outhtml_script_iurel_sellit();
	$out .= outhtml_script_iurel_personalnote();
	$out .= outhtml_script_iurel_storageplace();
	$out .= outhtml_script_iurel_initialprice();
	$out .= outhtml_script_iurel_sellprice();
	
	$out .= '<div id="form_iurel_div" >';
		$out .= outhtml_form_iurel_content($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_form_iurel_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'form_iurel_div';
		$param['html'] = outhtml_form_iurel_content($param);
	}
		
	return true;
}


// =============================================================================
function jqfn_form_iurel($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_form_iurel_callback';

	if (!isset($param['c'])) $param['c'] = '';

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_form_iurel_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/form_iurel.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_ic');
			jqfn_form_iurel($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>