<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');


// =============================================================================
function outhtml_script_class_uplink() {

$str = <<<SCRIPTSTRING

function js_class_uplink_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('class_uplink_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	return true;
}


function js_class_uplink_query(item_id, c) {
	
	var url = '/xhr/class_uplink.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function class_uplink_click() {
	
	var elem = document.getElementById('class_uplink_item_id');
	if (elem) {
		var item_id = elem.value;
		js_class_uplink_query(item_id, 'uplink');
	}
}


function class_uplink_refresh() {
	
	var elem = document.getElementById('class_uplink_item_id');
	if (elem) {
		var item_id = elem.value;
		js_class_uplink_query(item_id, '');
	}
}

function class_uplink_hide() {
	var elem = document.getElementById('class_uplink_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function class_uplink_show() {
	var elem = document.getElementById('class_uplink_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_class_uplink_result($param) {

	$out = '';
	
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_link.png\'); " onclick=" class_uplink_click(); return false; " title=" глобально изменить классификацию проекта">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_class_uplink_process(&$param) {

	if (!am_i_admin_or_moderator()) return $out;
	
	//$out .= 'z1';

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'class_uplink_div';
		$param['html'] = outhtml_class_uplink_result($param);
	}
	
	$param['ajp']['color'] = 'yellow';
	$param['ajp']['show'] = 'hide';
	
	//
	
	//
	
	if (can_i_class_uplink($param)) {
	
		$param['ajp']['show'] = 'show';
	
	}
		
	return true;
}


// =============================================================================
function outhtml_class_uplink_div($param) {

	$out = '';
	
	$out .= outhtml_script_class_uplink();
	
	$out .= '<input type="hidden" id="class_uplink_item_id" value="'.$param['i'].'" />';
	
	if (can_i_class_uplink($param)) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="class_uplink_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_class_uplink_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function can_i_class_uplink($param) {

	$param['i'] = ''.intval($param['i']);
	
	

	//print 'z1';

	if (!can_i_uplink_shipmodelclass($param['i'])) return false;
	
	//print 'z2';

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_has_model, ".
		" item.shipmodelclass_id, item.shipmodelclass_str, ".
		" item.shipmodel_id, item.shipmodel_str ".
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
	
	//print 'z3';
	
	if ($qr[0]['shipmodelclass_id'] < 1) return false;
	
	//
	
	if ($qr[0]['ship_id'] > 0) {
	
		$qr2 = mydb_queryarray("".
			" SELECT ship.ship_id, ".
			" ship.has_model ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ".
			"");
		if ($qr2 === false) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr2) != 1) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$has_model = ($qr2[0]['has_model'] == 'Y');
		
		if (!$has_model) return true;
		
	} else {
		
		return false;
		
	}
	
	if ($qr[0]['shipmodel_id'] < 1) return false;
	

	
	$stored_class_id = my_get_shipmodel_class($qr[0]['shipmodel_id']);
	if ($stored_class_id == $qr[0]['shipmodelclass_id']) return false;
	

	
	
	//print 'z4';

	
	
	//
	/*
	$stored_class_str = my_get_shipclass_name($qr[0]['shipmodelclass_id']);
	if ($qr[0]['shipmodelclass_str'] != $stored_class_str) return false;
	
	//print 'z5';
	
	$stored_model_str = my_get_shipmodel_name($qr[0]['shipmodel_id']);
	if ($qr[0]['shipmodel_str'] != $stored_model_str) return false;
	
	//print 'z6';
	
	//
	
	$stored_class_id = my_get_shipmodel_class($qr[0]['shipmodel_id']);
	if ($qr[0]['shipmodelclass_id'] == $stored_class_id) return false;
	
	//print 'z7';
	*/
		
	//
	
	return true;
}


// =============================================================================
function try_class_uplink(&$param) {

	$param['i'] = ''.intval($param['i']);

	if (!can_i_class_uplink($param)) return false;

	//
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_has_model, ".
		" item.shipmodelclass_id, item.shipmodelclass_str, ".
		" item.shipmodel_id, item.shipmodel_str ".
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
	
	//
	
	if ($qr[0]['ship_id'] > 0) {
	
		$qr2 = mydb_queryarray("".
			" SELECT ship.ship_id, ".
			" ship.has_model ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ".
			"");
		if ($qr2 === false) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr2) != 1) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$has_model = ($qr2[0]['has_model'] == 'Y');
		
		if ($has_model) {
		
			$qru = mydb_query("".
				" UPDATE shipmodel ".
				" SET shipmodel.shipmodelclass_id = '".$qr[0]['shipmodelclass_id']."' ".
				" WHERE shipmodel.shipmodel_id = '".$qr[0]['shipmodel_id']."' ". 
				"");
			if (!$qru) {
				out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			my_elemtree_rebuild_treeindex_local('shipmodel', $qr[0]['shipmodel_id']);
		
		} else {
		
			$qru = mydb_query("".
				" UPDATE ship ".
				" SET ship.shipmodelclass_id = '".$qr[0]['shipmodelclass_id']."' ".
				" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ". 
				"");
			if (!$qru) {
				out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			my_elemtree_rebuild_treeindex_local('ship', $qr[0]['ship_id']);
		
		}
		
	} else {
		
		return false;
		
	}
	
	/*
	print "".
		" UPDATE shipmodel ".
		" SET shipmodel.shipmodelclass_id = '".$qr[0]['shipmodelclass_id']."' ".
		" WHERE shipmodel.shipmodel_id = '".$qr[0]['shipmodel_id']."' ". 
		"";
	*/
	
	
	
	//
	
	// $param['ajp']['input_color'] = 'd6ffd5';
	// $param['ajp']['show'] = 'hide';
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_class_uplink($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_class_uplink_callback';
	$param['ajp']['show'] = 'show';

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'uplink') {
		try_class_uplink(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_class_uplink_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/class_uplink.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_class_uplink($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>