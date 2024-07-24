<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_iurel.php');


// =============================================================================
function outhtml_script_iurel_sellit() {

$str = <<<SCRIPTSTRING


function js_iurel_sellit_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('iurel_sellit_div');
		if (elem) {
			if (aresp['display'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	return true;
}


function iurel_sellit_switch(item_id, reload_form) {
	if (typeof reload_form === 'undefined') reload_form = false;
	var url = '/xhr/iurel_sellit.php?i=' + item_id + '&c=switch';
	if (reload_form) url = url + '&reloadform=y';
	return ajax_my_get_query(url);
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_iurel_sellit_result($param) {

	$out = '';

	$param['i'] = ''.intval($param['i']);
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'sellit');
	
	$out .= '<div>';
		$position = ($curvalue == 'Y');
		$text = ($position?'ДА':'НЕТ');
		$color = ($position?'82e9ff':'c8c8c8');
		$onclickstr = ' iurel_sellit_switch('.$param['i'].', true); return false; ';
		$out .= outhtml_switch_i1($position, $text, $color, $onclickstr);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_iurel_sellit_div($param) {

	$out = '';
	
	$out .= '<div id="iurel_sellit_div">';
		$out .= outhtml_iurel_sellit_result($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_iurel_sellit(&$param) {

	if (!$GLOBALS['is_registered_user']) return false;

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) return false;
	if ($param['c'] != 'switch') return false;
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'sellit');
	if ($curvalue === false) return false;
	
	$newvalue = (($curvalue == 'Y')?'N':'Y');
	$result = iurel_set_value($param['i'], $GLOBALS['user_id'], 'sellit', $newvalue);
	update_iurel_searchstring($param['i'], $GLOBALS['user_id']);
	
	return true;
}


// =============================================================================
function jqfn_iurel_sellit($param) {

	if (!$GLOBALS['is_registered_user']) return false;
	
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	$out = '';
	
	$param['ajp'] = array();
	

	$result = try_update_iurel_sellit($param);

	header('Content-Type: text/html; charset=utf-8');

	
	
	if (!isset($param['reloadform'])) $param['reloadform'] = 'n';
	if ($param['reloadform'] == 'y') {
		$param['ajp']['callback'] = 'js_form_iurel_callback';
		$param['ajp']['elemtoplace'] = 'form_iurel_div';
		$out .= ajax_encode_prefix($param['ajp']);
		$out .= outhtml_form_iurel_content($param);
	} else {
		$param['ajp']['callback'] = 'js_iurel_sellit_callback';
		$param['ajp']['elemtoplace'] = 'iurel_sellit_div';
		$out .= ajax_encode_prefix($param['ajp']);
		$out .= outhtml_iurel_sellit_result($param);
	}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/iurel_sellit.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_iurel_sellit($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>