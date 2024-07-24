<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_upstore.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_uplink.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_autocomplete.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');


// =============================================================================
function outhtml_script_shipmodel_sel() {

$str = <<<SCRIPTSTRING

var shipmodel_sel_str = '';
var TimeToUpdate = 0;


function js_shipmodel_sel_enable_children(enable) {
	if (enable == true) {
		//
		var elem = document.getElementById('shipclass_input');
		if (elem) {
			elem.readonly = false;
			elem.disabled = false;
		}
		//
		var elem = document.getElementById('shipmodelclass_button_bar');
		if (elem) {
			elem.style.visibility = 'visible';
		}
		//
	} else {
		//
		var elem = document.getElementById('shipclass_input');
		if (elem) {
			//elem.readonly = true;
			//elem.disabled = true;
		}
		//
		var elem = document.getElementById('shipmodelclass_button_bar');
		if (elem) {
			elem.style.visibility = 'hidden';
		}
		//
		var elem = document.getElementById('shipclass_sel_selector_div');
		if (elem) {
			elem.innerHTML = '';
		}
		//
		
	}
}

function js_shipmodel_sel_paint(c) {
	if (c == 'red') {
		var elem = document.getElementById('shipmodel_input');
		if (!elem) return false;
		elem.style.backgroundColor = '#ffd7d7';
		//
		var elem = document.getElementById('shipmodel_sel_store_button');
		if (elem) {
			elem.style.visibility = 'visible';
		}
		//
		js_shipmodel_sel_enable_children(true);
		//
		return true;
	}
	if (c == 'yellow') {
		var elem = document.getElementById('shipmodel_input');
		if (!elem) return false;
		elem.style.backgroundColor = '#fff4ae';
		//
		var elem = document.getElementById('shipmodel_sel_store_button');
		if (elem) {
			elem.style.visibility = 'visible';
		}
		//
		js_shipmodel_sel_enable_children(true);
		//
		return true;
	}
	if (c == 'green') {
		var elem = document.getElementById('shipmodel_input');
		if (!elem) return false;
		elem.style.backgroundColor = '#d6ffd5';
		//
		var elem = document.getElementById('shipmodel_sel_store_button');
		if (elem) {
			elem.style.visibility = 'hidden';
		}
		//
		js_shipmodel_sel_enable_children(false);
		//
		
		return true;
	}
	return false;
}


function js_shipmodel_sel_query(c, pn) {

	if (typeof pn === 'undefined') pn = '0';
	
	var elem = document.getElementById('shipmodel_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('shipmodel_selected_id');
	if (elem) {
		var shipmodel_id = elem.value;
	} else {
		var shipmodel_id = '0';
	}
	
	var elem = document.getElementById('shipmodel_sel_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/shipmodel_sel.php?i=' + item_id + '&c=' + c + '&pn=' + pn + '&shipmodel_id=' + shipmodel_id + '&str=' + str;
	
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
			  
				var elem2 = document.getElementById('shipmodel_selected_id');
				if (elem2) {
					if (elem2.value == 0) {
						js_shipmodel_sel_paint('yellow');
					}
				}
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function shipmodel_sel_use(shipmodel_id) {
	
	// alert(shipmodel_id);
	
	var elem = document.getElementById('shipmodel_sel_id' + shipmodel_id);
	if (!elem) return false;
	var str = elem.innerHTML;
	
	var elem = document.getElementById('shipmodel_input');
	if (!elem) return false;
	elem.value = str;
	js_shipmodel_sel_paint('green');

	var elem = document.getElementById('shipmodel_selected_id');
	if (!elem) return false;
	
	// alert('sdkjhf');
	
	elem.value = shipmodel_id;
	
	var elem = document.getElementById('shipmodel_sel_suggest_div');
	if (!elem) return false;
	
	elem.style.visibility = 'hidden';
	elem.innerHTML = '';
	
	js_shipmodel_sel_query('');

	return true;
}


function shipmodel_sel_onchange_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('shipmodel_input').value;
	if (shipmodel_sel_str != str) {



		shipmodel_sel_str = str;
		// js_shipmodel_sel_query('');
		model_autocomplete_search(str);
	}

	if (typeof model_autocomplete_clear == 'function') {
		// model_autocomplete_clear();
	}
	
	if (typeof model_upstore_refresh == 'function') {
		model_upstore_refresh();
	}
}


function shipmodel_sel_onchange() {

	var elem = document.getElementById('shipmodel_uplink_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
	
	var elem = document.getElementById('shipmodel_input');
	if (!elem) return false;
	var str = elem.value;

	if (shipmodel_sel_str != str) {
		js_shipmodel_sel_paint('red');

		var elem2 = document.getElementById('shipmodel_selected_id');
		if (elem2) {
			elem2.value = 0;
		}
		//
		if (str != '') {
			//TimeToUpdate = new Date().getTime() + 650;
			//htmlclean = 'запрос... ';
			//document.getElementById('shipmodel_sel_suggest_div').innerHTML = htmlclean;
			//document.getElementById('shipmodel_sel_suggest_div').style.visibility = 'visible';
			//setTimeout('shipmodel_sel_onchange_sub()', 800);
			//alert('a');
		} else {
			model_autocomplete_clear();
		}
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('shipmodel_sel_onchange_sub()', 800);
	}
}


function shipmodel_sel_store() {
	js_shipmodel_sel_query('store');
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


/*
// =============================================================================
function outhtml_shipmodel_sel_input_result($param) {
	
	$out = '';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="shipmodel_input" id="shipmodel_input"  onchange="shipmodel_sel_onchange()" onkeydown="shipmodel_sel_onchange()" onkeyup="shipmodel_sel_onchange()" value="" /></div>';
	
	return $out.PHP_EOL;
}
*/


/*
// =============================================================================
function get_shipmodel_sel_suggest_page_size() {
	return 8;
}
*/


/*
// =============================================================================
function outhtml_shipmodel_sel_suggest_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$out = '';
	
	$pagesize = get_shipmodel_sel_suggest_page_size();
	
	if ($total <= $pagesize) {
		return '';
	}
	
	
	$out .= '<div style=" min-height: 10px; padding: 10px 20px 10px 20px; ">';

	$pages = ceil($total / $pagesize);
	
	for ($c = 0; $c < $pages; $c++) {
		$local = '';
		if ($c == $param['pn']) {
			$local .= '<div style=" margin-right: 4px; float: left; padding: 1px 4px 6px 4px; background-color: #303030; color: #f0f0f0; font-size: 13px; border: 1px solid #000000; border-radius: 2px 2px 2px 2px; ">';
			$local .= ''.($c + 1).'';
			$local .= '</div> ';
		} else {
			$local .= '<a style=" margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #e0e0e0; color: #303030; float: left; font-size: 12px; border: 1px solid #808080; border-radius: 2px 2px 2px 2px;" href="#" onclick="shipmodel_sel_gotopn(\''.$c.'\'); return false">';
			$local .= ''.($c + 1);
			$local .= '</a> ';
		}
		$out .= $local;
	}
	
	$out .= '<div style=" clear: both; "></div>';
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}
*/


/*
// =============================================================================
function outhtml_shipmodel_sel_suggest_result($param) {

	$out = '';
	
	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	if (!isset($param['str'])) $param['str'] = '';
	
	$originalstr = $param['str'];
	

	$param['str'] = my_simplify_text_string($param['str']);
	$param['str'] = my_simplify_text_string($param['str']);
	//$out .= $param['str'];
	
	
	if (mb_strlen($param['str']) < 1) return '';
	$arr = explode(' ', $param['str'], 8);
	if (sizeof($arr) < 1) return '';
	
	$q = "SELECT COUNT(shipmodel.shipmodel_id) AS n ".
		"FROM shipmodel ".
		"WHERE ( LOCATE('".$arr[0]."', shipmodel.name ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE('".$arr[$i]."', shipmodel.name ) > 0 ) ";
	}
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		out_silent_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$total = $qr[0]['n'];
	
	$pagesize = get_shipmodel_sel_suggest_page_size();
	$pages = ceil($total / $pagesize);
	if ($param['pn'] >= $pages) $param['pn'] = ($pages - 1);
	if ($param['pn'] < 0) $param['pn'] = 0;
	$from = ($param['pn'] * $pagesize);
	
	$q = "SELECT shipmodel.shipmodel_id, shipmodel.nick, shipmodel.numcode, shipmodel.name ".
		"FROM shipmodel ".
		"WHERE ( LOCATE('".$arr[0]."', shipmodel.name ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE('".$arr[$i]."', shipmodel.name ) > 0 ) ";
	}
	$q .= " ORDER BY shipmodel.numcode, shipmodel.nick ".
		" LIMIT ".$from.", ".$pagesize." ".   
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		out_silent_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<!--';
	$out .= 'blockid=shipmodel_sel_suggest_div;';
	$out .= '-->';
	
	$out .= '<div style=" ">';
	
	if (sizeof($qr) == 0) {
		$out .= '<div style=" ">';
		$out .= 'не найдено';
		$out .= '</div>';
	}

	for ($i = 0; $i < sizeof($qr); $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		$out .= '<a href="#" id="shipmodel_sel_id'.$qr[$i]['shipmodel_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 11px; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px;  width: 180px; " onclick="shipmodel_sel_use('.$qr[$i]['shipmodel_id'].'); return false;">';
		$s = ''.$qr[$i]['numcode'];
		if ($qr[$i]['nick'] != '') $s .= ' «'.$qr[$i]['nick'].'»';
		if (mb_strlen($s) > 20) $s = mb_substr($s, 0, 20);
		$out .= $s;
		$out .= '</a>';
	}

	$out .= '</div>';
	
	$out .= outhtml_shipmodel_sel_suggest_paginator($param, $total);
	
	return $out.PHP_EOL;
}
*/


// =============================================================================
function outhtml_shipmodel_sel_input_div($param) {

	$out = '';
	
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodel_id, item.shipmodel_str ".
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
	
	$str = $qr[0]['shipmodel_str'];
	
	$sameexists = check_shipmodel_exists($str);
	
	$id = $qr[0]['shipmodel_id'];
	$item_id = $param['i'];
	$color = '#fff4ae'; // yellow
	
	if ($qr[0]['shipmodel_id'] > 0) {
		$basename = my_get_shipmodel_name($qr[0]['shipmodel_id']);
		if ($basename !== false) {
			if (trim($basename) == trim($str)) {
				$color = '#d6ffd5'; // green
			}
		}
	}

	//
	
	$out .= '<div >';
	
		$out .= '<input type="hidden" name="shipmodel_sel_item_id" id="shipmodel_sel_item_id" value="'.$item_id.'" />';
		$out .= '<input type="hidden" name="shipmodel_selected_id" id="shipmodel_selected_id" value="'.$id.'" />';
		
		$out .= '<div style=" vertical-align: top; ">';
			$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipmodel_input" id="shipmodel_input"  onchange="shipmodel_sel_onchange()" onkeydown="shipmodel_sel_onchange()" onkeyup="shipmodel_sel_onchange()" value="'.$str = htmlspecialchars($str, ENT_QUOTES).'" />';
		$out .= '</div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
/*
function outhtml_shipmodel_sel_suggest_div($param) {

	$out = '';
	
	$out .= '<div id="shipmodel_sel_suggest_div" style=" font-size: 11px; ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}
*/


// =============================================================================
function outhtml_shipmodel_sel_div($param) {

	$out = '';
	
	$out .= '<div id="shipmodel_sel_div">';
	
		$out .= outhtml_shipmodel_sel_input_div($param);
		// $out .= outhtml_shipmodel_sel_suggest_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_shipmodel_sel($param) {

	if (!am_i_registered_user()) return false;

	$out = '';

	//try_update_item_shipmodel($param);

	if (!isset($param['str'])) return false;
	if ($param['str'] == '') return false;

	header('Content-Type: text/html; charset=utf-8');
		
	if ($param['nosuggest'] != '1') {
		//$out .= outhtml_shipmodel_sel_suggest_result($param);
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/shipmodel_sel.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_shipmodel_sel($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>