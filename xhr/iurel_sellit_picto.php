<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist.php');


// =============================================================================
function outhtml_script_iurel_sellit_picto() {

$str = <<<SCRIPTSTRING


function js_iurel_sellit_picto_query(item_id, sellit, c) {

	if (c != 'set') c = 'none';
	
	var url = '/xhr/iurel_sellit_picto.php?i=' + item_id + '&sellit=' + sellit + '&c=' + c;
	
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


function iurel_sellit_picto_use(item_id, sellit) {
	
	js_iurel_sellit_picto_query(item_id, sellit, 'set');
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_iurel_sellit_picto_result($param) {

	$out = '';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;

	if (iurel_get_value($param['i'], $GLOBALS['user_id'], 'gotit') == 'N') return '';
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'sellit');
		
	//
	
	$out .= '<!--';
	$out .= 'blockid=iurel_sellit_picto_div_i'.$param['i'].';';
	$out .= '-->';
	
	//
	
	$list = array();
	$list[] = array('v' => 'N', 'text' => 'нет');
	$list[] = array('v' => 'Y', 'text' => 'есть');
	
	//
	
	$switchval = ($curvalue == 'Y')?'N':'Y';
	$pictourl = ($curvalue == 'Y')?'/images/arrows_cyan.png':'/images/arrows_gray.png';

	$out .= '<div style=" width: 16px; height: 16px; overflow: hidden; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\''.$pictourl.'\');" onclick="iurel_sellit_picto_use('.$param['i'].', \''.$switchval.'\'); return false; " title="Меняю / продаю" >';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_iurel_sellit_picto_div($param) {

	$out = '';
	
	$out .= '<div id="iurel_sellit_picto_div_i'.$param['i'].'" style=" ">';
	
	$out .= outhtml_iurel_sellit_picto_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_iurel_sellit_picto(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'sellit');
	if ($curvalue === false) return false;
	
	if (!isset($param['sellit'])) return false;
	
	
	if (!in_array($param['sellit'], array('Y', 'N'))) return false;

	if (iurel_get_value($param['i'], $GLOBALS['user_id'], 'gotit') == 'N') return false;

	if ($param['sellit'] == 'N') iurel_set_value($param['i'], $GLOBALS['user_id'], 'sellit', 'N');
	
	$result = iurel_set_value($param['i'], $GLOBALS['user_id'], 'sellit', $param['sellit']);
	
	// update_iurel_searchstring($param['i'], $GLOBALS['user_id']);
	
	return true;
}


// =============================================================================
function jqfn_iurel_sellit_picto($param) {

	$out = '';
	
	if (!$GLOBALS['is_registered_user']) return false;
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$result = try_update_iurel_sellit_picto($param);

	/*
	$out .= outhtml_iurel_sellit_picto_result($param);

	header('Content-Type: text/html; charset=utf-8');
		
	print $out;

	return true;
	*/

	//return jqfn_item_inlist_label($param);
	return jqfn_item_inlist($param);
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/iurel_sellit_picto.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_iurel_sellit_picto($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>