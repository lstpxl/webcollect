<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');


// =============================================================================
function outhtml_script_ship_upstore() {

$str = <<<SCRIPTSTRING

function js_ship_upstore_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('ship_upstore_div');
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
		var elem = document.getElementById('ship_input');
		if (elem) {
			if (aresp['input_color'] == 'red') aresp['input_color'] = 'ffd7d7';
			if (aresp['input_color'] == 'yellow') aresp['input_color'] = 'fff4ae';
			if (aresp['input_color'] == 'green') aresp['input_color'] = 'd6ffd5';
			elem.style.backgroundColor = '#' + aresp['input_color'];
		}
	}
	
	if (typeof aresp['shipfactorynum_color'] == 'string') {
		var elem = document.getElementById('ship_factoryserialnum_input');
		if (elem) {
			if (aresp['shipfactorynum_color'] == 'red') aresp['shipfactorynum_color'] = 'ffd7d7';
			if (aresp['shipfactorynum_color'] == 'yellow') aresp['shipfactorynum_color'] = 'fff4ae';
			if (aresp['shipfactorynum_color'] == 'green') aresp['shipfactorynum_color'] = 'd6ffd5';
			elem.style.backgroundColor = '#' + aresp['shipfactorynum_color'];
		}
	}
	
	
	
	
	if (typeof aresp['show_autocomplete'] == 'string') {
		var elem = document.getElementById('ship_autocomplete_div');
		if (elem) {
			if (aresp['show_autocomplete'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_autocomplete'] == 'no') {
				elem.style.visibility = 'hidden';
				elem.innerHTML = '';
			}
		}
	}

	return true;
}


function js_ship_upstore_query(item_id, c) {
	
	var url = '/xhr/ship_upstore.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function ship_upstore_click() {
	
	var elem = document.getElementById('ship_upstore_item_id');
	if (elem) {
		var item_id = elem.value;
		js_ship_upstore_query(item_id, 'upstore');
	}
}


function ship_upstore_refresh() {
	
	var elem = document.getElementById('ship_upstore_item_id');
	if (elem) {
		var item_id = elem.value;
		js_ship_upstore_query(item_id, '');
	}
}

function ship_upstore_hide() {
	var elem = document.getElementById('ship_upstore_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function ship_upstore_show() {
	var elem = document.getElementById('ship_upstore_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_ship_upstore_content($param) {

	$out = '';
	
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_add.png\'); " onclick=" ship_upstore_click(); return false; " title=" добавить корабль в базу ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_ship_upstore_process(&$param) {

	if (!am_i_admin_or_moderator()) return $out;
	
	//$out .= 'z1';

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'ship_upstore_div';
		$param['html'] = outhtml_ship_upstore_content($param);
	}
	
	// $param['ajp']['color'] = 'yellow';
	$param['ajp']['show'] = 'hide';
	
	//
	
	//
	
	if (can_i_ship_upstore($param)) {
	
		$param['ajp']['show'] = 'show';
	
	}
		
	return true;
}


// =============================================================================
function outhtml_ship_upstore_div($param) {

	$out = '';
	
	$out .= outhtml_script_ship_upstore();
	
	$out .= '<input type="hidden" id="ship_upstore_item_id" value="'.$param['i'].'" />';
	
	if (can_i_ship_upstore($param)) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="ship_upstore_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_ship_upstore_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function can_i_ship_upstore($param) {

	//print 'z1';
	
	$param['i'] = ''.intval($param['i']);

	if (!can_i_upstore_ship($param['i'])) return false;
	
	//print 'z2';
	

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//print 'z3';
	
	//
	
	if ($qr[0]['ship_str'] == '') return false;
	
	if ($qr[0]['ship_id'] > 0) {
		$stored_ship_name = my_get_ship_name($qr[0]['ship_id']);
		if ($stored_ship_name != $qr[0]['ship_str']) return true;
	} else {
		return true;
	}

	//
	
	return false;
}


// =============================================================================
function try_ship_upstore(&$param) {

	if (!can_i_ship_upstore($param)) return false;

	//
	$param['i'] = ''.intval($param['i']);
	
	//$param['ajp']['step1'] = '1';
	
	$qr = mydb_queryarray("".
		" SELECT ".
		" item.ship_id, item.ship_str, ".
		" item.shipmodelclass_id, item.shipmodelclass_str, ".
		" item.shipmodel_id, item.shipmodel_str, ".
		" item.ship_factoryserialnum_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//$param['ajp']['step2'] = '1';
	
	//
	
	// prepared query
	$a = array();
	$a[] = $qr[0]['ship_str'];
	$a[] = $qr[0]['ship_factoryserialnum_str'];
	$a[] = $qr[0]['shipmodel_id'];
	$t = 'ssi';
	$q = "".
		" INSERT INTO ship SET ".
		" ship.refresh = 'Y', ".
		" ship.name = ?, ".
		" ship.factoryserialnum = ?, ".
		" ship.shipmodel_id = ? ".
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$new_id = mydb_insert_id();
	if (!($new_id > 0)) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	my_elemtree_rebuild_treeindex_local('ship', $new_id);
	
	//$param['ajp']['step3'] = '1';
	//
	
	if ($new_id < 1) return false;
	
	$q = "".
		" UPDATE item ".
		" SET item.ship_id = '".$new_id."' ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$qru = mydb_query($q);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//$param['ajp']['step4'] = '1';
	
	//
	
	$param['ajp']['input_color'] = 'green';
	$param['ajp']['shipfactorynum_color'] = 'green';
	$param['ajp']['hide_upstore'] = 'yes';
	$param['ajp']['show_autocomplete'] = 'no';
	
	update_item_searchstring($param['i']);
	
	//$param['ajp']['step5'] = '1';
	
	return true;
}


// =============================================================================
function jqfn_ship_upstore($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_ship_upstore_callback';
	$param['ajp']['show'] = 'show';

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'upstore') {
		try_ship_upstore(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_ship_upstore_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/ship_upstore.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_ship_upstore($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>