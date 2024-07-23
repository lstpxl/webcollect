<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_inlist_label() {

$str = <<<SCRIPTSTRING


function js_item_inlist_label_query(item_id) {

	var url = '/xhr/item_inlist_label.php?i=' + item_id;
	
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


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_inlist_label_result($param) {

	$out = '';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
		
	//
	
	$out .= '<!--';
	$out .= 'blockid=item_inlist_label_div_i'.$param['i'].';';
	$out .= '-->';
	

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, item.ship_str, item.notes, item.shipmodel_id ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}


	$color = get_item_inlist_color($param['i']);
	

	$out .= '<div style=" width: 188px; overflow: hidden; min-height: 24px; background-color: #ffffff; border: solid 1px #f0f0f0; border-radius: 3px; -moz-border-radius: 3px; ">';
	
		$out .= '<div style=" min-height: 24px; border-bottom: solid 6px #'.$color.'; ">';
	
			$out .= '<div style=" padding: 3px 15px 0px 20px; ">';

				$out .= '<p style=" font-size: 10pt; color: #66737b; width: 140px; overflow: hidden; white-space: nowrap; " title="'.$qr[0]['ship_str'].'" >';
					//$out .= 'Фрунзе';
					$out .= $qr[0]['ship_str'];
				$out .= '</p>';

				if ($qr[0]['shipmodel_str'] != '') {
					$modelfull = get_item_shipmodel_name_full($qr[0]['shipmodel_id'], $qr[0]['shipmodel_str']);
					$out .= '<p style=" font-size: 8.5pt; color: #66737b; width: 140px; overflow: hidden; white-space: nowrap; " title="'.$modelfull.'" >';
					//$out .= 'т.м., накл, г.э.';
						//$out .= $qr[0]['shipmodel_str'];
						$out .= $modelfull;
					$out .= '</p>';
				}

				

				$out .= '<div style=" clear: both; "></div>';
				
			$out .= '</div>';
		
		$out .= '</div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_inlist_label_div($param) {

	$out = '';
	
	$out .= '<div id="item_inlist_label_div_i'.$param['i'].'" style=" " >';
	
	$out .= outhtml_item_inlist_label_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_item_inlist_label($param) {

	$out = '';
	
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$out .= outhtml_item_inlist_label_result($param);

	header('Content-Type: text/html; charset=utf-8');
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_inlist_label.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_inlist_label($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>