<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_classification.php');


// =============================================================================
function outhtml_script_ship_shipyard_autocomplete() {

$str = <<<SCRIPTSTRING

function js_ship_shipyard_autocomplete_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
		if (aresp['elemtoplace'] == 'ship_shipyard_autocomplete_div') {
			var elem2 = document.getElementById('model_autocomplete_div');
			if (elem2) {
				elem2.innerHTML = '';
			}
		}
	}

	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('ship_shipyard_autocomplete_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['show_upstore'] == 'string') {
		var elem = document.getElementById('ship_shipyard_upstore_div');
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
	
	if (typeof aresp['show_upstore'] == 'string') {
		var elem = document.getElementById('ship_shipyard_upstore_div');
		if (elem) {
			if (aresp['show_upstore'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_upstore'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	
	if (typeof aresp['show_uplink'] == 'string') {
		var elem = document.getElementById('ship_shipyard_uplink_div');
		if (elem) {
			if (aresp['show_uplink'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_uplink'] == 'no') {
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
			if (aresp['input_color'] == 'purple') aresp['input_color'] = 'ffd6ff';
			elem.style.backgroundColor = '#' + aresp['input_color'];
		}
	}
	
	return true;
}


function ship_shipyard_autocomplete_clear() {
	var elem = document.getElementById('ship_shipyard_autocomplete_div');
	if (elem) {
		elem.innerHTML = '';
		return true;
	}
	return false;
}


function ship_shipyard_autocomplete_search(q, pn) {

	if (typeof pn === 'undefined') pn = '0';

	var elem = document.getElementById('ship_shipyard_autocomplete_div');
	if (elem) {
		// elem.innerHTML = 'запрос...';
	}

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
		
			
		
			var url = '/xhr/ship_shipyard_autocomplete.php?i=' + item_id + '&c=search' + '&pn=' + pn + '&q=' + q + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}

function ship_shipyard_autocomplete_gotopn(pn) {

	if (typeof shipship_shipyard_sel_str != 'string') return false;

	ship_shipyard_autocomplete_search(shipship_shipyard_sel_str, pn);
}


function ship_shipyard_autocomplete_use(shipyard_id) {
	
	shipyard_id = '' + shipyard_id;
	
	if (!is_numeric(shipyard_id)) return false;
	

	var elem = document.getElementById('ship_shipyard_autocomplete_div');
	if (elem) {
		elem.innerHTML = '';
	}


	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = '' + elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/ship_shipyard_autocomplete.php?i=' + item_id + '&c=select' + '&shipyard_id=' + shipyard_id + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function get_ship_shipyard_sel_autocomplete_page_size() {
	return (10);
}


// =============================================================================
function outhtml_ship_shipyard_sel_autocomplete_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_ship_shipyard_sel_autocomplete_page_size();

	return outhtml_uni_paginator($pagesize, 4, 'ship_shipyard_autocomplete_gotopn', $param['pn'], $total).PHP_EOL;
}


// =============================================================================
function outhtml_ship_shipyard_autocomplete_content($param) {

	$out = '';
	
	$qstr = $param['q'];
	
	if (!isset($qstr)) $qstr = '';
	
	$originalstr = $qstr;

	
	$qstr = my_simplify_text_string($qstr);
	$qstr = my_simplify_text_string($qstr);
	//$out .= $qstr;
	
	
	if (mb_strlen($qstr) < 1) return '';
	$arr = explode(' ', $qstr, 8);
	if (sizeof($arr) < 1) return '';
	
	
	
	// prepared query
	$a = array();
	$a[] = $arr[0];
	$t = 's';
	$q = " SELECT shipyard.shipyard_id ".
		" FROM shipyard ".
		" WHERE ( LOCATE( ?, CONCAT(' ', LOWER(shipyard.name)) ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE( ?, CONCAT(' ', LOWER(shipyard.name)) ) > 0 ) ";
		$a[] = $arr[$i];
		$t .= 's';
	}
	$q .= " ORDER BY shipyard.name ".
		" LIMIT 100 ".   
		"";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	
	
	$out .= '<div style=" padding-top: 3px; ">';
	
	if (sizeof($qr) == 0) {
		$out .= '<div style=" display: block; float: left; color: #a08080; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; border: solid 1px #b0b0b0; ">';
		$out .= 'не найдено';
		$out .= '</div>';
	}
	
	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$total = sizeof($qr);
	
	$pagesize = get_ship_shipyard_sel_autocomplete_page_size();
	$totalpages = ceil($total / $pagesize);
	if ($param['pn'] > $totalpages) $param['pn'] = $totalpages;
	$from = $param['pn'] * $pagesize;
	$to = $from + $pagesize;
	if ($to > $total) $to = $total;

	for ($i = $from; $i < $to; $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		// width: 180px;
		$out .= '<a href="#" id="ship_shipyard_sel_id'.$qr[$i]['shipyard_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; " onclick="ship_shipyard_autocomplete_use('.$qr[$i]['shipyard_id'].'); return false;"><nobr>';
		
		$s = my_get_shipyard_name($qr[$i]['shipyard_id']);
		
		$out .= $s;
		$out .= '</nobr></a>';
	}
	
	$out .= outhtml_ship_shipyard_sel_autocomplete_paginator($param, $total);

	$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_ship_shipyard_autocomplete_div($param) {

	$out = '';
	
	// border: solid 1px red;
	$out .= '<div id="ship_shipyard_autocomplete_div" style="  ">';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_ship_shipyard_autocomplete_check_exist(&$param) {

	$param['ajp']['check_exist'] = '1';

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	$param['ajp']['input_color'] = 'yellow';
	
	/*
	if ($param['q'] == '') {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['input_color'] = 'yellow';
		return true;
	}
	*/
	
	$param['ajp']['check_exist'] = '2';
	
	//
	
	$originalstr = $param['q'];
	$str = $param['q'];
	
	$str = my_clean_string_ac($str);
	$str = my_clean_string_ac($str);
	
	$param['ajp']['check_exist'] = '3';
	
	if ($str == '') {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['input_color'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '4';
	
	
	// prepared query
	$a = array();
	$a[] = $str;
	$q = "".
		"SELECT shipyard.shipyard_id, shipyard.name ".
		"FROM shipyard ".
		"WHERE ( shipyard.name = ? ) ".
		";";
	$t = 's';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	$param['ajp']['check_exist'] = '5'.$str;
	
	if (sizeof($qr) == 0) {
		$param['ajp']['show_upstore'] = 'yes';
		$param['ajp']['input_color'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '6';
	
	if (sizeof($qr) > 1) {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['input_color'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '7'.$str;
	
	if ($qr[0]['name'] == $originalstr) {
		$param['ajp']['show_upstore'] = 'yes';
		$param['ajp']['input_color'] = 'purple';
		return true;
	}
	
	$param['ajp']['check_exist'] = '8'.$str;
	
	return true;
}


// =============================================================================
function jqfn_ship_shipyard_autocomplete_update(&$param) {

	if (!isset($param['q'])) return false;

	if (!can_i_edit_item($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipyard_str, item.shipyard_id ".
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
	
	//print 'line1';
	
	$str = trim($param['q']);
	
	//----
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.shipyard_id = '0',  ".
		" item.shipyard_str = ? ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$a[] = $param['q'];
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//---
	
	$param['ajp']['input_color'] = 'yellow';
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_ship_shipyard_autocomplete_search(&$param) {

	$param['i'] = ''.intval($param['i']);

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	$param['ajp']['elemtoplace'] = 'ship_shipyard_autocomplete_div';
	$param['html'] = '';
	
	jqfn_ship_shipyard_autocomplete_update($param);
	
	if ($param['q'] == '') {
		return true;
	}
	
	if (can_i_uplink_ship_factoryserialnum($param['i'])) {
		$param['ajp']['show_upstore'] = 'yes';
	}
	
	if (can_i_uplink_ship_factoryserialnum($param['i'])) {
		$param['ajp']['show_uplink'] = 'yes';
	}
	
	$param['html'] .= outhtml_ship_shipyard_autocomplete_content($param);
	
	jqfn_ship_shipyard_autocomplete_check_exist($param);
	
	return true;
}


// =============================================================================
function jqfn_ship_shipyard_autocomplete_select(&$param) {
	
	if (!isset($param['shipyard_id'])) return false;
	if (!ctype_digit($param['shipyard_id'])) return false;
	$param['shipyard_id'] = ''.intval($param['shipyard_id']);
	if ($param['shipyard_id'] < 1) return false;
	
	$str = my_get_shipyard_name($param['shipyard_id']);
	
	//print 'z1';
	
	if ($str === false) return false;
	
	//print 'z2';
	
	// prepared query
	$a = array();
	$a[] = $param['shipyard_id'];
	$a[] = $str;
	$a[] = $param['i'];
	$q = "".
		" UPDATE item SET ".
		" item.shipyard_id = ?, ".
		" item.shipyard_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$t = 'isi';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	//print 'z3';
	
	update_item_searchstring($param['i']);
	
	$param['ajp']['elemtoplace'] = 'ship_shipyard_autocomplete_div';
	//$param['html'] = outhtml_form_model_content(array('i' => $param['i']));
	
	if (can_i_ship_shipyard_upstore($param['i'])) {
		$param['ajp']['show_upstore'] = 'yes';
	} else {
		$param['ajp']['show_upstore'] = 'no';
	}
	
	if (can_i_ship_shipyard_uplink($param['i'])) {
		$param['ajp']['show_uplink'] = 'yes';
	} else {
		$param['ajp']['show_uplink'] = 'no';
	}
	
	$param['ajp']['input_color'] = 'green';
	
	// $param['html'] .= outhtml_ship_shipyard_autocomplete_content($param);
	$param['ajp']['elemtoplace'] = 'ship_shipyard_input_div';
	$param['html'] = outhtml_ship_shipyard_input_content(array('i' => $param['i']));
	
	return true;
}


// =============================================================================
function jqfn_ship_shipyard_autocomplete($param) {

	if (!am_i_registered_user()) return false;
	
	//print 'z2';

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_ship_shipyard_autocomplete_callback';
	// $param['ajp']['show_upstore'] = 'yes';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	//print 'z3';
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'search') {
		jqfn_ship_shipyard_autocomplete_search($param);
	}
	if ($param['c'] == 'select') {
		jqfn_ship_shipyard_autocomplete_select($param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	// my_ship_shipyard_autocomplete_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/ship_shipyard_autocomplete.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_ship_shipyard_autocomplete($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>