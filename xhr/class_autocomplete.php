<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
//require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/class_factoryserialnum_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_classification.php');


// =============================================================================
function outhtml_script_class_autocomplete() {

$str = <<<SCRIPTSTRING

function js_class_autocomplete_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}

	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById(aresp['class_autocomplete_div']);
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
		var elem = document.getElementById(aresp['class_upstore_div']);
		if (elem) {
			if (aresp['hide_upstore'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['hide_upstore'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	
	
	if (typeof aresp['color_input'] == 'string') {
		var elem = document.getElementById('class_input');
		if (elem) {
			if (aresp['color_input'] == 'red') aresp['color_input'] = 'ffd7d7';
			if (aresp['color_input'] == 'yellow') aresp['color_input'] = 'fff4ae';
			if (aresp['color_input'] == 'green') aresp['color_input'] = 'd6ffd5';
			if (aresp['color_input'] == 'purple') aresp['color_input'] = 'ffd6ff';
			elem.style.backgroundColor = '#' + aresp['color_input'];
		}
	}


	if (typeof aresp['show_enlist_button'] == 'string') {
		var elem = document.getElementById(aresp['class_enlist_div']);
		if (elem) {
			if (aresp['show_enlist_button'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_enlist_button'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	class_enlist_refresh();
	
	
	return true;
}


function class_autocomplete_clear() {
	var elem = document.getElementById('class_autocomplete_div');
	if (elem) {
		elem.innerHTML = '';
		return true;
	}
	return false;
}


function class_autocomplete_search(q, pn) {

	if (typeof pn === 'undefined') pn = '0';

	var elem = document.getElementById('class_autocomplete_div');
	if (elem) {
		// elem.innerHTML = 'запрос...';
	}

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
		
			
		
			var url = '/xhr/class_autocomplete.php?i=' + item_id + '&c=search' + '&pn=' + pn + '&q=' + q + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}


function class_autocomplete_enlist(q, pn) {

	if (typeof pn === 'undefined') pn = '0';

	var elem = document.getElementById('class_autocomplete_div');
	if (elem) {
		// elem.innerHTML = 'запрос...';
	}

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {

		var item_id = elem.value;
		if (is_numeric(item_id)) {
		
			var url = '/xhr/class_autocomplete.php?i=' + item_id + '&c=enlist' + '&pn=' + pn + '&q=' + q + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}


function class_autocomplete_gotopn(pn) {

	if (typeof shipclass_sel_str != 'string') return false;

	var elem = document.getElementById('class_autocomplete_mode');
	if (elem) {
		if (elem.value == 'search') {
			class_autocomplete_search(shipclass_sel_str, pn);
		}
		if (elem.value == 'enlist') {
			class_autocomplete_enlist('', pn);
		}
	}
}


function class_autocomplete_use(class_id) {
	
	class_id = '' + class_id;
	
	if (!is_numeric(class_id)) return false;

	var mode = 'enlist';
	var elem = document.getElementById('class_autocomplete_mode');
	if (elem) {
		if (elem.value == 'search') {
			// class_autocomplete_search(shipclass_sel_str, pn);
			mode = 'search';
		}
		if (elem.value == 'enlist') {
			// class_autocomplete_enlist('', pn);
			mode = 'enlist';
		}
	}

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = '' + elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/class_autocomplete.php?i=' + item_id + '&c=select' + '&class_id=' + class_id + '&mode=' + mode + '';
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
function get_class_sel_autocomplete_page_size() { return (12); }


// =============================================================================
function outhtml_class_sel_autocomplete_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_class_sel_autocomplete_page_size();

	return outhtml_uni_paginator($pagesize, 4, 'class_autocomplete_gotopn', $param['pn'], $total).PHP_EOL;
}


// =============================================================================
function outhtml_class_autocomplete_content($param) {

	$param['i'] = ''.intval($param['i']);

	$out = '';

	//

	$list = array();

	if ($param['c'] == 'enlist') {

		$q = " SELECT item.shipmodelclass_id ".
			" FROM item ".
			" WHERE item.item_id = '".$param['i']."' ".
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

		if ($qr[0]['shipmodelclass_id'] == 0) {
			$parent_id = 0;
		} else {
			$parent_id = $qr[0]['shipmodelclass_id'];
		}

		//

		$q = " SELECT shipmodelclass.shipmodelclass_id, ".
			" shipmodelclass.text ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.parent_id = '".$parent_id."' ".
			" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
			" LIMIT 1000 ".   
			"";
		$list = mydb_queryarray($q);
		if ($list === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}

	}

	//

	if ($param['c'] == 'search') {

		$qstr = $param['q'];
	
		if (!isset($qstr)) $qstr = '';
		
		$originalstr = $qstr;

		
		$qstr = my_simplify_text_string($qstr);
		$qstr = my_simplify_text_string($qstr);
		$qstr = eyo_str($qstr);
		//$out .= $qstr;
		
		
		if (mb_strlen($qstr) < 1) return '';
		$arr = explode(' ', $qstr, 8);
		if (sizeof($arr) < 1) return '';
		
		// $qstr = eyo_str($qstr);
		// REPLACE(LOWER(shipmodelclass.text), 'ё', 'е')
		
		// prepared query
		$a = array();
		$a[] = $arr[0];
		$t = 's';
		$q = " SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE ( LOCATE( ?, REPLACE(LOWER(shipmodelclass.text), 'ё', 'е') ) > 0 ) ";
		for ($i = 1; $i < sizeof($arr); $i++) {
			$q .= "AND ( LOCATE( ?, REPLACE(LOWER(shipmodelclass.text), 'ё', 'е') ) > 0 ) ";
			$a[] = $arr[$i];
			$t .= 's';
		}
		$q .= " ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
			" LIMIT 1000 ".   
			"";
		$list = mydb_prepquery($q, $t, $a);
		if ($list === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
		
	}

	$total = sizeof($list);
	
	
	
	$out .= '<div style=" padding-top: 3px; ">';

	
	$out .= '<input type="hidden" id="class_autocomplete_mode" value="'.$param['c'].'" />';
	
	if (sizeof($list) == 0) {
		$out .= '<div style=" display: block; float: left; color: #a08080; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; border: solid 1px #b0b0b0; ">';
		if ($param['c'] == 'search') $out .= 'не найдено';
		if ($param['c'] == 'enlist') $out .= 'пусто';
		$out .= '</div>';
	}
	
	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_class_sel_autocomplete_page_size();
	$totalpages = ceil($total / $pagesize);
	if ($param['pn'] > $totalpages) $param['pn'] = $totalpages;
	$from = $param['pn'] * $pagesize;
	$to = $from + $pagesize;
	if ($to > $total) $to = $total;

	for ($i = $from; $i < $to; $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		// width: 180px;
		$out .= '<a href="#" id="class_sel_id'.$list[$i]['shipmodelclass_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; " onclick="class_autocomplete_use('.$list[$i]['shipmodelclass_id'].'); return false;"><nobr>';
		
			$out .= my_get_shipclass_name($list[$i]['shipmodelclass_id'], '');

		$out .= '</nobr></a>';
	}
	
	$out .= outhtml_class_sel_autocomplete_paginator($param, $total);

	$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_class_autocomplete_div($param) {

	$out = '';
	
	// border: solid 1px red;
	$out .= '<div id="class_autocomplete_div" style="  ">';

		if (isset($param['full_enlist'])) {
			if ($param['full_enlist'] == 'yes') {
				$d = $param;
				jqfn_class_autocomplete_enlist($d);
				$out .= $d['html'];
			}
		}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_class_autocomplete_check_exist(&$param) {

	$param['ajp']['check_exist'] = '1';

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	if ($param['q'] == '') {
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	
	$param['ajp']['check_exist'] = '2';
	
	//
	
	$originalstr = $param['q'];
	$str = $param['q'];
	
	$str = my_clean_string_ac($str);
	$str = my_clean_string_ac($str);
	
	$param['ajp']['check_exist'] = '3';
	
	if ($str == '') {
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '4';
	
	// prepared query
	$a = array();
	$a[] = $str;
	$q = "".
		"SELECT shipmodelclass.shipmodelclass_id, shipmodelclass.text ".
		"FROM shipmodelclass ".
		"WHERE ( shipmodelclass.text = ? ) ".
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
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '6';
	
	if (sizeof($qr) > 1) {
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '7'.$str;
	
	if ($qr[0]['name'] == $originalstr) {
		$param['ajp']['hide_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'purple';
		return true;
	}
	
	$param['ajp']['check_exist'] = '8'.$str;
	
	return true;
}


// =============================================================================
function jqfn_class_autocomplete_update(&$param) {

	$param['i'] = ''.intval($param['i']);

	if (!isset($param['q'])) return false;

	if (!can_i_edit_item($param['i'])) return false;

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodelclass_str, item.shipmodelclass_id ".
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
	
	$str = my_clean_string_ac($param['q']);
	$str = my_clean_string_ac($str);
	
	// prepared query
	$a = array();
	$a[] = $str;
	$a[] = $param['i'];
	$q = "".
		" UPDATE item ".
		" SET item.shipmodelclass_id = '0',  ".
		" item.shipmodelclass_str = ? ".
		" WHERE item.item_id = ? ".
		";";
	$t = 'si';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	
	$param['ajp']['color_input'] = 'yellow';
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_class_autocomplete_search(&$param) {

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	$param['ajp']['elemtoplace'] = 'class_autocomplete_div';
	$param['html'] = '';
	
	if ($param['q'] == '') {
		return true;
	}

	//

	jqfn_class_autocomplete_update($param);
	
	jqfn_class_autocomplete_check_exist($param);
	
	//
	$out = '';
	$out .= '<div style=" padding-top: 3px; ">';

		// list
		$out .= '<div style=" ">';
			$out .= outhtml_class_autocomplete_content($param);
		$out .= '</div>';

	$out .= '</div>';

	//
	
	$param['html'] .= $out;
	
	//
	
	return true;
}


// =============================================================================
function jqfn_class_autocomplete_enlist(&$param) {

	$param['i'] = ''.intval($param['i']);

	$q = " SELECT item.shipmodelclass_id, item.shipmodelclass_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
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
	
	$param['ajp']['elemtoplace'] = 'class_autocomplete_div';
	$param['html'] = '';

	//

	$out = '';

	//

	if ($qr[0]['shipmodelclass_id'] == 0) {
		$parent_id = 0;
	} else {
		$parent_id = my_get_shipclass_parent($qr[0]['shipmodelclass_id']);
		// $parent_id = $qr[0]['shipmodelclass_id'];
	}

	//

	// $out .= 'parent'.$parent_id.'.';

	$out .= '<div style=" padding-top: 3px; ">';

		// back to parent link
		if ($qr[0]['shipmodelclass_id'] > 0) {
			$out .= '<a href="#" id="class_autocomplete_sel_id'.$parent_id.'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px; padding-right: 3px; overflow: hidden; width: 100px; min-height: 22px; padding-left: 24px;  padding-top: 2px; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/arrow_turn_left.png\'); " onclick=" class_autocomplete_use('.$parent_id.'); return false; " >';
			$out .= 'назад';
			$out .= '</a>';
		}

		// list
		$out .= '<div style=" padding-left: 22px; ">';
			$out .= outhtml_class_autocomplete_content($param);
		$out .= '</div>';

	$out .= '</div>';

	//
	
	$param['html'] .= $out;
	
	return true;
}


// =============================================================================
function jqfn_class_autocomplete_select(&$param) {
	
	if (!isset($param['class_id'])) return false;
	if (!ctype_digit($param['class_id'])) return false;
	$param['class_id'] = ''.intval($param['class_id']);
	
	if ($param['class_id'] > 0) {
		if (my_get_shipclass_name($param['class_id']) === false) return false;
	}
	
	// apply class data here
	
	// from class
	
	if ($param['class_id'] > 0) {
		$class_str = my_get_shipclass_name($param['class_id']);
		$top = my_get_top_shipmodelclass_id($param['class_id']);

	} else {
		$class_str = '';
		$top = 0;
	}

	
	// prepared query
	$a = array();
	$a[] = $param['class_id'];
	$a[] = $class_str;
	$a[] = $top;
	$a[] = $param['i'];
	$t = 'isii';
	$q = "".
		" UPDATE item SET ".
		" item.shipmodelclass_id = ?, ".
		" item.shipmodelclass_str = ?, ".
		" item.top_shipmodelclass_id = ? ".
		" WHERE item.item_id = ? ".
		";";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	update_item_searchstring($param['i']);
	
	// return complete classification block
	$param['ajp']['elemtoplace'] = 'form_class_div';
	$d = array('i' => $param['i']);
	if ($param['mode'] == 'enlist') $d['full_enlist'] = 'yes';
	$param['html'] = outhtml_form_class_content($d);
	
	return true;
}


// =============================================================================
function jqfn_class_autocomplete($param) {

	if (!am_i_registered_user()) return false;
	
	//print 'z2';

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_class_autocomplete_callback';
	$param['ajp']['show_enlist_button'] = 'yes';
	// $param['ajp']['hide_upstore'] = 'no';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	//print 'z3';
	
	if (!isset($param['c'])) $param['c'] = '';

	if ($param['c'] == 'search') {
		jqfn_class_autocomplete_search($param);
	}

	if ($param['c'] == 'enlist') {
		jqfn_class_autocomplete_enlist($param);
		$param['ajp']['show_enlist_button'] = 'no';
	}

	if ($param['c'] == 'select') {
		jqfn_class_autocomplete_select($param);
		$param['ajp']['show_enlist_button'] = 'no';
	}

	header('Content-Type: text/html; charset=utf-8');
	
	// my_class_autocomplete_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/class_autocomplete.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_class_autocomplete($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>