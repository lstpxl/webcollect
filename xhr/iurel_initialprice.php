<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_iurel.php');


// =============================================================================
function outhtml_script_iurel_initialprice() {

$str = <<<SCRIPTSTRING

var iurel_initialprice_time_to_update = 0;
var iurel_initialprice_str = '';

function js_iurel_initialprice_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('iurel_initialprice_div');
		if (elem) {
			if (aresp['display'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['color'] == 'string') {
		var elem = document.getElementById('iurel_initialprice_input');
		if (elem) {
			if (aresp['color'] == 'red') aresp['color'] = 'ffd7d7';
			if (aresp['color'] == 'yellow') aresp['color'] = 'fff4ae';
			if (aresp['color'] == 'green') aresp['color'] = 'd6ffd5';
			if (aresp['color'] == 'purple') aresp['color'] = 'ffd6ff';
			if (aresp['color'] == 'mygray') aresp['color'] = 'd9e1e7';
			elem.style.backgroundColor = '#' + aresp['color'];
		}
	}
	
	if (typeof aresp['inputvalue'] == 'string') {
		var elem = document.getElementById('iurel_initialprice_input');
		if (elem) {
			elem.value = aresp['inputvalue'];
		}
	}
	
	return true;
}


function iurel_initialprice_test_sub() {

	var TimeNow = new Date().getTime();
	if (TimeNow < iurel_initialprice_time_to_update) return true;

	var elem = document.getElementById('iurel_initialprice_input');
	if (!elem) return false;
	var str = elem.value;

	if (iurel_initialprice_str != str) {
	
		iurel_initialprice_str = str;
	
		var elem = document.getElementById('form_iurel_item_id');
		if (!elem) return false;
		item_id = elem.value;
		
		var url = '/xhr/iurel_initialprice.php?i=' + item_id + '&c=save&str=' + str + '';
		return ajax_my_get_query(url);
	}
	
	return true;
}


function iurel_initialprice_test() {
	
	var elem = document.getElementById('iurel_initialprice_input');
	if (!elem) return false;
	var str = elem.value;
	
	if (iurel_initialprice_time_to_update < 0) {
		iurel_initialprice_time_to_update = 0;
		return true;
	}

	if (iurel_initialprice_str != str) {
		elem.style.backgroundColor = '#ffd7d7';
		
		var TimeNow = new Date().getTime();
		iurel_initialprice_time_to_update = TimeNow + 650;
		setTimeout('iurel_initialprice_test_sub()', 800);
	}
}



SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_iurel_initialprice_result($param) {

	$out = '';

	$param['i'] = ''.intval($param['i']);
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'initialprice');
	if ($curvalue == 0) {
		$str = '';
	} else {
		$str = my_currency_text($curvalue);
	}
	
	$color = '#d9e1e7'; // light blue
	
	$out .= '<div>';
		$out .= '<input class="hoverlightblueborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 50px; text-align: right; " size="9" name="iurel_initialprice_input" id="iurel_initialprice_input"  onchange="iurel_initialprice_test()" onkeydown="iurel_initialprice_test()" onkeyup="iurel_initialprice_test()" value="'.$str.'" />';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function process_iurel_initialprice_result(&$param) {
	
	$param['i'] = ''.intval($param['i']);
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'initialprice');

	if ($curvalue == 0) {
		$param['ajp']['inputvalue'] = '';
	} else {
		$param['ajp']['inputvalue'] = my_currency_text($curvalue);
	}

	return true;
}


// =============================================================================
function outhtml_iurel_initialprice_div($param) {

	$out = '';
	
	$out .= '<div id="iurel_initialprice_div">';
		$out .= outhtml_iurel_initialprice_result($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_iurel_initialprice(&$param) {

	if (!$GLOBALS['is_registered_user']) return false;

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) return false;
	if ($param['c'] != 'save') return false;
	
	if (!isset($param['str'])) return false;
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'initialprice');
	if ($curvalue === false) return false;
	
	$newvalue = my_parse_currency($param['str']);
	if ($newvalue === false) {
		$param['ajp']['color'] = 'purple';
	}
	
	$result = iurel_set_value($param['i'], $GLOBALS['user_id'], 'initialprice', $newvalue);
	update_iurel_searchstring($param['i'], $GLOBALS['user_id']);
	
	if ($result === false) {
		$param['ajp']['color'] = 'purple';
	}
	
	$param['ajp']['color'] = 'mygray';
	
	return true;
}


// =============================================================================
function jqfn_iurel_initialprice($param) {

	if (!$GLOBALS['is_registered_user']) return false;
	
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	$out = '';
	
	$param['ajp'] = array();
	
	$result = try_update_iurel_initialprice(&$param);

	header('Content-Type: text/html; charset=utf-8');

	if (!isset($param['reloadform'])) $param['reloadform'] = 'n';
	if (!isset($param['h'])) $param['h'] = '';
	
	if ($param['reloadform'] == 'y') {
		$param['ajp']['callback'] = 'js_form_iurel_callback';
		$param['ajp']['elemtoplace'] = 'form_iurel_div';
		$out .= ajax_encode_prefix($param['ajp']);
		$out .= outhtml_form_iurel_content(&$param);
	} else {
		if ($param['h'] == 'full') {
			$param['ajp']['callback'] = 'js_iurel_initialprice_callback';
			$param['ajp']['elemtoplace'] = 'iurel_initialprice_div';
			$out .= ajax_encode_prefix($param['ajp']);
			$out .= outhtml_iurel_initialprice_result(&$param);
		} else {
			$param['ajp']['callback'] = 'js_iurel_initialprice_callback';
			process_iurel_initialprice_result(&$param);
			$out .= ajax_encode_prefix($param['ajp']);
		}
	}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/iurel_initialprice.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_iurel_initialprice($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>