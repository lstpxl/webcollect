<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_iurel_personalnote() {

$str = <<<SCRIPTSTRING

var iurel_personalnote_str = '';
var iurel_personalnote_timetoupdate = 0;

function js_iurel_personalnote_query() {
	
	var elem = document.getElementById('iurel_personalnote_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('iurel_personalnote_item_id');
	if (elem) {
		var item_id = elem.value;
	} else {
		var item_id = '0';
	}
	
	var url = '/xhr/iurel_personalnote.php?i=' + item_id + '&str=' + str;
	
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
			  if (BlockName == 'iurel_personalnote_saved_ok') {
				var elem = document.getElementById('iurel_personalnote_input');
				if (elem) {
					elem.style.backgroundColor = '#d9e1e7';
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


function iurel_personalnote_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < iurel_personalnote_timetoupdate) return true;

	var str = document.getElementById('iurel_personalnote_input').value;
	if (iurel_personalnote_str != str) {
		iurel_personalnote_str = str;
		js_iurel_personalnote_query();
	}
}

function iurel_personalnote_test() {
	
	var elem = document.getElementById('iurel_personalnote_input');
	if (!elem) return false;
	var str = elem.value;

	if (iurel_personalnote_str != str) {
		elem.style.backgroundColor = '#ffd7d7';
		//
		iurel_personalnote_timetoupdate = new Date().getTime() + 650;
		setTimeout('iurel_personalnote_test_sub()', 800);
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_iurel_personalnote_input_div($param) {

	$out = '';
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'personalnote');
	$str = $curvalue;
	$color = '#d6ffd5'; // green
	$color = '#d9e1e7'; // light blue
	
		
	$out .= '<div id="iurel_personalnote_input_div">';
	
	$out .= '<input type="hidden" name="iurel_personalnote_item_id" id="iurel_personalnote_item_id" value="'.$param['i'].'" />';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><textarea cols="30" rows="2" class="hoverlightblueborder" style=" display: block; text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 335px; " name="iurel_personalnote_input" id="iurel_personalnote_input"  onchange="iurel_personalnote_test()" onkeydown="iurel_personalnote_test()" onkeyup="iurel_personalnote_test()" />'.$str.'</textarea></div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_iurel_personalnote_div($param) {

	if (!$GLOBALS['is_registered_user']) return false;
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$out .= '<div id="iurel_personalnote_div">';
	
	$out .= outhtml_iurel_personalnote_input_div($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_iurel_personalnote(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'personalnote');
	if ($curvalue === false) return false;
	
	$param['str'] = trim($param['str']);
		
	$result = iurel_set_value($param['i'], $GLOBALS['user_id'], 'personalnote', $param['str']);
		
	update_iurel_searchstring($param['i'], $GLOBALS['user_id']);
	
	return true;
}


// =============================================================================
function jqfn_iurel_personalnote($param) {

	$out = '';
	
	if (!$GLOBALS['is_registered_user']) return false;
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$result = try_update_iurel_personalnote($param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=iurel_personalnote_saved_ok;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_iurel_personalnote($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/iurel_personalnote.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>