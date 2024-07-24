<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_issuedate() {

$str = <<<SCRIPTSTRING

var item_issuedate_str = '';
var TimeToUpdate = 0;

function js_item_issuedate_query() {
	
	var elem = document.getElementById('item_issuedate_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('item_issuedate_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/item_issuedate.php?i=' + item_id + '&str=' + str + '';
	
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
			  if (BlockName == 'item_issuedate_saved_ok') {
				var elem = document.getElementById('item_issuedate_input');
				if (elem) {
					elem.style.backgroundColor = '#d6ffd5';
				}
			  } else {
				var elem = document.getElementById('item_issuedate_input');
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


function item_issuedate_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('item_issuedate_input').value;
	if (item_issuedate_str != str) {
		item_issuedate_str = str;
		js_item_issuedate_query();
	}
}

function item_issuedate_test() {
	
	var elem = document.getElementById('item_issuedate_input');
	if (!elem) return false;
	var str = elem.value;
	
	if (item_issuedate_str != str) {
		elem.style.backgroundColor = '#fff4ae';
		//
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('item_issuedate_test_sub()', 800);
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_issuedate_input_div($param) {

	$out = '';
	
	if (isset($param['i'])) {
	
		$param['i'] = ''.intval($param['i']);
	
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.issuedate ".
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
		if ($qr[0]['issuedate'] == 0) {
			$str = '';
		} else {
			$str = $qr[0]['issuedate'];
		}
		$item_id = $param['i'];
		// $color = '#fff4ae'; // yellow
		$color = '#d6ffd5'; // green
	} else {
		$str = '';
		$item_id = 0;
		$color = '#ffffff';
	}
	
	$out .= '<div id="item_issuedate_input_div">';
	
	$out .= '<input type="hidden" name="item_issuedate_item_id" id="item_issuedate_item_id" value="'.$item_id.'" />';
	
	
	$out .= '<div style=" vertical-align: top; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 20px; " size="6" name="item_issuedate_input" id="item_issuedate_input"  onchange="item_issuedate_test()" onkeydown="item_issuedate_test()" onkeyup="item_issuedate_test()" value="'.$str.'" /></div>';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_item_issuedate_div($param) {

	$out = '';
	
	$out .= '<div id="item_issuedate_div">';
	
	$out .= outhtml_item_issuedate_input_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_issuedate(&$param) {

	//my_write_log('issu 1');

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	//my_write_log('issu 2');
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.issuedate ".
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
	
	//my_write_log('issu 3');
	
	if (!can_i_edit_item($param['i'])) return false;
	
	//my_write_log('issu 4');
	
	if (!isset($param['str'])) return false;
	
	//my_write_log('issu 5');
	
	$param['str'] = trim($param['str']);
	
	//my_write_log('issu 6');
	
	if ($param['str'] == '') {
		// год неизвестен
		$param['str'] = '0';
		//my_write_log('issu 7 in');
	} else {
		//my_write_log('issu 8');
	
		//if (!ctype_digit($param['str'])) return false;
		
		$param['str'] = filter_var($param['str'], FILTER_VALIDATE_INT);
		
		//my_write_log('issu 9');
		
		$i = intval($param['str']);
		
		if ($i < 1500) return false;
		if ($i > 2090) return false;
		
		$param['str'] = ''.$i;
	}
	
	//my_write_log('issu 9');
	
	// prepared query
	$a = array();
	$a[] = $param['str'];
	$a[] = $param['i'];
	$q = "".
		" UPDATE item ".
		" SET item.issuedate = ? ".
		" WHERE ( item.item_id = ? ) ". 
		";";
	$t = 'si';
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
function jqfn_item_issuedate($param) {

	$out = '';

	$result = try_update_item_issuedate($param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=item_issuedate_saved_ok;';
		$out .= '-->';
	} else {
		$out .= '<!--';
		$out .= 'blockid=item_issuedate_saved_fail;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_item_issuedate($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_issuedate.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>