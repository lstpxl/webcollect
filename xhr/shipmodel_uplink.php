<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_classification.php');


// =============================================================================
function outhtml_script_shipmodel_uplink() {

$str = <<<SCRIPTSTRING

function js_shipmodel_uplink_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('shipmodel_uplink_div');
		if (elem) {
			if (aresp['display'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	if (typeof aresp['color'] == 'string') {
		var elem = document.getElementById('shipmodel_input');
		if (elem) {
			elem.style.backgroundColor = '#' + aresp['color'];
		}
	}
	
	return true;
}


function js_shipmodel_uplink_query(item_id, c) {
	
	var url = '/xhr/shipmodel_uplink.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function shipmodel_uplink_click() {

	// alert('xxx!');
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_shipmodel_uplink_query(item_id, 'uplink');
	}
}


function shipmodel_uplink_refresh() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_shipmodel_uplink_query(item_id, '');
	}
}

function shipmodel_uplink_hide() {
	var elem = document.getElementById('shipmodel_uplink_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function shipmodel_uplink_show() {
	var elem = document.getElementById('shipmodel_uplink_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_shipmodel_uplink_result($param) {

	$out = '';
	
	// if (!am_i_admin_or_moderator()) return '';
	if (!can_i_uplink_shipmodel($param['i'])) return '';
	
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_str, ".
		" item.shipmodel_id, item.shipmodel_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	//
	
	// beautify here
	
	$qr[0]['shipmodel_str'] = trim($qr[0]['shipmodel_str']);
	
	
	// if ($qr[0]['shipmodel_str'] == '') return '';
	
	//
	
	if ($qr[0]['ship_id'] > 0) {
	
		$qrs = mydb_queryarray("".
			" SELECT ship.ship_id, ".
			" ship.shipmodel_id ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ".
			"");
		if ($qrs === false) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qrs) != 1) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
	
	}
	
	//
	
	
	//
	
	// 3f6b86
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_link.png\'); " onclick=" shipmodel_uplink_click(); return false; " title=" глобально изменить проект корабля ">';
	$out .= '</div>';

	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipmodel_uplink_div($param) {

	$out = '';
	
	// $out .= outhtml_script_shipmodel_uplink();
	
	// $out .= '<input type="hidden" id="shipmodel_uplink_item_id" value="'.$param['i'].'" />';
	
	if (can_i_shipmodel_uplink($param)) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="shipmodel_uplink_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_shipmodel_uplink_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}



// =============================================================================
function can_i_shipmodel_uplink($param) {

	$param['i'] = ''.intval($param['i']);
	
	if (!can_i_uplink_shipmodel($param['i'])) return false;

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_str, ".
		" item.shipmodel_id, item.shipmodel_str, ".
		" item.natoc_id, item.natoc_str, ".
		" item.shipmodelclass_id, item.shipmodelclass_str ".
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
	
	if ($qr[0]['ship_id'] == 0) return false;

	if ($qr[0]['shipmodel_id'] == 0) return false;
	
	if ($qr[0]['shipmodel_id'] == my_get_ship_model_id($qr[0]['ship_id'])) return false;
	
	return true;
}


// =============================================================================
function try_shipmodel_uplink(&$param) {

	if (!am_i_admin_or_moderator()) return false;
	
	if ($param['c'] != 'uplink') return false;
	
	if (!can_i_shipmodel_uplink($param)) return false;
	
	$param['i'] = ''.intval($param['i']);
	
	//

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_str, ".
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
	
	$qru = mydb_query("".
		" UPDATE ship ".
		" SET ship.shipmodel_id = '".$qr[0]['shipmodel_id']."' ".
		" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ". 
		"");
	if (!$qru) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	my_elemtree_rebuild_treeindex_local('ship', $qr[0]['ship_id']);
	
	$param['ajp']['display'] = 'no';
	
	update_item_searchstring($param['i']);
	
	$param['ajp']['elemtoplace'] = 'item_classification_div';
	$param['html'] = outhtml_item_classification_content(array('i' => $param['i']));
	
	return true;
}


// =============================================================================
function jqfn_shipmodel_uplink($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['ajp'] = array();
	$param['html'] = '';
	// $param['ajp']['elemtoplace'] = 'shipmodel_uplink_div';
	$param['ajp']['callback'] = 'js_shipmodel_uplink_callback';
	// $param['ajp']['color'] = 'fff4ae';
	$param['ajp']['display'] = 'yes';
	// $param['ajp']['enable'] = 'enabled';

	// try_update_shipmodel_uplink(&$param);

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'uplink') {
		try_shipmodel_uplink(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	if ($param['html'] == '') {
		$param['html'] = outhtml_shipmodel_uplink_result($param);
	}
	
	$prefixarr = array();
	
	
	if (can_i_shipmodel_uplink($param)) {
		$param['ajp']['display'] = 'yes';
	} else {
		$param['ajp']['display'] = 'no';
	}
	
	$out .= ajax_encode_prefix($param['ajp']);
	
	$out .= $param['html'];

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/shipmodel_uplink.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_shipmodel_uplink($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>