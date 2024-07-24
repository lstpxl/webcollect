<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_ship_shipyard_uplink() {

$str = <<<SCRIPTSTRING

function js_ship_shipyard_uplink_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('ship_shipyard_uplink_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	if (typeof aresp['input_color'] == 'string') {
		var elem = document.getElementById('ship_shipyard_input');
		if (elem) {
			if (aresp['input_color'] == 'red') aresp['input_color'] = 'ffd7d7';
			if (aresp['input_color'] == 'yellow') aresp['input_color'] = 'fff4ae';
			if (aresp['input_color'] == 'green') aresp['input_color'] = 'd6ffd5';
			elem.style.backgroundColor = '#' + aresp['input_color'];
		}
	}
	
	return true;
}


function js_ship_shipyard_uplink_query(item_id, c) {
	
	var url = '/xhr/ship_shipyard_uplink.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function ship_shipyard_uplink_click() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_ship_shipyard_uplink_query(item_id, 'uplink');
	}
}


function ship_shipyard_uplink_refresh() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_ship_shipyard_uplink_query(item_id, '');
	}
}

function ship_shipyard_uplink_hide() {
	var elem = document.getElementById('ship_shipyard_uplink_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function ship_shipyard_uplink_show() {
	var elem = document.getElementById('ship_shipyard_uplink_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_ship_shipyard_uplink_result($param) {

	$out = '';
	
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_link.png\'); " onclick=" ship_shipyard_uplink_click(); return false; " title=" глобально изменить данные о корабле ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_ship_shipyard_uplink_process(&$param) {

	if (!am_i_admin_or_moderator()) return '';
	
	//$out .= 'z1';

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'ship_shipyard_uplink_div';
		$param['html'] = outhtml_ship_shipyard_uplink_result($param);
	}
	
	//
	
	if (can_i_ship_shipyard_uplink($param['i'])) {
		$param['ajp']['show'] = 'show';
	} else {
		$param['ajp']['show'] = 'hide';
	}
	
	return true;
}


// =============================================================================
function outhtml_ship_shipyard_uplink_div($param) {

	$out = '';
	
	// $out .= '<input type="hidden" id="ship_shipyard_uplink_item_id" value="'.$param['i'].'" />';
	
	if (can_i_ship_shipyard_uplink($param['i'])) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="ship_shipyard_uplink_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_ship_shipyard_uplink_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_beautify_ship_shipyard_uplink_str($str) {

	$str = mb_ereg_replace('[^а-яa-z1-90]', ' ', $str);

	$str = mb_str_replace($str, '    ', ' ');
	$str = mb_str_replace($str, '  ', ' ');
	$str = mb_str_replace($str, '"', '«');
	$str = mb_str_replace($str, "'", '«');
	$str = mb_str_replace($str, '\'', '«');
	
	return $str;
}

// =============================================================================
function try_ship_shipyard_uplink(&$param) {

	//print 'a';

	// if (!am_i_admin_or_moderator()) return false;
	
	if (!can_i_ship_shipyard_uplink($param['i'])) return false;
	
	// print 'b';
	
	$param['i'] = ''.intval($param['i']);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipyard_id, item.ship_id ".
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
	
	//
	
	if ($qr[0]['ship_id'] < 1) return false;
	
	//
	
	$qr2 = mydb_queryarray("".
		" SELECT ship.ship_id, ship.shipyard_id ".
		" FROM ship ".
		" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ".
		"");
	if ($qr2 === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr2) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	if ($qr2[0]['shipyard_id'] == $qr[0]['shipyard_id']) return true;
	
	print 'k';
	

	$q = "".
		" UPDATE ship ".
		" SET ship.shipyard_id = '".$qr[0]['shipyard_id']."' ".
		" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ". 
		";";
	$qres = mydb_query($q);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$param['ajp']['input_color'] = 'green';
	
	//
	
	update_item_searchstring($param['i']);
	
	//
	
	return true;
}


// =============================================================================
function jqfn_ship_shipyard_uplink($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	// $param['ajp']['elemtoplace'] = 'ship_shipyard_uplink_div';
	$param['ajp']['callback'] = 'js_ship_shipyard_uplink_callback';
	$param['ajp']['show'] = 'show';

	// try_update_ship_shipyard_uplink($param);

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'uplink') {
		try_ship_shipyard_uplink($param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_ship_shipyard_uplink_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/ship_shipyard_uplink.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_fsnu');
			jqfn_ship_shipyard_uplink($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>