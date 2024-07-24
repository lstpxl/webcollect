<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

// =============================================================================
function outhtml_script_item_itemsettitle() {

$str = <<<SCRIPTSTRING


function js_item_itemsettitle_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('item_itemsettitle_div');
		if (elem) {
			if (aresp['display'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	return true;
}


function item_itemsettitle_switch(item_id, reload_form) {
	if (typeof reload_form === 'undefined') reload_form = false;
	var url = '/xhr/item_itemsettitle.php?i=' + item_id + '&c=switch';
	if (reload_form) url = url + '&reloadform=y';
	return ajax_my_get_query(url);
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_itemsettitle_result($param) {

	$out = '';

	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.is_itemset_title ".
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
	$curvalue = $qr[0]['is_itemset_title'];
	
	//
	
	$out .= '<div>';
		$position = ($curvalue == 'Y');
		$text = ($position?'ДА':'НЕТ');
		$color = ($position?'66737b':'c8c8c8');
		$onclickstr = ' item_itemsettitle_switch('.$param['i'].', true); return false; ';
		$out .= outhtml_switch_tick($position, $text, $color, $onclickstr);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_itemsettitle_div($param) {

	$out = '';
	
	$out .= '<div id="item_itemsettitle_div">';
		$out .= outhtml_item_itemsettitle_result($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_item_itemsettitle(&$param) {

	if (!$GLOBALS['is_registered_user']) return false;

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!can_i_edit_item($param['i'])) return false;
	
	if (!isset($param['c'])) return false;
	if ($param['c'] != 'switch') return false;
	
	// is_itemset_title
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.is_itemset_title ".
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
	$curvalue = $qr[0]['is_itemset_title'];
	
	$newvalue = (($curvalue == 'Y')?'N':'Y');
	
	// prepared query
	$a = array();
	$a[] = $newvalue;
	$a[] = $param['i'];
	$t = 'si';
	$q = "".
		" UPDATE item ".
		" SET item.is_itemset_title = ? ".
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
function jqfn_item_itemsettitle($param) {

	if (!$GLOBALS['is_registered_user']) return false;
	
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	$out = '';
	
	$param['ajp'] = array();
	

	$result = try_update_item_itemsettitle($param);

	header('Content-Type: text/html; charset=utf-8');

	
	/*
	if (!isset($param['reloadform'])) $param['reloadform'] = 'n';
	if ($param['reloadform'] == 'y') {
	
		$param['ajp']['callback'] = 'js_form_iurel_callback';
		$param['ajp']['elemtoplace'] = 'form_iurel_div';
		$out .= ajax_encode_prefix($param['ajp']);
		// $out .= outhtml_form_iurel_content($param);
	} else {
	*/
		$param['ajp']['callback'] = 'js_item_itemsettitle_callback';
		$param['ajp']['elemtoplace'] = 'item_itemsettitle_div';
		$out .= ajax_encode_prefix($param['ajp']);
		$out .= outhtml_item_itemsettitle_result($param);
	//}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_itemsettitle.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_itemsettitle($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>