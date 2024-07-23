<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_shipmodel_text_sep() {

$str = <<<SCRIPTSTRING

function js_shipmodel_text_sep_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
		if (aresp['elemtoplace'] == 'shipmodel_text_sep_div') {
			var elem2 = document.getElementById('model_autocomplete_div');
			if (elem2) {
				elem2.innerHTML = '';
			}
		}
	}

	
	if (typeof aresp['show_upstore'] == 'string') {
		var elem = document.getElementById('model_natoc_upstore_div');
		if (elem) {
			if (aresp['show_upstore'] == 'yes') {	
				// alert('z1');
				elem.style.visibility = 'visible';
			}
			if (aresp['show_upstore'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
		
	if (typeof aresp['show_uplink'] == 'string') {
		var elem = document.getElementById('model_natoc_uplink_div');
		if (elem) {
			if (aresp['show_uplink'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_uplink'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	
	if (typeof aresp['color_input'] == 'string') {
		var elem = document.getElementById('model_natoc_input');
		if (elem) {
			if (aresp['color_input'] == 'red') aresp['color_input'] = 'ffd7d7';
			if (aresp['color_input'] == 'yellow') aresp['color_input'] = 'fff4ae';
			if (aresp['color_input'] == 'green') aresp['color_input'] = 'd6ffd5';
			if (aresp['color_input'] == 'purple') aresp['color_input'] = 'ffd6ff';
			elem.style.backgroundColor = '#' + aresp['color_input'];
		}
	}
	
	if (typeof aresp['focusinput'] == 'string') {
		if (aresp['focusinput'] == 'yes') {
			var elem = document.getElementById('shipmodel_text_sep_search_input');
			if (elem) {
				elem.focus();
				var length = elem.value.length;  
				elem.setSelectionRange(length, length);  
			}
		}
	}
	
	if (typeof aresp['reload_model_form'] == 'string') {
		if (aresp['reload_model_form'] == 'yes') {
			var func = window['item_classification_reload'];
			if (func) {
				if (typeof func == 'function') {
					func();
				}
			}
		}
	}
	
	
	
	return true;
}



SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}



// =============================================================================
function outhtml_shipmodel_text_sep_content(&$param) {

	$out = '';
	
	// check state
	if (!isset($param['state'])) $param['state'] = 'close';
	if ($param['state'] != 'open') $param['state'] = 'close';
	

	//
	/*
	if ($param['state'] != 'open') {
		$onselect = ' js_shipmodel_text_sep_open('.$param['i'].'); return false; ';
	} else {
		$onselect = '';
	}
	*/
	
	$onselect = ' js_shipmodel_text_sep_open('.$param['i'].'); return false; ';
	
	/* background-color: #87a3b4; */
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #f00000;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_add.png\'); " onclick=" '.$onselect.' " title=" сохранить проект в базу ">';
	$out .= '</div>';

	//
	
	if ($param['state'] == 'open') {
	
		$out .= outhtml_shipmodel_text_sep_popup(&$param);
	
	}
	
	//
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipmodel_text_sep_div($param) {

	$out = '';
	
	if (can_i_shipmodel_upstore($param)) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="shipmodel_text_sep_div" style=" position: relative; font-size: 11px; '.$insertstyle.'">';
		$out .= outhtml_shipmodel_text_sep_content($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}



// =============================================================================
function jqfn_shipmodel_text_sep($param) {

	if (!am_i_registered_user()) return false;
	
	$param['ajp'] = array();

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_shipmodel_text_sep_callback';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	if (!isset($param['c'])) $param['c'] = '';
	
	if ($param['c'] == 'saveandclose') {
		// $param['ajp']['elemtoplace'] = 'shipmodel_text_sep_div';
		// $param['html'] = outhtml_shipmodel_text_sep_content(&$param);
		// $param['ajp']['doooooooo'] = 'doooooooo';
		jqfn_shipmodel_text_sep_saveandclose(&$param);
	}

	
	$param['ajp']['elemtoplace'] = 'shipmodel_text_sep_div';
	$param['html'] = outhtml_shipmodel_text_sep_content(&$param);

	header('Content-Type: text/html; charset=utf-8');
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/shipmodel_text_sep.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_shipmodel_text_sep($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>