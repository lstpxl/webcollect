<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');


// =============================================================================
function outhtml_script_shipclass_sel() {

$str = <<<SCRIPTSTRING

var shipclass_sel_str = '';
var TimeToUpdate = 0;

function js_shipclass_sel_query(c, selector_id) {

	// alert('c=' + c);
	
	var elem = document.getElementById('shipclass_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('shipclass_selected_id');
	if (elem) {
		var shipclass_id = elem.value;
	} else {
		var shipclass_id = '0';
	}
	
	var elem = document.getElementById('shipclass_sel_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/shipclass_sel.php?i=' + item_id + '&c=' + c +'&shipclass_id=' + shipclass_id + '&str=' + str;
	
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
			  
			  shipclass_upstore_refresh();
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function shipclass_sel_use(shipclass_id) {
	
	// alert(shipclass_id);
	
	var elem = document.getElementById('shipclass_sel_id' + shipclass_id);
	if (!elem) return false;
	var str = elem.innerHTML;
	
	var elem = document.getElementById('shipclass_input');
	if (!elem) return false;
	elem.value = str;
	elem.style.backgroundColor = '#d6ffd5';

	var elem = document.getElementById('shipclass_selected_id');
	if (!elem) return false;
	
	// alert('sdkjhf');
	
	elem.value = shipclass_id;
	
	var elem = document.getElementById('shipclass_sel_selector_div');
	if (!elem) return false;
	
	elem.style.visibility = 'hidden';
	elem.innerHTML = '';
	
	// js_shipclass_sel_query('');
	js_shipclass_sel_query('selector', shipclass_id);

	return true;
}


function shipclass_sel_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	// alert('ccc1');

	var str = document.getElementById('shipclass_input').value;
	if (shipclass_sel_str != str) {
		shipclass_sel_str = str;
		// js_shipclass_sel_query('');
		class_autocomplete_search(shipclass_sel_str, 0);
	}
}

function shipclass_sel_test() {

	var elem = document.getElementById('shipclass_input');
	if (!elem) return false;
	var str = elem.value;

	class_enlist_refresh();

	if (shipclass_sel_str != str) {
	
		class_uplink_hide();
	
		elem.style.backgroundColor = '#fff4ae';
		//

		//var elem2 = document.getElementById('shipclass_selected_id');
		//if (elem2) {
		//	elem2.value = 0;
		//}

		//
		if (str != '') {
			
			//alert('a');
		} else {
			// document.getElementById('class_autocomplete_div').style.visibility = 'hidden';
			document.getElementById('class_autocomplete_div').innerHTML = '';
		}

		TimeToUpdate = new Date().getTime() + 650;
		// htmlclean = 'запрос... ';
		// document.getElementById('class_autocomplete_div').innerHTML = htmlclean;
		// document.getElementById('class_autocomplete_div').style.visibility = 'visible';
		setTimeout('shipclass_sel_test_sub()', 800);

	}
}


function shipclass_sel_store() {
	js_shipclass_sel_query('store');
}

function shipclass_sel_open() {
	
	var elem = document.getElementById('shipclass_selected_id');
	if (!elem) return false;
	shipclass_id = elem.value;
	
	js_shipclass_sel_query('onlyselector', shipclass_id);
}

function shipclass_sel_goup() {

	var elem = document.getElementById('shipclass_selected_id');
	if (!elem) return false;
	shipclass_id = elem.value;
	
	js_shipclass_sel_query('goup', shipclass_id);
}


function shipclass_sel_suggest_result_gotopn(pn) {

	var elem = document.getElementById('shipclass_selected_id');
	if (!elem) return false;
	shipclass_id = elem.value;

	js_shipclass_sel_query('onlyselector', shipclass_id, pn);
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
/*
function outhtml_shipclass_sel_input_result($param) {
	
	$out = '';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="shipclass_input" id="shipclass_input"  onchange="shipclass_sel_test()" onkeydown="shipclass_sel_test()" onkeyup="shipclass_sel_test()" value="" /></div>';
	
	return $out.PHP_EOL;
}
*/



// =============================================================================
function outhtml_shipclass_sel_input_div($param) {

	

	$out = '';

	if (isset($param['i'])) {
		$param['i'] = ''.intval($param['i']);
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.shipmodelclass_id, item.shipmodelclass_str ".
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
		$str = $qr[0]['shipmodelclass_str'];
		$id = $qr[0]['shipmodelclass_id'];
		$item_id = $param['i'];
		$color = '#fff4ae'; // yellow

		// $out .= 'xx'.$qr[0]['shipmodelclass_str'];

		if ($qr[0]['shipmodelclass_id'] > 0) {
			$basename = my_get_shipclass_name($qr[0]['shipmodelclass_id']);
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

	// $out .= 'xx'.$str;
	
	$out .= '<div id="shipclass_sel_input_div">';
	
		$out .= '<input type="hidden" name="shipclass_sel_item_id" id="shipclass_sel_item_id" value="'.$item_id.'" />';
		
		//$out .= '<input type="hidden" name="shipclass_selected_id" id="shipclass_selected_id" value="'.$id.'" />';
		
		
		$out .= '<div style=" vertical-align: top; ">';

		$out .= '<input type="text" class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipclass_input" id="shipclass_input" onchange=" shipclass_sel_test(); return true; " onkeydown=" shipclass_sel_test(); return true; " onkeyup=" shipclass_sel_test(); return true; " value="'.$str.'" />';

		// $out .= '<input type="text" class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipclass_input" id="shipclass_input" onkeydown=" return true; " value="'.$str.'" />';

		$out .= '</div>';
		
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipclass_sel_selector_div($param) {

	$out = '';
	
	$out .= '<div id="shipclass_sel_selector_div" style=" font-size: 11px; ">';
	
	if ($param['c'] != '') {
		$out .= outhtml_shipclass_sel_selector_result($param);
	}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipclass_sel_div($param) {

	$out = '';
	
	$out .= '<div id="shipclass_sel_div">';
	
		$out .= outhtml_shipclass_sel_input_div($param);
	//$out .= outhtml_shipclass_sel_selector_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_store_item_shipclass(&$param) {

	if (!can_i_moderate_item($param['i'])) return false;
	
	if (!isset($param['str'])) return false;
	$param['str'] = trim($param['str']);
	if ($param['str'] == '') return false;
	
	$str = $param['str'];
	
	// $str = my_beautify_item_shipclass_str($param['str']);
	
	// prepared query
	$a = array();
	$a[] = $str;
	$t = 's';
	$q = "".
		" SELECT shipmodelclass.shipmodelclass_id, shipclass.nick, shipclass.numcode, shipclass.name ".
		" FROM shipmodelclass ".
		" WHERE ( shipmodelclass.name = ? ) ".
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	if (sizeof($qr) > 0) {
		$param['shipmodelclass_id'] = $qr[0]['shipmodelclass_id'];
		return true;
	}
	
	//
	
	// $str = my_beautify_item_shipclass_str($param['str']);
	// $a = my_break_item_shipclass_str($str);
	
	//
	
	
	// prepared query
	$a = array();
	$a[] = $a['text'];
	$t = 's';
	$q = "".
		" INSERT INTO shipmodelclass ".
		" SET shipmodelclass.text = ? ".
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
	
	$param['shipclass_id'] = $new_id;
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function try_update_item_shipclass(&$param) {

	$param['noselector'] = '0';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodelclass_id, item.shipmodelclass_str ".
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
	
	//print 'a('.$param['str'].')';
	
	if (!can_i_edit_item($param['i'])) return false;
	
	$param['str'] = trim($param['str']);
	
	if (!isset($param['shipclass_id'])) {
		$param['shipclass_id'] = 0;
		$basename = '';
	} else {
		if (!ctype_digit($param['shipclass_id'])) return false;
	}
	if ($param['shipclass_id'] == 0) {
		$basename = '';
	} else {
		$basename = my_get_shipclass_name($param['shipclass_id']);
		if ($basename === false) return false;
		//$param['noselector'] = '1';
	}
	
	// print 'b('.$param['str'].')';
	
	if ($basename != '') {
		if ($basename != '') {
			$param['str'] = $basename;
		}
	}
	
	// print 'c('.$param['str'].')';
	
	if (isset($param['c'])) {
		if ($param['c'] == 'store') {
			try_store_item_shipclass(&$param);
		}
	}
	
	// print 'd('.$param['str'].')';
	
	// print 'e('.$param['str'].')';
	
	// prepared query
	$a = array();
	$a[] = $param['shipclass_id'];
	$a[] = $param['str'];
	$a[] = $param['i'];
	$t = 'isi';
	$q = "".
		" UPDATE item ".
		" SET item.shipmodelclass_id = ?, ".
		" item.shipmodelclass_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	update_item_searchstring($param['i']);
	// $r = my_cascade_update_item_strs();
	
	return true;
}



// =============================================================================
function try_goup_item_shipclass(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodelclass_id, item.shipmodelclass_str ".
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
	
	if (!can_i_edit_item($param['i'])) return false;
	
	
	if (!isset($param['shipclass_id'])) return false;
	if (!ctype_digit($param['shipclass_id'])) return false;
	$param['shipclass_id'] = ''.intval($param['shipclass_id']);
	
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.parent_id ".
		"FROM shipmodelclass ".
		"WHERE shipmodelclass.shipmodelclass_id = '".$param['shipclass_id']."' ".
		"";
	$upper = mydb_queryarray($q);
	if ($upper === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($upper) < 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$new_id = $upper[0]['parent_id'];
	
	$param['shipclass_id'] = $new_id;
	
	if ($new_id == 0) {
		$basename = '';
	} else {
		$basename = my_get_shipclass_name($new_id);
		if ($basename === false) return false;
	}
	

	// prepared query
	$a = array();
	$a[] = $new_id;
	$a[] = $basename;
	$a[] = $param['i'];
	$t = 'isi';
	$q = "".
		" UPDATE item ".
		" SET item.shipmodelclass_id = ?, ".
		" item.shipmodelclass_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	
	return true;
}



// =============================================================================
function jqfn_shipclass_sel($param) {

	$out = '';
	header('Content-Type: text/html; charset=utf-8');
	
	if (!isset($param['noselector'])) $param['noselector'] = '0';
	if (!isset($param['c'])) $param['c'] = '';
	
	
	if ($param['c'] == 'onlyselector') {
	
		$out .= outhtml_shipclass_sel_selector_result($param);
	
	} elseif ($param['c'] == 'selector') {
	
		// $r = try_goup_item_shipclass(&$param);
		$r = try_update_item_shipclass(&$param);
		if ($r) {
			$out .= '<!--';
			$out .= 'blockid=shipclass_sel_div;';
			$out .= '-->';
			$out .= outhtml_shipclass_sel_input_div($param);
			// $out .= 'pre'.$param['noselector'];
			
			if ($param['noselector'] != '1') {
				$out .= outhtml_shipclass_sel_selector_div($param);
			}
			
		} else {
			$out .= outhtml_shipclass_sel_selector_div($param);
		}
		
	} elseif ($param['c'] == 'goup') {
	
		$r = try_goup_item_shipclass(&$param);
		if ($r) {
			$out .= '<!--';
			$out .= 'blockid=shipclass_sel_div;';
			$out .= '-->';
			$out .= outhtml_shipclass_sel_input_div($param);
			if ($param['noselector'] != '1') {
				$out .= outhtml_shipclass_sel_selector_div($param);
			}
		} else {
			$out .= outhtml_shipclass_sel_selector_div($param);
		}
	
	} else {

		try_update_item_shipclass(&$param);

		// if (!isset($param['str'])) return false;
		// if ($param['str'] == '') return false;
			
		if ($param['noselector'] != '1') {
			$out .= outhtml_shipclass_sel_selector_result($param);
		}
		
	}

	print $out;
	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/shipclass_sel.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_shipclass_sel($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>