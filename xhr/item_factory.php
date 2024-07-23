<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_factory() {

$str = <<<SCRIPTSTRING

var item_factory_str = '';
var TimeToUpdate = 0;

function js_item_factory_query(c) {
	
	var elem = document.getElementById('factory_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('item_selected_factory_id');
	if (elem) {
		var factory_id = elem.value;
	} else {
		var factory_id = '0';
	}
	
	var elem = document.getElementById('item_factory_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/item_factory.php?i=' + item_id + '&c=' + c +'&factory_id=' + factory_id + '&str=' + str;
	
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
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function item_factory_use(factory_id) {
	
	// alert(factory_id);
	
	var elem = document.getElementById('item_factory_id' + factory_id);
	if (!elem) return false;
	var str = elem.innerHTML;
	
	var elem = document.getElementById('factory_input');
	if (!elem) return false;
	elem.value = str;
	elem.style.backgroundColor = '#d6ffd5';

	var elem = document.getElementById('item_selected_factory_id');
	if (!elem) return false;
	
	// alert('sdkjhf');
	
	elem.value = factory_id;
	
	var elem = document.getElementById('item_factory_suggest_div');
	if (!elem) return false;
	
	elem.style.visibility = 'hidden';
	elem.innerHTML = '';
	
	js_item_factory_query('');

	return true;
}


function item_factory_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('factory_input').value;
	if (item_factory_str != str) {
		item_factory_str = str;
		js_item_factory_query('');
	}
}

function item_factory_test() {
	
	var elem = document.getElementById('factory_input');
	if (!elem) return false;
	var str = elem.value;

	if (item_factory_str != str) {
		elem.style.backgroundColor = '#fff4ae';
		//
		var elem2 = document.getElementById('item_selected_factory_id');
		if (elem2) {
			elem2.value = 0;
		}
		//
		if (str != '') {
			TimeToUpdate = new Date().getTime() + 650;
			htmlclean = 'запрос... ';
			document.getElementById('item_factory_suggest_div').innerHTML = htmlclean;
			document.getElementById('item_factory_suggest_div').style.visibility = 'visible';
			setTimeout('item_factory_test_sub()', 800);
			//alert('a');
		} else {
			document.getElementById('item_factory_suggest_div').style.visibility = 'hidden';
			document.getElementById('item_factory_suggest_div').innerHTML = '';
		}
	}
}


function item_factory_store() {
	js_item_factory_query('store');
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_factory_input_result($param) {
	
	$out = '';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="factory_input" id="factory_input"  onchange="item_factory_test()" onkeydown="item_factory_test()" onkeyup="item_factory_test()" value="" /></div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_factory_suggest_result($param) {

	$out = '';
	
	if (!isset($param['str'])) $param['str'] = '';
	
	$originalstr = $param['str'];

	$param['str'] = my_simplify_text_string($param['str']);

	
	if (mb_strlen($param['str']) < 1) return '';
	$arr = explode(' ', $param['str'], 8);
	if (sizeof($arr) < 1) return '';
	
	
	// prepared query
	$a = array();
	$a[] = $arr[0];
	$t = 's';
	$q = " SELECT factory.factory_id, factory.name ".
		" FROM factory ".
		" WHERE ( LOCATE( ?, CONCAT('_', factory.name) ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE( ?, CONCAT('_', factory.name) ) > 0 ) ";
		$a[] = $arr[$i];
		$t .= 's';
	}
	$a[] = $arr[0];
	$t .= 's';
	$q .= " ORDER BY LOCATE( ?, CONCAT('_', factory.name) ) ".
		" LIMIT 100 ". 
		"";
	print $q;
	$list = mydb_prepquery($q, $t, $a);
	if ($list === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	

	my_write_log('outhtml_item_factory_suggest_result line '.__LINE__.'');
	
	$out .= '<!--';
	$out .= 'blockid=item_factory_suggest_div;';
	$out .= '-->';
	
	$out .= '<div style=" ">';
	
	if (sizeof($list) == 0) {
		$out .= '<div style=" ">';
		$out .= 'не найдено';
		$out .= '</div>';
		my_write_log('outhtml_item_factory_suggest_result line '.__LINE__.'');
	}
	
	my_write_log('outhtml_item_factory_suggest_result line '.__LINE__.'');

	for ($i = 0; $i < sizeof($list); $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		$out .= '<a href="#" id="item_factory_id'.$list[$i]['factory_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 11px; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px;  width: 180px; " onclick="item_factory_use('.$list[$i]['factory_id'].'); return false;">';
		$out .= $list[$i]['name'];
		$out .= '</a>';
	}

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_factory_input_div($param) {

	$out = '';
	
	if (isset($param['i'])) {
		$param['i'] = ''.intval($param['i']);
		
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.factory_id, item.factory_str ".
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
		$str = $qr[0]['factory_str'];
		$id = $qr[0]['factory_id'];
		$item_id = $param['i'];
		$color = '#fff4ae'; // yellow
		if ($qr[0]['factory_id'] > 0) {
			$basename = my_get_factory_name($qr[0]['factory_id']);
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
	
	$out .= '<div id="item_factory_input_div">';
	
	$out .= '<input type="hidden" name="item_factory_item_id" id="item_factory_item_id" value="'.$item_id.'" />';
	$out .= '<input type="hidden" name="item_selected_factory_id" id="item_selected_factory_id" value="'.$id.'" />';
	
	$out .= '<table><tr><td style=" vertical-align: top; ">';
	
	$out .= '<div style=" vertical-align: top; padding-right: 5px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 150px; " size="25" name="factory_input" id="factory_input"  onchange="item_factory_test()" onkeydown="item_factory_test()" onkeyup="item_factory_test()" value="'.$str.'" /></div>';
	
	$out .= '</td><td>';
	
	// добавить в список
	if (can_i_moderate_item($param['i'])) {
		$out .= '<img src="/images/database_add.png" onclick=" item_factory_store(); " style=" margin-top: 4px; " alt="добавить в список" />';
	}
	
	$out .= '</td></tr></table>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_factory_suggest_div($param) {

	$out = '';
	
	$out .= '<div id="item_factory_suggest_div" style=" font-size: 11px; ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_factory_div($param) {

	$out = '';
	
	$out .= '<div id="item_factory_div">';
	
	$out .= outhtml_item_factory_input_div($param);
	$out .= outhtml_item_factory_suggest_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_beautify_item_factory_str($str) {

	$str = mb_str_replace($str, '    ', ' ');
	$str = mb_str_replace($str, '  ', ' ');
	$str = mb_str_replace($str, '"', '«');
	$str = mb_str_replace($str, '\'', '«');
	
	return $str;
}


// =============================================================================
function try_store_item_factory(&$param) {

	//my_write_log('try_store_item_factory line '.__LINE__.'');

	if (!can_i_moderate_item($param['i'])) return false;
	
	if (!isset($param['str'])) return false;
	
	$param['str'] = trim($param['str']);
	
	// $str = my_beautify_item_factory_str($param['str']);
	$str = $param['str'];
	

	//my_write_log('try_store_item_factory line '.__LINE__.'');
	
	// prepared query
	$a = array();
	$a[] = $str;
	$q = "".
		" SELECT factory.factory_id, factory.name ".
		" FROM factory ".
		" WHERE ( factory.name = ? ) ".
		";";
	$t = 's';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	if (sizeof($qr) > 0) {
		//my_write_log('try_store_item_factory line '.__LINE__.'');
		$param['factory_id'] = $qr[0]['factory_id'];
		return true;
	}
	
	//my_write_log('try_store_item_factory line '.__LINE__.'');
	
	//
	
	// $str = my_beautify_item_factory_str($param['str']);
	$str = $param['str'];

	//
	
	//my_write_log('try_store_item_factory line '.__LINE__.'');
	
	//my_write_log('try_store_item_factory line '.__LINE__.'');
	
	
	// prepared query
	$a = array();
	$a[] = $str;
	$q = "".
		" INSERT INTO factory ".
		" SET factory.name = ? ".
		";";
	$t = 's';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$new_id = mydb_insert_id();
	if (!($new_id > 0)) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//my_write_log('try_store_item_factory line '.__LINE__.'');
	
	$param['factory_id'] = $new_id;
	
	return true;
}


// =============================================================================
function try_update_item_factory(&$param) {

	$param['nosuggest'] = '0';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.factory_id, item.factory_str ".
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
	
	$param['str'] = trim($param['str']);
	
	if (!isset($param['factory_id'])) {
		$param['factory_id'] = 0;
		$basename = '';
	} else {
		if (!ctype_digit($param['factory_id'])) return false;
	}
	if ($param['factory_id'] == 0) {
		$basename = '';
	} else {
		$basename = my_get_factory_name($param['factory_id']);
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
			try_store_item_factory(&$param);
		}
	}
	

	// prepared query
	$a = array();
	$a[] = $param['factory_id'];
	$a[] = $param['str'];
	$a[] = $param['i'];
	$t = 'isi';
	$q = "".
		" UPDATE item ".
		" SET item.factory_id = ?, ".
		" item.factory_str = ? ".
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
function jqfn_item_factory($param) {

	$out = '';

	try_update_item_factory(&$param);

	if (!isset($param['str'])) return false;
	if ($param['str'] == '') return false;

	header('Content-Type: text/html; charset=utf-8');
		
	if ($param['nosuggest'] != '1') {
		$out .= outhtml_item_factory_suggest_result($param);
	}

	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_item_factory($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_factory.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>