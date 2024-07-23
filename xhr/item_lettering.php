<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_lettering() {

$str = <<<SCRIPTSTRING

var item_lettering_str = '';
var TimeToUpdate = 0;

function js_item_lettering_query() {
	
	var elem = document.getElementById('item_lettering_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('item_lettering_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/item_lettering.php?i=' + item_id + '&str=' + str;
	
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
			  if (BlockName == 'item_lettering_saved_ok') {
				var elem = document.getElementById('item_lettering_input');
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


function item_lettering_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('item_lettering_input').value;
	if (item_lettering_str != str) {
		item_lettering_str = str;
		js_item_lettering_query();
	}
}

function item_lettering_test() {
	
	var elem = document.getElementById('item_lettering_input');
	if (!elem) return false;
	var str = elem.value;

	if (item_lettering_str != str) {
		elem.style.backgroundColor = '#fff4ae';
		//
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('item_lettering_test_sub()', 800);
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_lettering_input_div($param) {

	$out = '';
	
	if (!isset($param['i'])) return false;
	
	
	$param['i'] = ''.intval($param['i']);
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.lettering ".
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
	
	$str = $qr[0]['lettering'];
	$item_id = $param['i'];
	// $color = '#fff4ae'; // yellow
	$color = '#d6ffd5'; // green


	
	$out .= '<div id="item_lettering_input_div">';
	
	$out .= '<input type="hidden" name="item_lettering_item_id" id="item_lettering_item_id" value="'.$item_id.'" />';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input size="40" class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 510px; " name="item_lettering_input" id="item_lettering_input"  onchange="item_lettering_test()" onkeydown="item_lettering_test()" onkeyup="item_lettering_test()" value="'.htmlspecialchars($str, ENT_QUOTES).'" /></div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_item_lettering_div($param) {

	$out = '';
	
	$out .= '<div id="item_lettering_div">';
	
	$out .= outhtml_item_lettering_input_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_lettering(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.lettering ".
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
	
	//
	
	$str = trim($param['str']);
	
	$a = explode(' ', $str);
	for ($i = 0; $i < sizeof($a); $i++) {
		if ($a[$i] == 'х') $a[$i] = 'x';
		if ($a[$i] == 'хх') $a[$i] = 'xx';
		if ($a[$i] == 'ххх') $a[$i] = 'xxx';
		if ($a[$i] == 'хххх') $a[$i] = 'xxxx';
		
		if ($a[$i] == 'хv') $a[$i] = 'xv';
		if ($a[$i] == 'ххv') $a[$i] = 'xxv';
		if ($a[$i] == 'хххv') $a[$i] = 'xxxv';
	}
	$str = implode(" ", $a);
	
	// ----
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.lettering = ? ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$a[] = $str;
	$t = 's';
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
function jqfn_item_lettering($param) {

	$out = '';

	$result = try_update_item_lettering(&$param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=item_lettering_saved_ok;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_item_lettering($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_lettering.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>