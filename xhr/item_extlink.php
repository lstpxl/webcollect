<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_extlink() {

$str = <<<SCRIPTSTRING

var item_extlink_str = '';
var TimeToUpdate = 0;

function js_item_extlink_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['color'] == 'string') {
		var elem = document.getElementById('item_extlink_input');
		if (elem) {
			if (aresp['color'] == 'red') aresp['color'] = 'ffd7d7';
			if (aresp['color'] == 'yellow') aresp['color'] = 'fff4ae';
			if (aresp['color'] == 'green') aresp['color'] = 'd6ffd5';
			elem.style.backgroundColor = '#' + aresp['color'];
		}
	}
	
	return true;
}

function js_item_extlink_query() {
	
	var elem = document.getElementById('item_extlink_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('item_extlink_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/item_extlink.php';
	var params = new Array();
	params['i'] = item_id;
	params['str'] = str;
	
	ajax_my_post_query(url, params);
}


function item_extlink_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('item_extlink_input').value;
	if (item_extlink_str != str) {
		item_extlink_str = str;
		js_item_extlink_query();
	}
}

function item_extlink_test() {
	
	var elem = document.getElementById('item_extlink_input');
	if (!elem) return false;
	var str = elem.value;

	if (item_extlink_str != str) {
		elem.style.backgroundColor = '#fff4ae';
		//
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('item_extlink_test_sub()', 800);
	}
}

function item_extlink_onfocus() {
	var elem = document.getElementById('item_extlink_input');
	if (!elem) return false;
	var str = elem.value;
	if (item_extlink_str != str) item_extlink_str = str;
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_extlink_input_div($param) {

	$out = '';
	
	if (!isset($param['i'])) return false;
	
	
	$param['i'] = ''.intval($param['i']);
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.extlink ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$str = $qr[0]['extlink'];
	$item_id = $param['i'];
	// $color = '#fff4ae'; // yellow
	$color = '#d6ffd5'; // green


	
	$out .= '<div id="item_extlink_input_div">';
	
	$out .= '<input type="hidden" name="item_extlink_item_id" id="item_extlink_item_id" value="'.$item_id.'" />';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input size="40" class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 552px; " name="item_extlink_input" id="item_extlink_input" onfocus="item_extlink_onfocus()"  onchange="item_extlink_test()" onkeydown="item_extlink_test()" onkeyup="item_extlink_test()" value="'.htmlspecialchars($str, ENT_QUOTES).'" /></div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_item_extlink_div($param) {

	$out = '';
	
	$out .= '<div id="item_extlink_div">';
	
	$out .= outhtml_item_extlink_input_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_extlink(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.extlink ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (!can_i_edit_item($param['i'])) return false;
	
	//
	
	$str = trim($param['str']);

	
	
	// ----
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.extlink = ? ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$a[] = $str;
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	
	
	update_item_searchstring($param['i']);
	
	$param['ajp']['color'] = 'green';
	
	if (is_valid_http_link($str)) {
		// ok
	}
	
	return true;
}


// =============================================================================
function jqfn_item_extlink_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'form_model_div';
		$param['html'] = outhtml_item_extlink_input_div($param);
	}
		
	return true;
}


// =============================================================================
function jqfn_item_extlink($param) {

	foreach ($param as &$value) {
		$value = rawurldecode($value);
	}
	
	if (!$GLOBALS['is_registered_user']) return false;
	
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	$out = '';
	
	$param['ajp'] = array();
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_item_extlink_callback';

	$result = try_update_item_extlink($param);

	header('Content-Type: text/html; charset=utf-8');

	jqfn_item_extlink_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_extlink.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_extlink($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>