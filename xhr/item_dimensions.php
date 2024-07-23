<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_dimensions() {

$str = <<<SCRIPTSTRING

var item_dimensions_str_w = '';
var item_dimensions_str_h = '';
var TimeToUpdate = 0;

function js_item_dimensions_query() {
	
	var elem = document.getElementById('item_dimensions_input_w');
	if (elem) {
		var strw = elem.value;
	} else {
		var strw = '';
	}
	
	var elem = document.getElementById('item_dimensions_input_h');
	if (elem) {
		var strh = elem.value;
	} else {
		var strh = '';
	}
	
	var elem = document.getElementById('item_dimensions_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/item_dimensions.php?i=' + item_id + '&strw=' + strw + '&strh=' + strh;
	
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
			  if (BlockName == 'item_dimensions_saved_ok') {
				var elem = document.getElementById('item_dimensions_input_w');
				if (elem) {
					elem.style.backgroundColor = '#d6ffd5';
				}
				var elem = document.getElementById('item_dimensions_input_h');
				if (elem) {
					elem.style.backgroundColor = '#d6ffd5';
				}
			  } else {
				var elem = document.getElementById('item_dimensions_input_w');
				if (elem) {
					elem.style.backgroundColor = '#ffd7d7';
				}
				var elem = document.getElementById('item_dimensions_input_h');
				if (elem) {
					elem.style.backgroundColor = '#ffd7d7';
				}
			}
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function item_dimensions_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var strw = document.getElementById('item_dimensions_input_w').value;
	var strh = document.getElementById('item_dimensions_input_h').value;
	if ((item_dimensions_str_w != strw) || (item_dimensions_str_h != strh)) {
		item_dimensions_str_w = strw;
		js_item_dimensions_query();
	}
}

function item_dimensions_test() {
	
	var elem = document.getElementById('item_dimensions_input_w');
	if (!elem) return false;
	var strw = elem.value;
	
	var elem = document.getElementById('item_dimensions_input_h');
	if (!elem) return false;
	var strh = elem.value;

	if ((item_dimensions_str_w != strw) || (item_dimensions_str_h != strh)) {
		elem.style.backgroundColor = '#fff4ae';
		//
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('item_dimensions_test_sub()', 800);
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_dimensions_input_div($param) {

	$out = '';
	
	if (isset($param['i'])) {
		$param['i'] = ''.intval($param['i']);
		
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.width, item.height ".
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
		$strw = $qr[0]['width'];
		$strh = $qr[0]['height'];
		$item_id = $param['i'];
		// $color = '#fff4ae'; // yellow
		$color = '#d6ffd5'; // green
	} else {
		$str = '';
		$item_id = 0;
		$color = '#ffffff';
	}
	
	$out .= '<div id="item_dimensions_input_div">';
	
	$out .= '<input type="hidden" name="item_dimensions_item_id" id="item_dimensions_item_id" value="'.$item_id.'" />';
	
	$out .= '<table><tr><td style=" vertical-align: top; font-size: 9pt; padding: 2px 5px 4px 5px; ">';
	
	$out .= 'ширина';
	
	$out .= '</td><td>';
	
	$out .= '<div style=" vertical-align: top; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 20px; text-align: right; " size="4" name="item_dimensions_input_w" id="item_dimensions_input_w"  onchange="item_dimensions_test()" onkeydown="item_dimensions_test()" onkeyup="item_dimensions_test()" value="'.$strw.'" /></div>';
	
	$out .= '</td><td style=" vertical-align: top; font-size: 9pt; padding: 2px 5px 4px 5px; ">';
	
	$out .= 'мм';
	
	$out .= '</td><td style=" vertical-align: top; font-size: 9pt; padding: 2px 5px 4px 15px; ">';
	
	$out .= 'высота';
	
	$out .= '</td><td>';
	
	$out .= '<div style=" vertical-align: top; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 20px; text-align: right; " size="4" name="item_dimensions_input_h" id="item_dimensions_input_h"  onchange="item_dimensions_test()" onkeydown="item_dimensions_test()" onkeyup="item_dimensions_test()" value="'.$strh.'" /></div>';
	
	$out .= '</td><td style=" vertical-align: top; font-size: 9pt; padding: 2px 5px 4px 5px; ">';
	
	$out .= 'мм';
	
	$out .= '</td></tr></table>';

	$out .= '</div>';

	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_item_dimensions_div($param) {

	$out = '';
	
	$out .= '<div id="item_dimensions_div">';
	
	$out .= outhtml_item_dimensions_input_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_dimensions(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.width, item.height ".
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
	
	if (!isset($param['strw'])) $param['strw'] = $qr[0]['width'];
	if (!isset($param['strh'])) $param['strh'] = $qr[0]['height'];
	
	$param['strw'] = trim($param['strw']);
	$param['strh'] = trim($param['strh']);
	
	$param['strw'] = mb_str_replace($param['strw'], ',', '.');
	$param['strh'] = mb_str_replace($param['strh'], ',', '.');
	
	if (!is_numeric($param['strw'])) return false;
	if (!is_numeric($param['strh'])) return false;
	

	// prepared query
	$a = array();
	$a[] = $param['strw'];
	$a[] = $param['strh'];
	$a[] = $param['i'];
	$t = 'ssi';
	$q = "".
		" UPDATE item ".
		" SET item.width = ?, ".
		" item.height = ? ".
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
function jqfn_item_dimensions($param) {

	$out = '';

	$result = try_update_item_dimensions(&$param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=item_dimensions_saved_ok;';
		$out .= '-->';
	} else {
		$out .= '<!--';
		$out .= 'blockid=item_dimensions_saved_fail;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_item_dimensions($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_dimensions.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>