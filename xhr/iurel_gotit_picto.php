<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist.php');


// =============================================================================
function outhtml_script_iurel_gotit_picto() {

$str = <<<SCRIPTSTRING


function js_iurel_gotit_picto_query(item_id, gotit, c) {

	if (c != 'set') c = 'none';
	
	var url = '/xhr/iurel_gotit_picto.php?i=' + item_id + '&gotit=' + gotit + '&c=' + c;
	
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
  
	// js_item_inlist_query(item_id);
}


function iurel_gotit_picto_use(item_id, gotit) {
	
	js_iurel_gotit_picto_query(item_id, gotit, 'set');
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_iurel_gotit_picto_result($param) {

	$out = '';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'gotit');
		
	//
	
	$out .= '<!--';
	$out .= 'blockid=iurel_gotit_picto_div_i'.$param['i'].';';
	$out .= '-->';
	
	//
	
	$list = array();
	$list[] = array('v' => 'N', 'text' => 'нет');
	$list[] = array('v' => 'Y', 'text' => 'есть');
	
	//
	
	$switchval = ($curvalue == 'Y')?'N':'Y';
	$pictourl = ($curvalue == 'Y')?'/images/star_yellow.png':'/images/star_gray.png';

	$out .= '<div style=" width: 16px; height: 16px; overflow: hidden; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\''.$pictourl.'\');" onclick="iurel_gotit_picto_use('.$param['i'].', \''.$switchval.'\'); return false; " title="У меня есть">';
	// $out .= '<img src="/images/spacer.gif" style=" width: 16px; height: 16px; " title="У меня есть" />';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_iurel_gotit_picto_div($param) {

	$out = '';
	
	$out .= '<div id="iurel_gotit_picto_div_i'.$param['i'].'" style=" width: 16px; height: 16px; overflow: hidden; ">';
	
	$out .= outhtml_iurel_gotit_picto_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_iurel_gotit_picto(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'gotit');
	if ($curvalue === false) return false;
	
	if (!isset($param['gotit'])) return false;
	
	
	if (!in_array($param['gotit'], array('Y', 'N'))) return false;
	
	$result = iurel_set_value($param['i'], $GLOBALS['user_id'], 'gotit', $param['gotit']);
	if ($param['gotit'] == 'N') iurel_set_value($param['i'], $GLOBALS['user_id'], 'sellit', 'N');
	if ($param['gotit'] == 'Y') iurel_set_value($param['i'], $GLOBALS['user_id'], 'wantit', 'N');
	
	// update_iurel_searchstring($param['i'], $GLOBALS['user_id']);
	
	return true;
}


// =============================================================================
function jqfn_iurel_gotit_picto($param) {

	$out = '';
	
	if (!$GLOBALS['is_registered_user']) return false;
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$result = try_update_iurel_gotit_picto($param);

	/*
	$out .= outhtml_iurel_gotit_picto_result($param);

	header('Content-Type: text/html; charset=utf-8');
		
	print $out;

	return true;
	*/

	//return jqfn_item_inlist_label($param);
	
	return jqfn_item_inlist($param);
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/iurel_gotit_picto.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_iurel_gotit_picto($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>