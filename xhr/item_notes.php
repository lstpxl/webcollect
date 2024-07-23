<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_notes() {

$str = <<<SCRIPTSTRING

var item_notes_str = '';
var TimeToUpdate = 0;

function js_item_notes_query() {
	
	var elem = document.getElementById('item_notes_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('item_notes_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/item_notes.php?i=' + item_id + '&str=' + str;
	
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
			  if (BlockName == 'item_notes_saved_ok') {
				var elem = document.getElementById('item_notes_input');
				if (elem) {
					elem.style.backgroundColor = '#d6ffd5';
				}
			  } else {
				  var htmlclean = String(response).substring(IndexClean, String(response).length);
				  document.getElementById(BlockName).innerHTML = htmlclean;
				  document.getElementById(BlockName).style.visibility = 'visible';
				}
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function item_notes_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('item_notes_input').value;
	if (item_notes_str != str) {
		item_notes_str = str;
		js_item_notes_query();
	}
}

function item_notes_test() {
	
	var elem = document.getElementById('item_notes_input');
	if (!elem) return false;
	var str = elem.value;

	if (item_notes_str != str) {
		elem.style.backgroundColor = '#fff4ae';
		//
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('item_notes_test_sub()', 800);
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_notes_input_div($param) {

	$out = '';
	
	if (isset($param['i'])) {
		$param['i'] = ''.intval($param['i']);
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.notes ".
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
		$str = $qr[0]['notes'];
		$item_id = $param['i'];
		// $color = '#fff4ae'; // yellow
		$color = '#d6ffd5'; // green
	} else {
		$str = '';
		$item_id = 0;
		$color = '#ffffff';
	}
	
	$out .= '<div id="item_notes_input_div">';
	
	$out .= '<input type="hidden" name="item_notes_item_id" id="item_notes_item_id" value="'.$item_id.'" />';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><textarea cols="40" rows="2" class="hoverwhiteborder" style=" width: 552px; text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " name="item_notes_input" id="item_notes_input"  onchange="item_notes_test()" onkeydown="item_notes_test()" onkeyup="item_notes_test()" />'.$str.'</textarea></div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_item_notes_div($param) {

	$out = '';
	
	$out .= '<div id="item_notes_div">';
	
	$out .= outhtml_item_notes_input_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_notes(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.notes ".
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
	
	// prepared query
	$a = array();
	$a[] = $param['str'];
	$a[] = $param['i'];
	$t = 'si';
	$q = "".
		" UPDATE item ".
		" SET item.notes = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_item_notes($param) {

	$out = '';

	$result = try_update_item_notes(&$param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=item_notes_saved_ok;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_item_notes($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_notes.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>