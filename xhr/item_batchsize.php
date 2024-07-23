<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_batchsize() {

$str = <<<SCRIPTSTRING

var item_batchsize_str = '';
var TimeToUpdate = 0;

function js_item_batchsize_query() {
	
	var elem = document.getElementById('item_batchsize_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('item_batchsize_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/item_batchsize.php?i=' + item_id + '&str=' + str + '';
	
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
			  if (BlockName == 'item_batchsize_saved_ok') {
				var elem = document.getElementById('item_batchsize_input');
				if (elem) {
					elem.style.backgroundColor = '#d6ffd5';
				}
			  } else {
				var elem = document.getElementById('item_batchsize_input');
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


function item_batchsize_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('item_batchsize_input').value;
	if (item_batchsize_str != str) {
		item_batchsize_str = str;
		js_item_batchsize_query();
	}
}

function item_batchsize_test() {
	
	var elem = document.getElementById('item_batchsize_input');
	if (!elem) return false;
	var str = elem.value;
	
	if (item_batchsize_str != str) {
		elem.style.backgroundColor = '#fff4ae';
		//
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('item_batchsize_test_sub()', 800);
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_batchsize_input_div($param) {

	

	$out = '';
	
	if (isset($param['i'])) {
	
		$param['i'] = ''.intval($param['i']);
	
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.batchsize ".
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
		$str = $qr[0]['batchsize'];
		$item_id = $param['i'];
		// $color = '#fff4ae'; // yellow
		$color = '#d6ffd5'; // green
	} else {
		$str = '';
		$item_id = 0;
		$color = '#ffffff';
	}
	
	$out .= '<div id="item_batchsize_input_div">';
	
	$out .= '<input type="hidden" name="item_batchsize_item_id" id="item_batchsize_item_id" value="'.$item_id.'" />';
	
	if ($str == '0') $str = '';
	
	$out .= '<div style=" vertical-align: top; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 20px; text-align: right; " size="6" name="item_batchsize_input" id="item_batchsize_input"  onchange="item_batchsize_test()" onkeydown="item_batchsize_test()" onkeyup="item_batchsize_test()" value="'.$str.'" /></div>';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_item_batchsize_div($param) {

	$out = '';
	
	$out .= '<div id="item_batchsize_div">';
	
	$out .= outhtml_item_batchsize_input_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_batchsize(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.batchsize ".
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
	
	if (!isset($param['str'])) return false;
	
	$param['str'] = trim($param['str']);
	if (!ctype_digit($param['str'])) return false;
	$param['str'] = ''.intval($param['str']);

	// prepared query
	$a = array();
	$a[] = $param['str'];
	$a[] = $param['i'];
	$t = 'si';
	$q = "".
		" UPDATE item ".
		" SET item.batchsize = ? ".
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
function jqfn_item_batchsize($param) {

	$out = '';

	$result = try_update_item_batchsize(&$param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=item_batchsize_saved_ok;';
		$out .= '-->';
	} else {
		$out .= '<!--';
		$out .= 'blockid=item_batchsize_saved_fail;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_item_batchsize($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_batchsize.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>