<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/refresh_downlink.php');

// =============================================================================
function outhtml_script_ship_has_model() {

$str = <<<SCRIPTSTRING


function js_ship_has_model_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('ship_has_model_div');
		if (elem) {
			if (aresp['display'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['reloadform'] == 'string') {
		if (aresp['reloadform'] == 'yes') {
			form_model_reload();
		}
	}
	
	return true;
}


function ship_has_model_switch(item_id, reload_form) {
	if (typeof reload_form === 'undefined') reload_form = false;
	var url = '/xhr/ship_has_model.php?i=' + item_id + '&c=switch';
	if (reload_form) url = url + '&reloadform=y';
	return ajax_my_get_query(url);
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_ship_has_model_result($param) {

	$out = '';

	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id, item.ship_has_model ".
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
	$ship_id = $qr[0]['ship_id'];
	$curvalue = $qr[0]['ship_has_model'];
	
	//
	
	if ($ship_id > 0) {
		
		$qr2 = mydb_queryarray("".
			" SELECT ship.ship_id, ".
			" ship.has_model ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$ship_id."' ".
			"");
		if ($qr2 === false) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr2) != 1) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$curvalue2 = $qr2[0]['has_model'];
		
	} else {
	
		$curvalue2 = $curvalue;
	
	}
	
	//
	
	$out .= '<div>';
		$position = ($curvalue == 'Y');
		$text = ($position?'ДА':'НЕТ');
		$color = ($position?'66737b':'c8c8c8');
		$onclickstr = ' ship_has_model_switch('.$param['i'].', true); return false; ';
		$out .= outhtml_switch_tick($position, $text, $color, $onclickstr);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_ship_has_model_div($param) {

	$out = '';
	
	$out .= '<div id="ship_has_model_div">';
	
		// if ($GLOBALS['user_id'] == 2) {
			$out .= outhtml_ship_has_model_result($param);
		// }
		
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_ship_has_model(&$param) {

	if (!$GLOBALS['is_registered_user']) return false;

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!can_i_edit_item($param['i'])) return false;
	
	if (!isset($param['c'])) return false;
	if ($param['c'] != 'switch') return false;
	
	// 
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id, item.ship_has_model ".
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
	$ship_id = $qr[0]['ship_id'];
	$curvalue = $qr[0]['ship_has_model'];
	
	//
	
	// print 'zzz';
	
	//
	
	if ($ship_id > 0) {
		
		$qr2 = mydb_queryarray("".
			" SELECT ship.ship_id, ".
			" ship.has_model ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$ship_id."' ".
			"");
		if ($qr2 === false) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr2) != 1) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$curvalue2 = $qr2[0]['has_model'];
		
	} else {
	
		$curvalue2 = $curvalue;
	
	}
	
	//
	
	$newvalue = (($curvalue == 'Y')?'N':'Y');
	
	//
	
	// prepared query
	$a = array();
	$a[] = $newvalue;
	$a[] = $param['i'];
	$t = 'si';
	$q = "".
		" UPDATE item ".
		" SET item.ship_has_model = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	//
	
	if ($ship_id > 0) {
	
		if (can_i_uplink_ship_hasmodel($param['i'])) {
	
			// prepared query
			$a = array();
			$a[] = $newvalue;
			$a[] = $ship_id;
			$t = 'si';
			$q = "".
				" UPDATE ship ".
				" SET ship.has_model = ?, ".
				" ship.shipmodel_id = '0' ".
				" WHERE ship.ship_id = ? ". 
				";";
			$qr = mydb_prepquery($q, $t, $a);
			if ($qr === false) {
				out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			// end of prepared query
			
			
			force_downlink_children_recursive('ship', $ship_id);
		
		}
	
	}
	
	$param['reloadform'] = 'yes';
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_ship_has_model($param) {

	if (!$GLOBALS['is_registered_user']) return false;
	
	if (!isset($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	$out = '';
	
	$param['ajp'] = array();
	

	$result = try_update_ship_has_model($param);

	header('Content-Type: text/html; charset=utf-8');

	
	
	if (!isset($param['reloadform'])) $param['reloadform'] = 'no';
	if ($param['reloadform'] == 'yes') {
		$param['ajp']['reloadform'] = 'yes';
		//$param['ajp']['callback'] = 'js_form_iurel_callback';
		//$param['ajp']['elemtoplace'] = 'form_iurel_div';
		//$out .= ajax_encode_prefix($param['ajp']);
		// $out .= outhtml_form_iurel_content($param);
	} 
	//else {

		$param['ajp']['callback'] = 'js_ship_has_model_callback';
		$param['ajp']['elemtoplace'] = 'ship_has_model_div';
		$out .= ajax_encode_prefix($param['ajp']);
		$out .= outhtml_ship_has_model_result($param);
	//}
		
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/ship_has_model.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_ship_has_model($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>