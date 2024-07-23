<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_undelete() {

$str = <<<SCRIPTSTRING

var undelete_item_id = 0;

function js_item_undelete_get(url, params) {
	
	var temp = document.createElement('form');
	temp.action = url;
	temp.method = 'GET';
	temp.style.display = 'none';
	for (var x in params) {
		var opt = document.createElement('textarea');
		opt.name = x;
		opt.value = params[x];
		temp.appendChild(opt);
	}
	document.body.appendChild(temp);
	temp.submit();
	return temp;
}


function js_item_undelete_click(item_id) {


	var url = '/xhr/item_undelete.php?i=' + item_id + '';
	undelete_item_id = item_id;
	
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
          var BlockNameIndex = (String(response).indexOf("blockid=") + 8);
          var BlockNameEnd = (String(response).indexOf(";", BlockNameIndex));
          var IndexClean = (String(response).indexOf("-->") + 3);
          var BlockName = String(response).substring(BlockNameIndex, BlockNameEnd);
          var htmlclean = String(response).substring(IndexClean, String(response).length);
		  
			if (BlockName == 'item_undelete_saved_ok') {
				js_item_undelete_get('/item/view.php', {i:undelete_item_id});
			} else {
				alert('Ошибка удаления');
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
function outhtml_item_undelete_div($item_id) {

	$out = '';
	
	$out .= '<div id="item_undelete_div">';
	
	if (can_i_undelete_item($item_id)) {
		$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="none" name="undelete_button" style="background-color: #90b0e0; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #534026; padding: 2px 12px 3px 12px; min-width: 130px; " onclick=" js_item_undelete_click(\''.$item_id.'\'); return false; ">Восстановить</button></div>';
	}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_undelete(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!can_i_undelete_item($param['i'])) return false;
	
	
	$qru = mydb_query("".
		" UPDATE item ".
		" SET item.status = 'W' ".
		" WHERE item.item_id = '".$param['i']."' ". 
		"");
	if (!$qru) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return true;
}


// =============================================================================
function jqfn_item_undelete($param) {

	$out = '';

	$result = try_update_item_undelete(&$param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=item_undelete_saved_ok;';
		$out .= '-->';
	} else {
		$out .= '<!--';
		$out .= 'blockid=item_undelete_saved_fail;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_undelete.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_undelete($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>