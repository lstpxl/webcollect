<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_metal() {

$str = <<<SCRIPTSTRING

var item_metal_str = '';
var TimeToUpdate = 0;

function js_item_metal_query(item_id, metal_id, c) {

	if (c != 'set') c = 'none';
	
	var url = '/xhr/item_metal.php?i=' + item_id + '&metal_id=' + metal_id + '&c=' + c;
	
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


function item_metal_use(item_id, metal_id) {
	
	js_item_metal_query(item_id, metal_id, 'set');
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_metal_input_result($param) {
	
	$out = '';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="shipmodel_input" id="shipmodel_input"  onchange="item_metal_test()" onkeydown="item_metal_test()" onkeyup="item_metal_test()" value="" /></div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_metal_input_div($param) {

	$out = '';
	
	if (isset($param['i'])) {
		$param['i'] = ''.intval($param['i']);
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.metal_id ".
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
		$id = $qr[0]['shipmodel_id'];
		$item_id = $param['i'];
		$color = '#fff4ae'; // yellow
		if ($qr[0]['shipmodel_id'] > 0) {
			$basename = my_get_shipmodel_name($qr[0]['shipmodel_id']);
			if ($basename !== false) {
				if ($basename == trim($str)) {
					$color = '#d6ffd5'; // green
				}
			}
		}
	} else {
		$str = '';
		$id = 0;
		$item_id = 0;
		$color = '#ffffff';
		$basename = '';
	}
	
	$out .= '<div id="item_metal_input_div">';
	
	$out .= '<input type="hidden" name="item_metal_item_id" id="item_metal_item_id" value="'.$item_id.'" />';
	$out .= '<input type="hidden" name="item_metalected_id" id="item_metalected_id" value="'.$id.'" />';
	
	$out .= '<table><tr><td style=" vertical-align: top; ">';
	
	$out .= '<div style=" vertical-align: top; padding-right: 5px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipmodel_input" id="shipmodel_input"  onchange="item_metal_test()" onkeydown="item_metal_test()" onkeyup="item_metal_test()" value="'.$str.'" /></div>';
	
	$out .= '</td><td>';
	
	// добавить в список
	$out .= '<img src="/images/database_add.png" onclick=" alert(\'ZZ!\'); " style=" margin-top: 4px; " alt="добавить в список" />';
	
	$out .= '</td></tr></table>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_metal_result($param) {

	$out = '';
	
	//my_write_log('Metal 1');
	
	//my_write_log('i=('.$param['i'].')');
	
	if (!isset($param['i'])) return false;
	
	//my_write_log('Metal 1a');
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	//my_write_log('Metal 2');
	
	// справочник
	
	$list = mydb_queryarray("".
		" SELECT metal.metal_id, metal.shorttext, metal.longtext ".
		" FROM metal ".
		" ORDER BY metal_id ".
		"");
	if ($list === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//my_write_log('Metal 3');
	
	if (sizeof($list) < 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//my_write_log('Metal 4');
	
	// текущее значение
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.metal_id ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//my_write_log('Metal 5');
	
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//my_write_log('Metal 6');
	
	//
	
	$out .= '<!--';
	$out .= 'blockid=item_metal_div;';
	$out .= '-->';
	
	//
	
	$out .= '<div>';
	
	for ($i = 0; $i < sizeof($list); $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		$color_text = '606060';
		$color_bg = 'f5f5f5';
		$onclick_insert = ' onclick="item_metal_use('.$param['i'].', '.$list[$i]['metal_id'].'); return false; " ';
		if ($list[$i]['metal_id'] == $qr[0]['metal_id']) {
			$color_text = '303030';
			$color_bg = 'adf37b';
			$onclick_insert = ' onclick="return false;" ';
		}
		$out .= '<a href="#" id="item_metal_id'.$qr[$i]['shipmodel_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; float: left; color: #'.$color_text.'; background-color: #'.$color_bg.'; font-size: 11px; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding: 0px 3px 0px 3px; min-width: 30px; text-align: center; " '.$onclick_insert.' >';
		$out .= $list[$i]['shorttext'];
		$out .= '</a>';
	}
	
	$out .= '<div style=" clear: both; "></div>';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_metal_div($param) {

	$out = '';
	
	$out .= '<div id="item_metal_div">';
	
	$out .= outhtml_item_metal_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_metal(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) return false;
	if ($param['c'] != 'set') return false;
	
	if (!isset($param['metal_id'])) return false;
	if (!ctype_digit($param['metal_id'])) return false;
	$param['metal_id'] = ''.intval($param['metal_id']);
	
	// справочник
	
	$qr = mydb_queryarray("".
		" SELECT metal.metal_id, metal.shorttext, metal.longtext ".
		" FROM metal ".
		" WHERE metal_id = '".$param['metal_id']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	// текущее значение
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.metal_id ".
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
	
	if (!can_i_edit_item($param['i'])) return false;
	
	
	$qru = mydb_query("".
		" UPDATE item ".
		" SET item.metal_id = '".$param['metal_id']."' ".
		" WHERE item.item_id = '".$param['i']."' ". 
		"");
	if (!$qru) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_item_metal($param) {

	$out = '';

	try_update_item_metal(&$param);

	$out .= outhtml_item_metal_result($param);

	header('Content-Type: text/html; charset=utf-8');
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_item_metal($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_metal.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>