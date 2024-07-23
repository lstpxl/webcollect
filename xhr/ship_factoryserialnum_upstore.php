<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_ship_factoryserialnum_upstore() {

$str = <<<SCRIPTSTRING

function js_ship_factoryserialnum_upstore_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('ship_factoryserialnum_upstore_div');
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
		var elem = document.getElementById('ship_factoryserialnum_input');
		if (elem) {
			if (aresp['input_color'] == 'red') aresp['input_color'] = 'ffd7d7';
			if (aresp['input_color'] == 'yellow') aresp['input_color'] = 'fff4ae';
			if (aresp['input_color'] == 'green') aresp['input_color'] = 'd6ffd5';
			elem.style.backgroundColor = '#' + aresp['input_color'];
		}
	}
	
	return true;
}


function js_ship_factoryserialnum_upstore_query(item_id, c) {
	
	var url = '/xhr/ship_factoryserialnum_upstore.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function ship_factoryserialnum_upstore_click() {
	
	var elem = document.getElementById('ship_factoryserialnum_upstore_item_id');
	if (elem) {
		var item_id = elem.value;
		js_ship_factoryserialnum_upstore_query(item_id, 'upstore');
	}
}


function ship_factoryserialnum_upstore_refresh() {
	
	var elem = document.getElementById('ship_factoryserialnum_upstore_item_id');
	if (elem) {
		var item_id = elem.value;
		js_ship_factoryserialnum_upstore_query(item_id, '');
	}
}

function ship_factoryserialnum_upstore_hide() {
	var elem = document.getElementById('ship_factoryserialnum_upstore_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function ship_factoryserialnum_upstore_show() {
	var elem = document.getElementById('ship_factoryserialnum_upstore_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_ship_factoryserialnum_upstore_result($param) {

	$out = '';
	
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_link.png\'); " onclick=" ship_factoryserialnum_upstore_click(); return false; " title="обновить данные о корабле">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_ship_factoryserialnum_upstore_process(&$param) {

	if (!am_i_admin_or_moderator()) return $out;
	
	//$out .= 'z1';

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'ship_factoryserialnum_upstore_div';
		$param['html'] = outhtml_ship_factoryserialnum_upstore_result($param);
	}
	
	//
	
	if (can_i_ship_factoryserialnum_upstore($param)) {
		$param['ajp']['show'] = 'show';
	} else {
		$param['ajp']['show'] = 'hide';
	}
	
	return true;
}


// =============================================================================
function outhtml_ship_factoryserialnum_upstore_div($param) {

	$out = '';
	
	$out .= outhtml_script_ship_factoryserialnum_upstore();
	
	$out .= '<input type="hidden" id="ship_factoryserialnum_upstore_item_id" value="'.$param['i'].'" />';
	
	if (can_i_ship_factoryserialnum_upstore($param)) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="ship_factoryserialnum_upstore_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_ship_factoryserialnum_upstore_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function can_i_ship_factoryserialnum_upstore($param) {

	$param['i'] = ''.intval($param['i']);
	
	if (!can_i_uplink_ship_factoryserialnum($param['i'])) return false;
	
	//
	
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id, item.ship_factoryserialnum_str ".
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
	
	$in_ship_str = my_get_ship_factoryserialnum($qr[0]['ship_id']);
	if ($in_ship_str == $qr[0]['ship_factoryserialnum_str']) return false;
	
	//
	
	return true;
}


// =============================================================================
function try_ship_factoryserialnum_upstore(&$param) {

	//$param['ajp']['zzz1'] = '1';

	if (!can_i_ship_factoryserialnum_upstore($param)) return false;
	
	//$param['ajp']['zzz2'] = '1';
	$param['i'] = ''.intval($param['i']);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id, item.ship_factoryserialnum_str ".
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
	
	// $param['ajp']['zzz3'] = '1';
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE ship ".
		" SET ship.factoryserialnum = ? ".
		" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ". 
		";";
	$a[] = $qr[0]['ship_factoryserialnum_str'];
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	
	my_elemtree_rebuild_treeindex_local('ship', $qr[0]['ship_id']);
	
	$param['ajp']['zzz4'] = '1';

	$param['ajp']['input_color'] = 'green';
	
	//
	
	update_item_searchstring($param['i']);
	
	//
	
	return true;
}


// =============================================================================
function jqfn_ship_factoryserialnum_upstore($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	// $param['ajp']['elemtoplace'] = 'ship_factoryserialnum_upstore_div';
	$param['ajp']['callback'] = 'js_ship_factoryserialnum_upstore_callback';
	$param['ajp']['show'] = 'show';

	// try_update_ship_factoryserialnum_upstore(&$param);

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'upstore') {
		try_ship_factoryserialnum_upstore(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_ship_factoryserialnum_upstore_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/ship_factoryserialnum_upstore.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_fsnu');
			jqfn_ship_factoryserialnum_upstore($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>