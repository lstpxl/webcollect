<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_reject() {

$str = <<<SCRIPTSTRING

function js_item_reject_get(url, params) {
	
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


function js_item_reject_click(item_id) {

	if (!confirm('Подтвердить правильность?')) {
		return false;
	}

	var url = '/xhr/item_reject.php?i=' + item_id + '';
	
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

					if (BlockName == 'item_reject_saved_ok') {
						var elem = document.getElementById('reject_button');
						if (elem) {
							var respid = elem.value;
							js_item_reject_get('/item/edit.php', {i:respid});
						}
					} else {
						alert('Ошибка');
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
function try_update_item_reject(&$param) {

	//my_write_log('issu 1');

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!can_i_reject_item($param['i'])) return false;
	
	//my_write_log('issu 2');

	$qru = mydb_query("".
		" UPDATE item ".
		" SET item.status = 'R', ".
		" item.time_approved = '".date('Y-m-d H:i:s')."' ".
		" WHERE item.item_id = '".$param['i']."' ". 
		"");
	if (!$qru) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return true;
}


// =============================================================================
function outhtml_item_reject_div($param) {

	$out = '';
	
	$out .= '<div id="item_reject_div">';
	
	if (can_i_reject_item($param['i'])) {
		// value="'.$param['i'].'"
		$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="none" name="reject_button" id="reject_button" value="'.$param['i'].'" style="background-color: #8dd888; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; " onclick=" js_item_reject_click('.$param['i'].'); return false; ">Утвердить</button></div>';
	}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_item_reject($param) {

	$out = '';

	$result = try_update_item_reject(&$param);

	header('Content-Type: text/html; charset=utf-8');
	
	if ($result) {
		$out .= '<!--';
		$out .= 'blockid=item_reject_saved_ok;';
		$out .= '-->';
	} else {
		$out .= '<!--';
		$out .= 'blockid=item_reject_saved_fail;';
		$out .= '-->';
	}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_reject.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_reject($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>