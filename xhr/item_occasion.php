<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_occasion() {

$str = <<<SCRIPTSTRING

function js_item_occasion_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}

	if (typeof aresp['color'] == 'string') {
		js_item_occasion_setcolor(aresp['color']);
	}
	
	return true;
}


function js_item_occasion_setcolor(color) {

	var elem = document.getElementById('item_occasion_select');
	if (!elem) return false;
	
	if (color == 'red') color = 'ffd7d7';
	if (color == 'yellow') color = 'fff4ae';
	if (color == 'green') color = 'd6ffd5';
	elem.style.backgroundColor = '#' + color;

	return true;
}


function js_item_occasion_query(item_id, occasion_id, c) {
	
	var url = '/xhr/item_occasion.php?i=' + item_id + '&occasion_id=' + occasion_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function js_item_occasion_change() {

	js_item_occasion_setcolor('red');

	var elem = document.getElementById('item_occasion_select');
	if (!elem) return false;
	
	var idx = elem.selectedIndex;
	var occasion_id = elem.options[idx].value;
	
	var elem = document.getElementById('edited_item_id');
	if (!elem) return false;
	var item_id = elem.value;

	js_item_occasion_query(item_id, occasion_id, 'save');
}


function item_occasion_refresh() {
	
	var url = '/xhr/item_occasion.php?i=' + item_id + '&p=full' + '';
	return ajax_my_get_query(url);
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function my_get_occasion_list() {

	$qr = mydb_queryarray("".
		" SELECT occasion.occasion_id, occasion.name ".
		" FROM occasion ".
		" ORDER BY (occasion.occasion_id > 0), occasion.name ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return [];
	}
	
	return $qr;
}


// =============================================================================
function outhtml_item_occasion_result($param) {

	$out = '';
	
	if (!am_i_admin_or_moderator()) return '';
	
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.occasion_id ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	//
	
	$list = my_get_occasion_list();
	
	//
	
	$out .= '<select class="hoverwhiteborder" name="occasion_select" id="item_occasion_select" style=" width: 180px; overflow: hidden; background-color: #d6ffd5; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 9pt; " onChange=" js_item_occasion_change(); return false; " >';
	for ($i = 0; $i < sizeof($list); $i++) {
		$ins = ($qr[0]['occasion_id'] == $list[$i]['occasion_id'])?' selected ':'';
		$out .= '<option '.$ins.' value="'.$list[$i]['occasion_id'].'">'.$list[$i]['name'].'</option>';
	}
	$out .= '</select>';
			
	//
	
	// 3f6b86
	/*
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_link.png\'); " onclick=" item_occasion_click(); return false; " title=" глобально изменить проект корабля ">';
	$out .= '</div>';
	*/

	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_occasion_div($param) {

	$out = '';
	
	$out .= '<div id="item_occasion_div">';
	
		$out .= outhtml_item_occasion_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_modify_item_occasion(&$param) {

	$param['i'] = ''.intval($param['i']);

	$param['ajp']['color'] = 'red';

	if (!am_i_admin_or_moderator()) return false;
	
	if ($param['c'] != 'save') return false;
	
	if (!isset($param['occasion_id'])) return false;
	if (!ctype_digit($param['occasion_id'])) return false;
	$param['occasion_id'] = ''.intval($param['occasion_id']);
	
	//
	
	if (my_get_occasion_str($param['occasion_id']) === false) return false;
	
	//

	$qr = mydb_query("".
		" UPDATE item SET ".
		" item.occasion_id  = '".$param['occasion_id']."' ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$param['ajp']['color'] = 'green';
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_item_occasion_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'item_occasion_div';
		$param['html'] = outhtml_item_occasion_result($param);
	}
	
	$prefixarr = array();

	return true;
}


// =============================================================================
function jqfn_item_occasion($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['ajp'] = array();
	// $param['ajp']['elemtoplace'] = 'item_occasion_div';
	$param['ajp']['callback'] = 'js_item_occasion_callback';

	// try_update_item_occasion($param);

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'save') {
		try_modify_item_occasion($param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_item_occasion_process($param);
	
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_occasion.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_occasion($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>