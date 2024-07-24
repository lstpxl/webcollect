<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_factoryserialnum_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_classification.php');


// =============================================================================
function outhtml_script_ship_autocomplete() {

$str = <<<SCRIPTSTRING

function js_ship_autocomplete_callback(aresp) {

	if (typeof aresp != 'object') return false;
	
	var elem = document.getElementById('ship_autocomplete_div');
	if (elem) {
		elem.style.backgroundColor = 'transparent';
	}

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}

	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('ship_autocomplete_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
				elem.innerHTML = '';
			}
		}
	}
	
	if (typeof aresp['show_upstore'] == 'string') {
		var elem = document.getElementById('ship_upstore_div');
		if (elem) {
			if (aresp['show_upstore'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_upstore'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	
	
	if (typeof aresp['color_input'] == 'string') {
		var elem = document.getElementById('ship_input');
		if (elem) {
			if (aresp['color_input'] == 'red') aresp['color_input'] = 'ffd7d7';
			if (aresp['color_input'] == 'yellow') aresp['color_input'] = 'fff4ae';
			if (aresp['color_input'] == 'green') aresp['color_input'] = 'd6ffd5';
			if (aresp['color_input'] == 'purple') aresp['color_input'] = 'ffd6ff';
			elem.style.backgroundColor = '#' + aresp['color_input'];
		}
	}
	
	return true;
}


function ship_autocomplete_clear() {
	var elem = document.getElementById('ship_autocomplete_div');
	if (elem) {
		elem.innerHTML = '';
		return true;
	}
	return false;
}


function ship_autocomplete_search(q, pn) {

	if (typeof pn === 'undefined') pn = '0';

	var elem = document.getElementById('ship_autocomplete_div');
	if (elem) {
		// elem.innerHTML = 'запрос...';
		elem.style.backgroundColor = '#ffa0a0';
	}


	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/ship_autocomplete.php?i=' + item_id + '&c=search' + '&pn=' + pn + '&q=' + q + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}


function ship_autocomplete_use(ship_id) {
	
	ship_id = '' + ship_id;
	
	if (!is_numeric(ship_id)) return false;

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = '' + elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/ship_autocomplete.php?i=' + item_id + '&c=select' + '&ship_id=' + ship_id + '';
			return ajax_my_get_query(url);
		}
	}
	
	ship_autocomplete_clear();
	
	return false;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function get_ship_sel_autocomplete_page_size() {
	return (8);
}


// =============================================================================
function outhtml_ship_sel_autocomplete_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_ship_sel_autocomplete_page_size();

	return outhtml_uni_paginator($pagesize, 4, 'ship_autocomplete_gotopn', $param['pn'], $total).PHP_EOL;
}


// =============================================================================
function outhtml_ship_autocomplete_content($param) {

	$out = '';
	
	if (!isset($param['q'])) return '';
	
	$qstr = trim($param['q']);
	if ($qstr == '') return '';
	
	$originalstr = $param['q'];
	
	$qstr = prep_string_for_search($qstr);
	
	if (mb_strlen($qstr) < 1) return '';
	$arr = explode(' ', $qstr, 8);
	if (sizeof($arr) < 1) return '';
	
	// prepared query
	$a = array();
	$a[] = $arr[0];
	$t = 's';
	$q = " SELECT ship.ship_id, ship.name, ship.factoryserialnum, ship.shipmodel_id, ship.shipmodelclass_id ".
		" FROM ship ".
		" WHERE ( LOCATE( ?, CONCAT(' ',ship.name) ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE( ?, CONCAT(' ',ship.name) ) > 0 ) ";
		$a[] = $arr[$i];
		$t .= 's';
	}
	$q .= " ORDER BY ship.factoryserialnum, ship.name ".
		" LIMIT 100 ".   
		"";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	$total = sizeof($qr);
	
	$out .= '<div style=" padding-top: 3px; ">';
	
	if (sizeof($qr) == 0) {
		$out .= '<div style=" display: block; float: left; color: #a08080; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; border: solid 1px #b0b0b0; ">';
		$out .= 'не найдено';
		$out .= '</div>';
	}
	
	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_ship_sel_autocomplete_page_size();
	$totalpages = ceil($total / $pagesize);
	if ($param['pn'] > $totalpages) $param['pn'] = $totalpages;
	$from = $param['pn'] * $pagesize;
	$to = $from + $pagesize;
	if ($to > $total) $to = $total;

	for ($i = $from; $i < $to; $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		// width: 180px;
		$out .= '<a href="#" id="ship_sel_id'.$qr[$i]['ship_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; " onclick="ship_autocomplete_use('.$qr[$i]['ship_id'].'); return false;"><nobr>';
		$s = ''.$qr[$i]['name'];
		if (($qr[$i]['factoryserialnum'] != '') && ($qr[$i]['factoryserialnum'] != '0')) {
			$s .= ' (зав.ном. '.$qr[$i]['factoryserialnum'].')';
		}
		if ($qr[$i]['shipmodel_id'] > 0) {
			$s .= ' ('.my_get_shipmodel_name_alldetail($qr[$i]['shipmodel_id']).') ';
		} else {
			$s .= ' (проект не указан) ';
		}
		if ($qr[$i]['shipmodelclass_id'] > 0) {
			$s .= ' '.my_get_shipclass_name($qr[$i]['shipmodelclass_id']).' ';
		} else {
			$s .= ' ';
		}
		//$s .= ') ';
		$out .= $s;
		$out .= '</nobr></a>';
	}
	
	$out .= outhtml_ship_sel_autocomplete_paginator($param, $total);

	$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_ship_autocomplete_div($param) {

	$out = '';
	
	$out .= '<input type="hidden" id="ship_upstore_item_id" value="'.$param['i'].'" />';
	
	// border: solid 1px red;
	$out .= '<div id="ship_autocomplete_div" style="  ">';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_ship_autocomplete_check_exist(&$param) {

	//$param['ajp']['check_exist'] = '1';

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	if ($param['q'] == '') {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	
	//$param['ajp']['check_exist'] = '2';
	
	//
	
	$str = trim($param['q']);
	
	//$param['ajp']['check_exist'] = '3';
	
	if ($str == '') {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	//$param['ajp']['check_exist'] = '4';
	
	// --
	
	$a = array();
	$q = " SELECT ship.ship_id ".
		" FROM ship ".
		" WHERE ( ship.name = ? ) ".
		";";
	$a[] = $str;
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
		
	//$param['ajp']['check_exist'] = '5'.$str;
	
	if (sizeof($qres) == 0) {
		$param['ajp']['show_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	//$param['ajp']['check_exist'] = '6';
	
	if (sizeof($qres) > 0) {
		$param['ajp']['show_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'purple';
		return true;
	}
	
	
	//$param['ajp']['check_exist'] = '7'.$str;
	
	return true;
}


// =============================================================================
function jqfn_ship_autocomplete_update(&$param) {

	if (!isset($param['q'])) return false;

	if (!can_i_edit_item($param['i'])) return false;
	
	$param['i'] = ''.intval($param['i']);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id, item.ship_str ".
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
	
	$str = trim($param['q']);
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.ship_id = '0',  ".
		" item.ship_str = ? ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$a[] = $str;
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$param['ajp']['color_input'] = 'yellow';
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_ship_autocomplete_search(&$param) {

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	$param['ajp']['elemtoplace'] = 'ship_autocomplete_div';
	$param['html'] = '';
	
	jqfn_ship_autocomplete_update($param);
	
	if ($param['q'] == '') {
		return true;
	}

	
	$param['html'] .= outhtml_ship_autocomplete_content($param);
	
	$param['ajp']['show'] = 'show';
	
	jqfn_ship_autocomplete_check_exist($param);
	
	return true;
}


// =============================================================================
function jqfn_ship_autocomplete_select(&$param) {
	
	if (!isset($param['ship_id'])) return false;
	if (!ctype_digit($param['ship_id'])) return false;
	$param['ship_id'] = ''.intval($param['ship_id']);
	if (my_get_ship_name($param['ship_id']) === false) return false;
	
	// apply ship data here
	
	// from ship
	
	$q = " SELECT ship.ship_id, ".
		" ship.name, ship.has_model, ship.factoryserialnum, ship.shipmodel_id, ".
		" ship.shipmodelclass_id, ship.shipyard_id ".
		" FROM ship ".
		" WHERE ship.ship_id = '".$param['ship_id']."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ($qr[0]['shipmodel_id'] > 0) {
		$shipmodel_str = my_get_shipmodel_name($qr[0]['shipmodel_id']);
		
		$natoc_id = my_get_shipmodel_natoc_id($qr[0]['shipmodel_id']);
		
		if ($natoc_id > 0) {
			$natoc_str = my_get_natoc_str($natoc_id);
		} else {
			$natoc_str = '';
		}
	} else {
		$shipmodel_str = '';
		$natoc_id = 0;
		$natoc_str = '';
	}
	
	if ($qr[0]['shipyard_id'] > 0) {
		$shipyard_str = my_get_shipyard_name($qr[0]['shipyard_id']);
	} else {
		$shipyard_str = '';
	}
	
	
	// prepared query
	$a = array();
	$a[] = $param['ship_id'];
	$a[] = $qr[0]['name'];
	$a[] = $qr[0]['factoryserialnum'];
	$a[] = $qr[0]['shipmodel_id'];
	$a[] = $shipmodel_str;
	
	$a[] = $qr[0]['shipmodelclass_id'];
	$a[] = my_get_shipclass_name($qr[0]['shipmodelclass_id']);
	
	$a[] = $natoc_id;
	$a[] = $natoc_str;
	
	$a[] = $qr[0]['shipyard_id'];
	$a[] = $shipyard_str;
	
	$a[] = $param['i'];
	$t = 'issisisisisi';
	$q = "".
		" UPDATE item SET ".
		" item.ship_id = ?, ".
		" item.ship_str = ?, ".
		" item.ship_factoryserialnum_str = ?, ".
		" item.shipmodel_id = ?, ".
		" item.shipmodel_str = ?, ".
		" item.shipmodelclass_id = ?, ".
		" item.shipmodelclass_str = ?, ".
		" item.natoc_id = ?, ".
		" item.natoc_str = ?, ".
		" item.shipyard_id = ?, ".
		" item.shipyard_str = ? ".
		
		" WHERE item.item_id = ? ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	
	// from shipmodel
	
	if ($qr[0]['has_model'] == 'Y') {
		
		$shipmodelclass_id = '0';
		$shipmodelclass_str = '';
		
		if ($qr[0]['shipmodel_id'] > 0) {
			$shipmodelclass_id = my_get_shipmodel_class($qr[0]['shipmodel_id']);
			if ($shipmodelclass_id > 0) {
				$shipmodelclass_str = my_get_shipclass_name($shipmodelclass_id);
			}
		}
		
		// prepared query
		$a = array();
		$a[] = $shipmodelclass_id;
		$a[] = $shipmodelclass_str;
		$a[] = $param['i'];
		$t = 'isi';
		$q = "".
			" UPDATE item SET ".
			" item.shipmodelclass_id = ?, ".
			" item.shipmodelclass_str = ? ".
			" WHERE item.item_id = ? ". 
			";";
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
	
	}
	

	update_item_searchstring($param['i']);
	
	//
	
	// return complete classification block
	$param['ajp']['elemtoplace'] = 'item_classification_div';
	$param['html'] = outhtml_item_classification_content(array('i' => $param['i']));
	
	return true;
}


// =============================================================================
function jqfn_ship_autocomplete($param) {

	if (!am_i_registered_user()) return false;
	
	//print 'z2';

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_ship_autocomplete_callback';
	// $param['ajp']['show_upstore'] = 'no';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	//print 'z3';
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'search') {
		jqfn_ship_autocomplete_search($param);
	}
	if ($param['c'] == 'select') {
		jqfn_ship_autocomplete_select($param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	// my_ship_autocomplete_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/ship_autocomplete.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_ship_autocomplete($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>