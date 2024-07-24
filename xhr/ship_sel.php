<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');


// =============================================================================
function outhtml_script_ship_sel() {

$str = <<<SCRIPTSTRING

var ship_sel_str = '';
var TimeToUpdate = 0;

function js_ship_sel_enable_children(enable) {
	if (enable == true) {
		//
		var elem = document.getElementById('shipmodel_input');
		if (elem) {
			elem.readonly = false;
			elem.disabled = false;
		}
		//
		var elem = document.getElementById('shipclass_input');
		if (elem) {
			elem.readonly = false;
			elem.disabled = false;
		}
		//
		var elem = document.getElementById('shipmodel_sel_store_button');
		if (elem) {
			elem.style.visibility = 'visible';
		}
		//
		var elem = document.getElementById('shipmodelclass_button_bar');
		if (elem) {
			elem.style.visibility = 'visible';
		}
		//
	} else {
		//
		var elem = document.getElementById('shipmodel_input');
		if (elem) {
			//elem.readonly = true;
			//elem.disabled = true;
		}
		//
		var elem = document.getElementById('shipclass_input');
		if (elem) {
			//elem.readonly = true;
			//elem.disabled = true;
		}
		//
		var elem = document.getElementById('shipmodel_sel_store_button');
		if (elem) {
			elem.style.visibility = 'hidden';
		}
		//
		var elem = document.getElementById('shipmodelclass_button_bar');
		if (elem) {
			elem.style.visibility = 'hidden';
		}
		//
		
	}
}

function js_ship_sel_paint(c) {
	if (c == 'red') {
		var elem = document.getElementById('ship_input');
		if (elem) {
			elem.style.backgroundColor = '#ffd7d7';
		}
		//
		var elem = document.getElementById('ship_sel_store_button');
		if (elem) {
			elem.style.visibility = 'visible';
		}
		//
		js_ship_sel_enable_children(true);
		//
		return true;
	}
	if (c == 'yellow') {
		var elem = document.getElementById('ship_input');
		if (elem) {
			elem.style.backgroundColor = '#fff4ae';
		}
		//
		var elem = document.getElementById('ship_sel_store_button');
		if (elem) {
			elem.style.visibility = 'visible';
		}
		//
		js_ship_sel_enable_children(true);
		//
		return true;
	}
	if (c == 'green') {
		var elem = document.getElementById('ship_input');
		if (elem) {
			elem.style.backgroundColor = '#d6ffd5';
		}
		//
		var elem = document.getElementById('ship_sel_store_button');
		if (elem) {
			elem.style.visibility = 'hidden';
		}
		//
		js_ship_sel_enable_children(false);
		//
		return true;
	}
	return false;
}

function js_ship_sel_query(c, pn) {

	if (typeof pn === 'undefined') pn = '0';
	
	var elem = document.getElementById('ship_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('ship_selected_id');
	if (elem) {
		var ship_id = elem.value;
	} else {
		var ship_id = '0';
	}
	
	var elem = document.getElementById('ship_sel_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/ship_sel.php?i=' + item_id + '&c=' + c + '&pn=' + pn + '&ship_id=' + ship_id + '&str=' + str;
	
	var XMLHttpRequestObject = false;
  if (window.XMLHttpRequest) {
    try {
      XMLHttpRequestObject = new XMLHttpRequest();
    } catch (e) {}
  } else if (window.ActiveXObject) {
    try {
      XMLHttpRequestObject = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
      try {
        XMLHttpRequestObject = new ActiveXObject('Microsoft.XMLHTTP');
      } catch (e) {}
    }
  }
  if (!XMLHttpRequestObject) return false;
  XMLHttpRequestObject.open('GET', url, true);
  XMLHttpRequestObject.onreadystatechange = function() {
    try {
      if (XMLHttpRequestObject.readyState == 4) {
        if (XMLHttpRequestObject.status == 200) {
          var response = XMLHttpRequestObject.responseText;
          delete XMLHttpRequestObject;
          XMLHttpRequestObject = null;
		  if (String(response).indexOf("blockid=") > 0) {
			  var BlockNameIndex = (String(response).indexOf("blockid=") + 8);
			  var BlockNameEnd = (String(response).indexOf(";", BlockNameIndex));
			  var IndexClean = (String(response).indexOf("-->") + 3);
			  var BlockName = String(response).substring(BlockNameIndex, BlockNameEnd);
			  var htmlclean = String(response).substring(IndexClean, String(response).length);
			  document.getElementById(BlockName).innerHTML = htmlclean;
			  document.getElementById(BlockName).style.visibility = 'visible';
			  
				var elem2 = document.getElementById('ship_selected_id');
				if (elem2) {
					if (elem2.value == 0) {
						js_ship_sel_paint('yellow');
						
						
						
					}
				}
			  
				var stored_ok_index = (String(htmlclean).indexOf('ship_stored_ok') + 0);
				//alert(htmlclean);
				//alert(stored_ok_index);
				if (stored_ok_index > 0) {
					//alert('paint');
					js_ship_sel_paint('green');
						//
					var elem3 = document.getElementById('ship_sel_store_button');
					if (elem3) {
						elem3.display = 'none';
					}
					//
					
						
				}
				
				if (typeof ship_factoryserialnum_upstore_refresh == 'function') {
					ship_factoryserialnum_upstore_refresh();
				}
				
				
  
				
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function ship_sel_use(ship_id) {
	
	// alert(ship_id);
	
	var elem = document.getElementById('ship_sel_id' + ship_id);
	if (!elem) return false;
	var str = elem.innerHTML;
	
	js_ship_sel_paint('green');



	var elem = document.getElementById('ship_selected_id');
	if (!elem) return false;
	
	// alert('sdkjhf');
	
	elem.value = ship_id;
	
	var elem = document.getElementById('ship_sel_suggest_div');
	if (!elem) return false;
	
	elem.style.visibility = 'hidden';
	elem.innerHTML = '';
	
	js_ship_sel_query('nosuggest');
	
	if (typeof ship_factoryserialnum_upstore_refresh == 'function') {
		// ship_factoryserialnum_upstore_refresh();
	}
	
	return true;
}


function ship_sel_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('ship_input').value;
	if (ship_sel_str != str) {
		ship_sel_str = str;
		ship_autocomplete_search(str);
		// js_ship_sel_query();
		if (typeof ship_factoryserialnum_upstore_refresh == 'function') {
			// ship_factoryserialnum_upstore_refresh();
			// ship_factoryserialnum_upstore_hide();
		}
	}
	
	if (typeof ship_autocomplete_clear == 'function') {
		// ship_autocomplete_clear();
	}
	
}

function ship_sel_test() {
	
	var elem = document.getElementById('ship_input');
	if (!elem) return false;
	var str = elem.value;
	
	//if (typeof ship_upstore_hide == 'function') {
	//	ship_upstore_hide();
	//}
	
	if (str == '') {
		ship_autocomplete_clear();
	}
	

	if (ship_sel_str != str) {
	
		var elem = document.getElementById('ship_factoryserialnum_upstore_div');
		if (elem) {
			elem.style.visibility = 'hidden';
		}
	
		if (typeof item_ship_factoryserialnum_setstatus == 'function') {
			// item_ship_factoryserialnum_setstatus('yellow', true);
		}
	
		js_ship_sel_paint('red');
		//
		//if (typeof ship_factoryserialnum_upstore_hide == 'function') {
			//ship_factoryserialnum_upstore_hide();
		//}
		//
		var elem2 = document.getElementById('ship_selected_id');
		if (elem2) {
			elem2.value = 0;
		}
		//
		var elem3 = document.getElementById('ship_sel_store_button');
		if (elem3) {
			elem3.display = 'block';
		}
		//
		if (str != '') {
			//TimeToUpdate = new Date().getTime() + 650;
			//htmlclean = 'запрос... ';
			//document.getElementById('ship_sel_suggest_div').innerHTML = htmlclean;
			//document.getElementById('ship_sel_suggest_div').style.visibility = 'visible';
			//setTimeout('ship_sel_test_sub()', 800);
			//alert('a');
		} else {
			//document.getElementById('ship_sel_suggest_div').style.visibility = 'hidden';
			//document.getElementById('ship_sel_suggest_div').innerHTML = '';
		}
		
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('ship_sel_test_sub()', 800);
		
	}
}


function ship_sel_store() {
	js_ship_sel_query('store');
}


function ship_sel_suggest_result_gotopn(pn) {
	js_ship_sel_query('', pn);
}

function ship_autocomplete_gotopn(pn) {
	
	if (!is_numeric(pn)) return false;
	q = ship_sel_str;
	return ship_autocomplete_search(q, pn);
}



SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_ship_sel_input_result($param) {
	
	$out = '';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="ship_input" id="ship_input"  onchange="ship_sel_test()" onkeydown="ship_sel_test()" onkeyup="ship_sel_test()" value="" /></div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function get_ship_sel_suggest_page_size() {
	return (8);
}


// =============================================================================
function outhtml_ship_sel_suggest_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_ship_sel_suggest_page_size();

	return outhtml_uni_paginator($pagesize, 4, 'ship_sel_suggest_result_gotopn', $param['pn'], $total).PHP_EOL;
}

/*
// =============================================================================
function outhtml_ship_sel_suggest_result($param) {

	return '';

	$out = '';
	
	
	if (!isset($param['str'])) $param['str'] = '';
	
	$originalstr = $param['str'];

	
	$param['str'] = my_simplify_text_string($param['str']);
	$param['str'] = my_simplify_text_string($param['str']);
	//$out .= $param['str'];
	
	
	if (mb_strlen($param['str']) < 1) return '';
	$arr = explode(' ', $param['str'], 8);
	if (sizeof($arr) < 1) return '';
	

	$q = "SELECT ship.ship_id, ship.name, ship.factoryserialnum ".
		"FROM ship ".
		"WHERE ( LOCATE('".$arr[0]."', ship.name ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE('".$arr[$i]."', ship.name ) > 0 ) ";
	}
	$q .= " ORDER BY ship.numcode, ship.nick ".
		" LIMIT 100 ".   
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$total = sizeof($qr);
	
	$out .= '<div style=" ">';
	
	if (sizeof($qr) == 0) {
		$out .= '<div style=" ">';
		$out .= 'не найдено';
		$out .= '</div>';
	}
	
	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_ship_sel_suggest_page_size();
	$totalpages = ceil($total / $pagesize);
	if ($param['pn'] > $totalpages) $param['pn'] = $totalpages;
	$from = $param['pn'] * $pagesize;
	$to = $from + $pagesize;
	if ($to > $total) $to = $total;

	for ($i = $from; $i < $to; $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		$out .= '<a href="#" id="ship_sel_id'.$qr[$i]['ship_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 11px; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px;  width: 180px; " onclick="ship_sel_use('.$qr[$i]['ship_id'].'); return false;">';
		$s = ''.$qr[$i]['name'];
		if (($qr[$i]['factoryserialnum'] != '') && ($qr[$i]['factoryserialnum'] != '0')) {
			$s .= ' (сер.ном. '.$qr[$i]['factoryserialnum'].')';
		}
		//if ($qr[$i]['nick'] != '') $s .= ' «'.$qr[$i]['nick'].'»';
		//if (mb_strlen($s) > 20) $s = mb_substr($s, 0, 20);
		$out .= $s;
		$out .= '</a>';
	}
	
	$out .= outhtml_ship_sel_suggest_paginator($param, $total);

	$out .= '</div>';
	
	return $out.PHP_EOL;
}
*/

// =============================================================================
function outhtml_ship_sel_input_div($param) {

	$out = '';
	
	if (isset($param['i'])) {
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
		$str = $qr[0]['ship_str'];
		$id = $qr[0]['ship_id'];
		$item_id = $param['i'];
		$color = '#fff4ae'; // yellow
		if ($qr[0]['ship_id'] > 0) {
			$basename = my_get_ship_name($qr[0]['ship_id']);
			if ($basename !== false) {
				if ($basename == trim($str)) {
					$color = '#d6ffd5'; // green
				}
			}
		}
	} else {
		$str = '';
		$id = 0;
		$item_id = 0;
		$color = '#ffffff';
		$basename = '';
	}
	
	$out .= '<div id="ship_sel_input_div">';
	
	$out .= '<input type="hidden" name="ship_sel_item_id" id="ship_sel_item_id" value="'.$item_id.'" />';
	$out .= '<input type="hidden" name="ship_selected_id" id="ship_selected_id" value="'.$id.'" />';
	
	$out .= '<table><tr><td style=" vertical-align: top; ">';
	
	$out .= '<div style=" vertical-align: top; padding-right: 5px; "><input autocomplete="off" class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="ship_input" id="ship_input"  onchange="ship_sel_test()" onkeydown="ship_sel_test()" onkeyup="ship_sel_test()" value="'.htmlspecialchars($str, ENT_QUOTES).'" /></div>';
	
	$out .= '</td><td>';
	
	// добавить в список
	
	/*
	
	if ($id == 0) {
		$styleins = ' display: block; ';
	} else {
		$styleins = ' display: none; ';
	}
		$out .= '<img id="ship_sel_store_button" src="/images/database_add.png" onclick=" ship_sel_store(); " style=" margin-top: 4px; '.$styleins.' " alt="добавить в список" />';

	$out .= '</td><td>';
	
		// $out .= outhtml_ship_upstore_div(array('i' => $item_id));
		
		*/
	
	$out .= '</td></tr></table>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_ship_sel_suggest_div($param) {

	$out = '';
	
	$out .= '<div id="ship_sel_suggest_div" style=" font-size: 11px; ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_ship_sel_div($param) {

	$out = '';
	
	$out .= '<div id="ship_sel_div">';
	
	$out .= outhtml_ship_sel_input_div($param);
	$out .= outhtml_ship_sel_suggest_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_ship(&$param) {

	$param['nosuggest'] = '0';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
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
	
	//print 'line1';
	
	if (!can_i_edit_item($param['i'])) return false;
	
	$param['str'] = trim($param['str']);
	
	//print 'line2';
	
	if (!isset($param['ship_id'])) {
		$param['ship_id'] = 0;
		$basename = '';
	} else {
		if (!ctype_digit($param['ship_id'])) return false;
	}
	if ($param['ship_id'] == 0) {
		$basename = '';
	} else {
		$basename = my_get_ship_name($param['ship_id']);
		if ($basename === false) return false;
		$param['nosuggest'] = '1';
	}
	
	
	
	if ($basename != '') {
		if ($basename != '') {
			$param['str'] = $basename;
		}
	}
	
	if (isset($param['c'])) {
		if ($param['c'] == 'store') {
		
			
			try_store_item_ship($param);
		}
	}
	
	// prepared query
	$a = array();
	$a[] = $param['ship_id'];
	$a[] = $param['str'];
	$a[] = $param['i'];
	$t = 'isi';
	$q = "".
		" UPDATE item ".
		" SET item.ship_id = ?, ".
		" item.ship_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	
	update_item_searchstring($param['i']);
	
	return true;
}

// =============================================================================
function try_store_item_ship(&$param) {

	if (!can_i_moderate_item($param['i'])) return false;
	
	if (!isset($param['str'])) return false;
	$param['str'] = trim($param['str']);
	if ($param['str'] == '') return false;
	
	$str = $param['str'];
	
	// prepared query
	$a = array();
	$a[] = $str;
	$t = 's';
	$q = "".
		" SELECT ship.ship_id, ship.name ".
		" FROM ship ".
		" WHERE ( ship.name = ? ) ".
		";";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	if (sizeof($qr) > 0) {
		$param['shipmodel_id'] = $qr[0]['shipmodel_id'];
		return true;
	}
	
	//
	
	// prepared query
	$a = array();
	$a[] = $str;
	$t = 's';
	$q = "".
		" INSERT INTO ship ".
		" SET ship.name = ? ".
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
	
	$param['ship_id'] = $new_id;
	
	$param['nosuggest'] = '1';
	
	$param['stored'] = '1';
	
	// my_elemtree_build_treeindex();
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_ship_sel($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	if (!isset($param['str'])) return false;
	if ($param['str'] == '') return false;
	
	header('Content-Type: text/html; charset=utf-8');
	
	
	$param['stored'] = '0';
	
	try_update_item_ship($param);

	if (isset($param['c'])) {
		if ($param['c'] == 'nosuggest') {
			$param['nosuggest'] = '1';
			
			$out .= '<!--';
			$out .= 'blockid=ship_sel_div;';
			$out .= '-->';
			$out .= outhtml_ship_sel_input_div($param);
			print $out;
			return true;
		}
	}
	
	
		
	if ($param['nosuggest'] != '1') {
		$out .= '<!--';
		$out .= 'blockid=ship_sel_suggest_div;';
		$out .= '-->';
		//$out .= outhtml_ship_sel_suggest_result($param);
	} else {
		
		if (isset($param['stored'])) {
			if ($param['stored'] == '1') {
				$out .= '<!--';
				$out .= 'blockid=ship_sel_suggest_div;';
				$out .= '-->';
				$out .= '<div name="ship_stored_ok" >';
				$out .= 'сохранено';
				$out .= '</div>';
			}
		}
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/ship_sel.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_ship_sel($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>