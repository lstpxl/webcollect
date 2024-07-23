<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_factoryserialnum_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_classification.php');


// =============================================================================
function outhtml_script_model_autocomplete() {

$str = <<<SCRIPTSTRING

function js_model_autocomplete_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
		if (aresp['elemtoplace'] == 'model_autocomplete_div') {
			var elem2 = document.getElementById('model_natoc_autocomplete_div');
			if (elem2) {
				elem2.innerHTML = '';
			}
		}
	}

	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById(aresp['model_autocomplete_div']);
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['hide_upstore'] == 'string') {
		var elem = document.getElementById('shipmodel_upstore_div');
		if (elem) {
			if (aresp['hide_upstore'] == 'no') {
				elem.style.visibility = 'visible';
			}
			if (aresp['hide_upstore'] == 'yes') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	
	
	if (typeof aresp['color_input'] == 'string') {
		var elem = document.getElementById('shipmodel_input');
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


function model_autocomplete_clear() {
	var elem = document.getElementById('model_autocomplete_div');
	if (elem) {
		elem.innerHTML = '';
		return true;
	}
	return false;
}


function model_autocomplete_search(q, pn) {

	if (typeof pn === 'undefined') pn = '0';

	var elem = document.getElementById('model_autocomplete_div');
	if (elem) {
		// elem.innerHTML = 'запрос...';
	}

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
		
			
		
			var url = '/xhr/model_autocomplete.php?i=' + item_id + '&c=search' + '&pn=' + pn + '&q=' + q + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}

function model_autocomplete_gotopn(pn) {

	if (typeof shipmodel_sel_str != 'string') return false;

	model_autocomplete_search(shipmodel_sel_str, pn);
}


function model_autocomplete_use(model_id) {
	
	model_id = '' + model_id;
	
	if (!is_numeric(model_id)) return false;

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = '' + elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/model_autocomplete.php?i=' + item_id + '&c=select' + '&model_id=' + model_id + '';
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
function get_model_sel_autocomplete_page_size() {
	return (12);
}


// =============================================================================
function outhtml_model_sel_autocomplete_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_model_sel_autocomplete_page_size();

	return outhtml_uni_paginator($pagesize, 4, 'model_autocomplete_gotopn', $param['pn'], $total).PHP_EOL;
}


// =============================================================================
function outhtml_model_autocomplete_content($param) {

	$out = '';
	
	if (!isset($param['q'])) return '';
	
	$qstr = trim($param['q']);
	if ($qstr == '') return '';
	
	$originalstr = $param['q'];
	
	$qstr = prep_string_for_search($qstr);
	$qstr = eyo_str($qstr);
	
	if (mb_strlen($qstr) < 1) return '';
	$arr = explode(' ', $qstr, 8);
	if (sizeof($arr) < 1) return '';
	
	// print_r($arr);
	
	// $qstr = eyo_str($qstr);
	// REPLACE(LOWER(shipmodel.name), 'ё', 'е')
	
	// prepared query
	$a = array();
	$a[] = $arr[0];
	$t = 's';
	$q = " SELECT shipmodel.shipmodel_id, shipmodel.shipmodelclass_id ".
		" FROM shipmodel ".
		" WHERE ( LOCATE( ?, CONCAT(' ', REPLACE(LOWER(shipmodel.name), 'ё', 'е')) ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE( ?, CONCAT(' ', REPLACE(LOWER(shipmodel.name), 'ё', 'е')) ) > 0 ) ";
		$a[] = $arr[$i];
		$t .= 's';
	}
	$q .= " ORDER BY shipmodel.numcode, shipmodel.nick ".
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
	
	$pagesize = get_model_sel_autocomplete_page_size();
	$totalpages = ceil($total / $pagesize);
	if ($param['pn'] > $totalpages) $param['pn'] = $totalpages;
	$from = $param['pn'] * $pagesize;
	$to = $from + $pagesize;
	if ($to > $total) $to = $total;

	for ($i = $from; $i < $to; $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		// width: 180px;
		$out .= '<a href="#" id="model_sel_id'.$qr[$i]['model_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; " onclick="model_autocomplete_use('.$qr[$i]['shipmodel_id'].'); return false;"><nobr>';
		
		$s = get_item_shipmodel_name_full($qr[$i]['shipmodel_id'], '');
		
		$natoc_id = my_get_shipmodel_natoc_id($qr[$i]['shipmodel_id']);
		if ($natoc_id > 0) {
			$s .= ' ('.my_get_natoc_str($natoc_id).')';
		}

		if ($qr[$i]['shipmodelclass_id'] > 0) {
			$s .= ' ['.my_get_shipclass_name($qr[$i]['shipmodelclass_id']).']';
		}

		$out .= $s;
		$out .= '</nobr></a>';
	}
	
	$out .= outhtml_model_sel_autocomplete_paginator($param, $total);

	$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_model_autocomplete_div($param) {

	$out = '';
	
	// border: solid 1px red;
	$out .= '<div id="model_autocomplete_div" style="  ">';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_model_autocomplete_check_exist(&$param) {

	//$param['ajp']['check_exist'] = '1';

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	if ($param['q'] == '') {
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	
	//$param['ajp']['check_exist'] = '2';
	
	//
	
	$str = trim($param['q']);
	
	//$str = my_clean_string_ac($str);
	//$str = my_clean_string_ac($str);
	
	//$param['ajp']['check_exist'] = '3';
	
	if ($str == '') {
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	//$param['ajp']['check_exist'] = '4';

	// prepared query
	$a = array();
	$q = "".
		"SELECT shipmodel.shipmodel_id, shipmodel.name ".
		"FROM shipmodel ".
		"WHERE ( shipmodel.name = ? ) ";
		";";
	$a[] = $str;
	$t = 's';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	//$param['ajp']['check_exist'] = '5'.$str;
	
	if (sizeof($qr) == 0) {
		$param['ajp']['hide_upstore'] = 'no';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	//$param['ajp']['check_exist'] = '6';
	
	/*
	if (sizeof($qr) > 1) {
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	*/
	
	//$param['ajp']['check_exist'] = '7'.$str;
	
	/*
	if ($qr[0]['name'] == $originalstr) {
		$param['ajp']['hide_upstore'] = 'no';
		$param['ajp']['color_input'] = 'purple';
		return true;
	}
	*/
	
	if (sizeof($qr) >= 1) {
		$param['ajp']['hide_upstore'] = 'no';
		$param['ajp']['color_input'] = 'purple';
		return true;
	}
	
	//$param['ajp']['check_exist'] = '8'.$str;
	
	return true;
}


// =============================================================================
function jqfn_model_autocomplete_update(&$param) {

	if (!isset($param['q'])) return false;

	if (!can_i_edit_item($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodel_str, item.shipmodel_id ".
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
	
	//$str = my_clean_string_ac($param['q']);
	$str = trim($param['q']);

	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.shipmodel_id = '0',  ".
		" item.shipmodel_str = ? ".
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
	
	$param['ajp']['color_input'] = 'yellow';
	$param['ajp']['hide_upstore'] = 'no';
	
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_model_autocomplete_search(&$param) {

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	$param['ajp']['elemtoplace'] = 'model_autocomplete_div';
	$param['html'] = '';
	
	jqfn_model_autocomplete_update(&$param);
	
	if ($param['q'] == '') {
		return true;
	}
	
	$param['html'] .= outhtml_model_autocomplete_content(&$param);
	
	jqfn_model_autocomplete_check_exist(&$param);
	
	return true;
}


// =============================================================================
function jqfn_model_autocomplete_select(&$param) {
	
	if (!isset($param['model_id'])) return false;
	if (!ctype_digit($param['model_id'])) return false;
	$param['model_id'] = ''.intval($param['model_id']);
	if (my_get_shipmodel_name($param['model_id']) === false) return false;
	
	// apply model data here
	
	// from model
	
	$q = " SELECT * ".
		" FROM shipmodel ".
		" WHERE shipmodel.shipmodel_id = '".$param['model_id']."' ".
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
	
	//
	
	if ($qr[0]['shipmodelclass_id'] > 0) {
		$class_str = my_get_shipclass_name($qr[0]['shipmodelclass_id']);
	} else {
		$class_str = '';
	}
	
	if ($qr[0]['natoc_id'] > 0) {
		$natoc_str = my_get_natoc_str($qr[0]['natoc_id']);
	} else {
		$natoc_str = '';
	}
	
	//
	
	// prepared query
	$a = array();
	$a[] = $param['model_id'];
	$a[] = $qr[0]['name'];
	$a[] = $qr[0]['shipmodelclass_id'];
	$a[] = $class_str;
	$a[] = $qr[0]['natoc_id'];
	$a[] = $natoc_str;
	$a[] = $param['i'];
	$t = 'isisisi';
	$q = "".
		" UPDATE item SET ".
		" item.shipmodel_id = ?, ".
		" item.shipmodel_str = ?, ".
		" item.shipmodelclass_id = ?, ".
		" item.shipmodelclass_str = ?, ".
		" item.natoc_id = ?, ".
		" item.natoc_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	

	update_item_searchstring($param['i']);
	
	// return complete classification block
	$param['ajp']['elemtoplace'] = 'item_classification_div';
	$param['html'] = outhtml_item_classification_content(array('i' => $param['i']));
	
	return true;
}


// =============================================================================
function jqfn_model_autocomplete($param) {

	if (!am_i_registered_user()) return false;
	
	//print 'z2';

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_model_autocomplete_callback';
	// $param['ajp']['hide_upstore'] = 'no';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	//print 'z3';
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'search') {
		jqfn_model_autocomplete_search(&$param);
	}
	if ($param['c'] == 'select') {
		jqfn_model_autocomplete_select(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	// my_model_autocomplete_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/model_autocomplete.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_model_autocomplete($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>