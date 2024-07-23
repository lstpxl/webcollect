<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/treeindex.php');


// =============================================================================
function outhtml_script_manual_sort_item() {

$str = <<<SCRIPTSTRING


function js_manual_sort_item_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('manual_sort_item_div');
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


function manual_sort_item_switch(item_id, reload_form) {
	if (typeof reload_form === 'undefined') reload_form = false;
	var url = '/xhr/manual_sort_item.php?i=' + item_id + '&c=switch';
	if (reload_form) url = url + '&reloadform=y';
	return ajax_my_get_query(url);
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_manual_sort_item_result($param) {

	$out = '';

	$param['i'] = ''.intval($param['i']);
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'wantit');
	
	$out .= '<div>';
		$position = ($curvalue == 'Y');
		$text = ($position?'ДА':'НЕТ');
		$color = ($position?'fab1fd':'c8c8c8');
		$onclickstr = ' manual_sort_item_switch('.$param['i'].', true); return false; ';
		$out .= outhtml_switch_i1($position, $text, $color, $onclickstr);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_manual_sort_item_div($param) {

	$out = '';
	
	$out .= '<div id="manual_sort_item_div">';
		$out .= outhtml_manual_sort_item_result($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function set_item_ti_subsort($item_id, $ti_subsort) {

	$item_id = ''.intval($item_id);

	// prepared query
	$a = array();
	$a[] = $ti_subsort;
	$a[] = $item_id;
	$q = "".
		" UPDATE item ".
		" SET item.ti_subsort = ? ".
		" WHERE item.item_id = ? ".
		";";
	$t = 'si';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	return true;
}


// =============================================================================
function try_update_manual_sort_item_placeafter(&$param) {

	if (!$GLOBALS['is_registered_user']) return false;

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['after'])) return false;
	if (!ctype_digit($param['after'])) return false;
	$param['after'] = ''.intval($param['after']);
	if ($param['after'] > 0) {
		if (my_get_item_status($param['after']) === false) return false;
	}
	
	if (!isset($param['c'])) return false;
	if ($param['c'] != 'placeafter') return false;
	
	//
	
	$qr = mydb_queryarray("".
		" SELECT item.ti_parent ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		return false;
	}
	if ($qr[0]['ti_parent'] == '') return false;
	
	$ti_parent = $qr[0]['ti_parent'];
	
	//
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id ".
		" FROM item ".
		" WHERE item.ti_parent = '".$ti_parent."' ".
		" ORDER BY item.ti_subsort, item.sortfield_c, item.sortfield_a ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) < 2) {
		return false;
	}
	
	//
	
	$idxi = false;
	$idxa = false;
	for ($i = 0; $i < sizeof($qr); $i++) {
		if ($qr[$i]['item_id'] == $param['i']) $idxi = $i;
		if ($param['after'] > 0) {
			if ($qr[$i]['item_id'] == $param['after']) $idxa = $i;
		}
		set_item_ti_subsort($qr[$i]['item_id'], (($i + 1) * 2));
	}
	
	if ($idxi === false) return false;
	if (($param['after'] > 0) && ($idxa === false)) return false;
	
	if ($param['after'] > 0) {
		set_item_ti_subsort($param['i'], ((($idxa + 1) * 2 + 1)));
	} else {
		set_item_ti_subsort($param['i'], 1);
	}
	
	$result = treeindex_rebuld_item_group($ti_parent);
	
	// set_item_ti_subsort($item_id, $ti_subsort)
	
	//
	
	$param['ajp']['result'] = 'ok';
	
	return true;
}


// =============================================================================
function jqfn_manual_sort_item($param) {

	if (!$GLOBALS['is_registered_user']) return false;
	
	if (!am_i_admin()) return false;
	
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	$out = '';
	
	$param['ajp'] = array();
	

	//$result = try_update_manual_sort_item(&$param);

	header('Content-Type: text/html; charset=utf-8');

	
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'placeafter') {
		$param['ajp']['callback'] = 'js_sorter_test_callback';
		// $param['ajp']['elemtoplace'] = 'form_iurel_div';
		$result = try_update_manual_sort_item_placeafter(&$param);
		$out .= ajax_encode_prefix($param['ajp']);
		//$out .= outhtml_form_iurel_content($param);
	}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/manual_sort_item.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_manual_sort_item($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>