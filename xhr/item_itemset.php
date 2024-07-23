<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_itemset() {

$str = <<<SCRIPTSTRING

var item_itemset_str = '';
var item_itemset_time_to_update = 0;

function js_item_itemset_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
			var elem = document.getElementById('itemset_input');
			if (elem) {
				item_itemset_str = elem.value;
			}
		}
	}
	
	if (typeof aresp['display_autocomplete'] == 'string') {
		var elem = document.getElementById('item_itemset_suggest_div');
		if (elem) {
			if (aresp['display_autocomplete'] == 'yes') {
				elem.style.display = 'block';
			}
			if (aresp['display_autocomplete'] == 'no') {
				elem.style.display = 'none';
			}
		}
	}
	
	if (typeof aresp['display_upstore'] == 'string') {
		var elem = document.getElementById('item_itemset_upstore_div');
		if (elem) {
			if (aresp['display_upstore'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display_upstore'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['color'] == 'string') {
		var elem = document.getElementById('itemset_input');
		if (elem) {
			if (aresp['color'] == 'red') aresp['color'] = 'ffd7d7';
			if (aresp['color'] == 'yellow') aresp['color'] = 'fff4ae';
			if (aresp['color'] == 'green') aresp['color'] = 'd6ffd5';
			if (aresp['color'] == 'purple') aresp['color'] = 'ffd6ff';
			elem.style.backgroundColor = '#' + aresp['color'];
		}
	}

	return true;
}


function item_itemset_use(itemset_id) {
	
	var elem = document.getElementById('item_classification_item_id');
	if (!elem) return false;
	item_id = elem.value;
	
	//item_itemset_str = '';
	item_itemset_time_to_update = 0;
	
	var url = '/xhr/item_itemset.php?i=' + item_id + '&c=select&itemset_id=' + itemset_id + '';
	return ajax_my_get_query(url);
}


function item_itemset_test_sub() {

	var TimeNow = new Date().getTime();
	if (TimeNow < item_itemset_time_to_update) return true;

	
	var elem = document.getElementById('itemset_input');
	if (!elem) return false;
	var str = elem.value;

	if (item_itemset_str != str) {
	
		var elem = document.getElementById('item_classification_item_id');
		if (!elem) return false;
		item_id = elem.value;
		
		var url = '/xhr/item_itemset.php?i=' + item_id + '&c=autocomplete&str=' + str + '';
		return ajax_my_get_query(url);
	}
	
	return true;
}

function item_itemset_test() {
	
	var elem = document.getElementById('itemset_input');
	if (!elem) return false;
	var str = elem.value;
	
	if (item_itemset_time_to_update < 0) {
		item_itemset_time_to_update = 0;
		return true;
	}

	if (item_itemset_str != str) {
		elem.style.backgroundColor = '#fff4ae';
		
		var TimeNow = new Date().getTime();
		item_itemset_time_to_update = TimeNow + 650;
		setTimeout('item_itemset_test_sub()', 800);
	}
}


function item_itemset_upstore() {

	var elem = document.getElementById('item_classification_item_id');
	if (!elem) return false;
	item_id = elem.value;
	
	var url = '/xhr/item_itemset.php?i=' + item_id + '&c=upstore' + '';
	return ajax_my_get_query(url);
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_itemset_input_result($param) {
	
	$out = '';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 280px; " name="itemset_input" id="itemset_input"  onchange="item_itemset_test()" onkeydown="item_itemset_test()" onkeyup="item_itemset_test()" value="" /></div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_itemset_inlist_block($itemset_id) {

	$itemset_id = ''.intval($itemset_id);

	$out = '';
	
	//
	
	$qr = mydb_queryarray("".
		" SELECT itemset.itemset_id, itemset.name ".
		" FROM itemset ".
		" WHERE itemset.itemset_id = '".$itemset_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$itemsetname = $qr[0]['name'];
	
	//
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id ".
		" FROM item ".
		" WHERE item.itemset_id = '".$itemset_id."' ".
		" ORDER BY item_id ".
		" LIMIT 1 ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) > 0) {
		$item_id = $qr[0]['item_id'];
	} else {
		$item_id = 0;
	}
	
	//
	
	$onclickstr = ' item_itemset_use('.$itemset_id.'); return false; ';

	// thumb div
	$out .= '<div style=" float: left; margin-right: 4px; margin-bottom: 10px; width: 48px; ">';

		// bagde image div
		$out .= '<div style=" width: 48px; height: 48px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$item_id.'&n=1&s=s\'); background-size: 85%; " >';
		
			
			
			$out .= '<a style=" position:relative; display: block; width: 46px; height: 46px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " href="#" onclick=" '.$onclickstr.' "  title="'.htmlspecialchars($itemsetname, ENT_QUOTES).'" >';
			$out .= '</a>'; 
			
			$out .= '<div style=" clear: both; "></div>';

		$out .= '</div>';

		//

		$out .= '<div style=" width: 48px; overflow: hidden; min-height: 12px; background-color: #ffffff; border: solid 1px #f0f0f0; border-radius: 3px; -moz-border-radius: 3px; cursor: pointer; " onclick=" '.$onclickstr.' " >';
		
			$out .= '<div style=" min-height: 12px; border-bottom: solid 6px #'.$color.'; ">';
		
				$out .= '<div style=" padding: 0px 15px 2px 4px; ">';

					// 66737b 
					$out .= '<p style=" font-size: 9pt; color: #4b575e; width: 38px; overflow: hidden; white-space: nowrap; " title="'.htmlspecialchars($itemsetname, ENT_QUOTES).'" >';
						$out .= htmlspecialchars($itemsetname, ENT_QUOTES);
					$out .= '</p>';
					

					//$out .= '<div style=" clear: both; "></div>';
					
				$out .= '</div>';
			
			$out .= '</div>';

		$out .= '</div>';

		//

	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_itemset_suggest_result($param) {

	// try_purge_empty_itemsets();

	$out = '';
	
	if (!isset($param['str'])) $param['str'] = '';
	
	$str = trim($param['str']);
	
	if ($str == '') {
	
		$qr = mydb_queryarray("".
			" SELECT itemset.itemset_id, itemset.name ".
			" FROM itemset ".
			" ORDER BY itemset.name ".
			" LIMIT 200 ".
			"");
		if ($qr === false) {
			out_silent_error("Ошибка БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
	} else {
	
		$originalstr = $param['str'];

		$param['str'] = my_simplify_text_string($param['str']);
		
		if (mb_strlen($param['str']) < 1) return '';
		$arr = explode(' ', $param['str'], 8);
		if (sizeof($arr) < 1) return '';
		
		// prepared query
		$a = array();
		$a[] = $arr[0];
		$t = 's';
		$q = " SELECT itemset.itemset_id, itemset.name ".
			" FROM itemset ".
			" WHERE ( LOCATE( ?, CONCAT('_', itemset.name) ) > 0 ) ";
		for ($i = 1; $i < sizeof($arr); $i++) {
			$q .= "AND ( LOCATE( ?, CONCAT('_', itemset.name) ) > 0 ) ";
			$a[] = $arr[$i];
			$t .= 's';
		}
		$a[] = $arr[0];
		$t .= 's';
		$q .= " ORDER BY LOCATE( ?, CONCAT('_', itemset.name) ) ".
			" LIMIT 100 ".
			"";
		$qr = mydb_prepquery($q, $t, $a);
		if ($qr === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
	
	}
	
	//my_write_log('outhtml_item_itemset_suggest_result line '.__LINE__.'');
	
	$out .= '<div style=" width: 204px; padding-top: 10px; ">';
		
		if (sizeof($qr) == 0) {
			$out .= '<div style=" ">';
			$out .= 'не найдено';
			$out .= '</div>';
			my_write_log('outhtml_item_itemset_suggest_result line '.__LINE__.'');
		}
		
		//my_write_log('outhtml_item_itemset_suggest_result line '.__LINE__.'');

		for ($i = 0; $i < sizeof($qr); $i++) {
			$out .= outhtml_itemset_inlist_block($qr[$i]['itemset_id']);
		}
		
		$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_itemset_input_div($param) {

	$param['i'] = ''.intval($param['i']);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.itemset_id, item.itemset_str ".
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
	//print '2';
	
	$str = $qr[0]['itemset_str'];
	$id = $qr[0]['itemset_id'];
	$item_id = $param['i'];
	$color = '#fff4ae'; // yellow
	if ($qr[0]['itemset_id'] > 0) {
		$basename = my_get_itemset_name($qr[0]['itemset_id']);
		if ($basename !== false) {
			if (trim($basename) == trim($str)) {
				$color = '#d6ffd5'; // green
			}
		}
	}

	
	$out .= '<div id="item_itemset_input_div">';
	
	/*
	$out .= '<input type="hidden" name="item_itemset_item_id" id="item_itemset_item_id" value="'.$item_id.'" />';
	$out .= '<input type="hidden" name="item_itemsetected_id" id="item_itemsetected_id" value="'.$id.'" />';
	*/
	
	$out .= '<table><tr><td style=" vertical-align: top; ">';
	
	$out .= '<div style=" vertical-align: top; padding-right: 5px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="32" name="itemset_input" id="itemset_input"  onchange="item_itemset_test()" onkeydown="item_itemset_test()" onkeyup="item_itemset_test()" value="'.$str = htmlspecialchars($str, ENT_QUOTES).'" /></div>';
	
	$out .= '</td><td>';
	
	// добавить в список
	if (can_i_moderate_item($param['i'])) {
	
		if ($qr[0]['itemset_id'] < 1) {
			$insertstyle = ' visibility: visible; ';
		} else {
			$insertstyle = ' visibility: hidden; ';
		}
	
		$out .= '<div id="item_itemset_upstore_div" style="'.$insertstyle.'" >';
	
			$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_add.png\'); " onclick=" item_itemset_upstore(); return false; " title=" создать такую серию ">';
			$out .= '</div>';
		
		$out .= '</div>';
	
		// $out .= '<img src="/images/database_add.png" onclick=" item_itemset_store(); " style=" margin-top: 4px; " alt="добавить в список" />';
	}
	
	$out .= '</td></tr></table>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_itemset_suggest_div($param) {

	$out = '';
	
	$out .= '<div id="item_itemset_suggest_div" style=" font-size: 11px; ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_itemset_content($param) {

	$out = '';
	
	$out .= outhtml_item_itemset_input_div($param);
	$out .= outhtml_item_itemset_suggest_div($param);

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_itemset_div($param) {

	$out = '';
	
	$out .= '<div id="item_itemset_div">';
	
	$out .= outhtml_item_itemset_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_beautify_item_itemset_str($str) {

	$str = mb_str_replace($str, '    ', ' ');
	$str = mb_str_replace($str, '  ', ' ');
	
	return $str;
}


// =============================================================================
function my_item_itemset_process(&$param) {

	if ($param['c'] == 'select') return true;

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.itemset_id, item.itemset_str ".
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
	
	if ($qr[0]['itemset_id'] > 0) {
		$param['ajp']['color'] = 'green';
		
		$param['ajp']['display_upstore'] = 'no';
	} else {
		$param['ajp']['color'] = 'yellow';
		$param['ajp']['display_upstore'] = 'yes';
	}

	if (!isset($param['str'])) {
		$param['ajp']['elemtoplace'] = 'item_itemset_suggest_div';
		$param['html'] = '';
		return true;
	}
	
	//
	
	$param['ajp']['display_autocomplete'] = 'yes';
	$param['html'] = outhtml_item_itemset_suggest_result($param);
	
	/*
	if ($param['str'] != '') {
		$param['ajp']['display_autocomplete'] = 'yes';
		$param['html'] = outhtml_item_itemset_suggest_result($param);
	} else {
		$param['ajp']['display_autocomplete'] = 'no';
	}
	*/
	$param['ajp']['elemtoplace'] = 'item_itemset_suggest_div';
	
	
	return true;
}



// =============================================================================
function try_purge_empty_itemsets() {

	$q = "".
		" DELETE FROM itemset ".
		" WHERE itemset_id ". 
		" NOT IN ".
		" ( ".
		" SELECT DISTINCT itemset_id FROM item ".
		" ) ".
		";";
	$qru = mydb_query($q);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
		
	return true;
}



// =============================================================================
function try_item_itemset_upstore(&$param) {

	try_purge_empty_itemsets();

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);

	//my_write_log('try_store_item_itemset line '.__LINE__.'');

	if (!can_i_moderate_item($param['i'])) return false;
	
	$param['ajp']['color'] = 'yellow';
	$param['ajp']['display_autocomplete'] = 'no';
	$param['ajp']['display_upstore'] = 'yes';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.itemset_id, item.itemset_str ".
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
	
	$str = trim($qr[0]['itemset_str']);
	$str = my_beautify_item_itemset_str($str);
	
	//
	
	if ($qr[0]['itemset_id'] > 0) {
		$storedstr = my_get_itemset_name($qr[0]['itemset_id']);
		
		if ($storedstr == $str) {
			$param['ajp']['color'] = 'green';
			$param['ajp']['display_upstore'] = 'no';
			return false;
		}
	}
	
	//
	
	// prepared query
	$a = array();
	$a[] = $str;
	$t = 's';
	$q = "".
		" SELECT * FROM itemset ".
		" WHERE itemset.name = ? ".
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	if (sizeof($qres) > 0) {
		$param['ajp']['color'] = '00ff00';
		return false;
	}
	
	//
		
	// prepared query
	$a = array();
	$a[] = $str;
	$t = 's';
	$q = "".
		" INSERT INTO itemset ".
		" SET itemset.name = ? ".
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$new_id = mydb_insert_id();
	if (!($new_id > 0)) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$q = "".
		" UPDATE item ".
		" SET item.itemset_id = '".$new_id."' ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$qru = mydb_query($q);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$param['ajp']['color'] = 'green';
	$param['ajp']['display_upstore'] = 'no';
	return false;
	
	$param['itemset_id'] = $new_id;
	
	return true;
}


// =============================================================================
function try_update_item_itemset(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!can_i_edit_item($param['i'])) return false;
	
	$str = trim($param['str']);
	$str = my_beautify_item_itemset_str($str);
	
	$param['ajp']['stop'] = '605';

	// prepared query
	$a = array();
	$a[] = $str;
	$t = 's';
	$q = "".
		" SELECT * FROM itemset ".
		" WHERE itemset.name = ? ".
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$param['ajp']['stop'] = '620';
	
	if (sizeof($qres) > 0) {
		$param['ajp']['color'] = 'purple';
		$param['ajp']['display_upstore'] = 'yes';
	}
	
	// prepared query
	$a = array();
	$a[] = $str;
	$a[] = $param['i'];
	$t = 'si';
	$q = "".
		" UPDATE item ".
		" SET item.itemset_id = '0', ".
		" item.itemset_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$param['ajp']['stop'] = '645';
		
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function try_item_itemset_select(&$param) {
	
	if (!isset($param['itemset_id'])) return false;
	if (!ctype_digit($param['itemset_id'])) return false;
	$param['itemset_id'] = ''.intval($param['itemset_id']);
	
	if ($param['itemset_id'] > 0) {
		if (my_get_itemset_name($param['itemset_id']) === false) return false;
	}
	
	// apply  data here
	
	// from 
	
	if ($param['itemset_id'] > 0) {
		$itemset_str = my_get_itemset_name($param['itemset_id']);
	} else {
		$itemset_str = '';
	}

	
	// prepared query
	$a = array();
	$a[] = $param['itemset_id'];
	$a[] = $itemset_str;
	$a[] = $param['i'];
	$t = 'isi';
	$q = "".
		" UPDATE item SET ".
		" item.itemset_id = ?, ".
		" item.itemset_str = ? ".
		" WHERE item.item_id = ? ".
		";";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	update_item_searchstring($param['i']);
	
	$param['ajp']['color'] = 'green';
	$param['ajp']['display_autocomplete'] = 'no';
	$param['ajp']['display_upstore'] = 'no';
	
	$param['html'] = outhtml_item_itemset_content($param);
	$param['ajp']['elemtoplace'] = 'item_itemset_div';
	
	// return complete classification block
	//$param['ajp']['elemtoplace'] = 'form_class_div';
	//$d = array('i' => $param['i']);
	//if ($param['mode'] == 'enlist') $d['full_enlist'] = 'yes';
	//$param['html'] = outhtml_form_class_content($d);
	
	return true;
}



// =============================================================================
function jqfn_item_itemset($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_item_itemset_callback';
	$param['ajp']['display_autocomplete'] = 'no';
	$param['ajp']['display_upstore'] = 'no';
	
	//
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'upstore') {
		try_item_itemset_upstore(&$param);
	}
	if ($param['c'] == 'autocomplete') {
		try_update_item_itemset(&$param);
		// try_item_itemset_autocomplete(&$param);
	}
	if ($param['c'] == 'select') {
		try_item_itemset_select(&$param);
	}
	
	//
	
	header('Content-Type: text/html; charset=utf-8');
	
	my_item_itemset_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_itemset.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_itemset($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>