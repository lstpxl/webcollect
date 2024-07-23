<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function on_shipmodeltree_del_error($param) {
	
	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=shipmodeltree_del_result;';
	$out .= '-->';
	$out .= 'failure';
	
	header('Content-Type: text/html; charset=utf-8');
	
	print $out;

	return true;
}


// =============================================================================
function on_shipmodeltree_del_ok($param) {
	
	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=shipmodeltree_del_result;';
	$out .= '-->';
	$out .= 'ok';
	
	header('Content-Type: text/html; charset=utf-8');
	
	print $out;

	return true;
}


/*
// =============================================================================
function my_get_shipclass_elem_children_count($type, $id) {

	$d = array();
	$d['class'] = 0;
	$d['model'] = 0;
	$d['ship'] = 0;
	$d['item'] = 0;
	
	$id = ''.intval($id);

	//
	
	if ($type == 'shipmodelclass') {
	
		
	
		$q = "SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.parent_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$d['class'] += sizeof($qr);
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			
			$z = my_get_shipclass_elem_children_count('shipmodelclass', $qr[$i]['shipmodelclass_id']);
			$d['class'] += $z['class'];
			$d['model'] += $z['model'];
			$d['ship'] += $z['ship'];
			$d['item'] += $z['item'];
		}
		
		//
		
		$q = " SELECT shipmodel.shipmodel_id ".
			" FROM shipmodel ".
			" WHERE shipmodelclass_id = '".$id."' ";
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$d['model'] += sizeof($qr);
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$z = my_get_shipclass_elem_children_count('shipmodel', $qr[$i]['shipmodel_id']);
			$d['model'] += $z['model'];
			$d['ship'] += $z['ship'];
			$d['item'] += $z['item'];
		}
		
		//
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodelclass_id = '".$id."' ";
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$d['item'] += sizeof($qr);
	
	}
	
	//
	
	
	if ($type == 'shipmodel') {
	
		$q = "SELECT ship.ship_id ".
			" FROM ship ".
			" WHERE ship.shipmodel_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$d['ship'] += 1;
			$z = my_get_shipclass_elem_children_count('ship', $qr[$i]['ship_id']);
			$d['item'] += $z['item'];
		}
		
		//
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodel_id = '".$id."' ";
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$d['item'] += sizeof($qr);
			
	}
	
	//
	
	if ($type == 'ship') {
	
		$q = "SELECT item.item_id ".
			" FROM item ".
			" WHERE item.ship_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$d['item'] += sizeof($qr);
		
	}
	
	//
	
	return $d;
}
*/


// =============================================================================
function my_get_shipclass_elem_children_clean($type, $id) {

	$id = ''.intval($id);

	if ($type == 'shipmodelclass') {
	
		$q = "SELECT shipmodelclass.shipmodelclass_id, ".
			" shipmodelclass.parent_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			return false;
		}
		if (sizeof($qr) != 1) {
			return false;
		}
		$parent_id = $qr[$i]['parent_id'];
		
		//
		
		$qru = mydb_query("".
			" UPDATE shipmodelclass ".
			" SET shipmodelclass.parent_id = '".$parent_id."' ".
			" WHERE shipmodelclass.parent_id = '".$id."' ". 
			"");
		if (!$qru) {
			return false;
		}
		
		$qru = mydb_query("".
			" UPDATE shipmodel ".
			" SET shipmodel.shipmodelclass_id = '".$parent_id."' ".
			" WHERE shipmodel.shipmodelclass_id = '".$id."' ". 
			"");
		if (!$qru) {
			return false;
		}
		
		$qru = mydb_query("".
			" UPDATE item ".
			" SET item.shipmodelclass_id = '".$parent_id."' ".
			" WHERE item.shipmodelclass_id = '".$id."' ". 
			"");
		if (!$qru) {
			return false;
		}
		
	}
	
	//
	
	
	if ($type == 'shipmodel') {
	
		$qru = mydb_query("".
			" UPDATE ship ".
			" SET ship.shipmodel_id = '0' ".
			" WHERE ship.shipmodel_id = '".$id."' ". 
			"");
		if (!$qru) {
			return false;
		}
		
		$qru = mydb_query("".
			" UPDATE item ".
			" SET item.shipmodel_id = '0' ".
			" WHERE item.shipmodel_id = '".$id."' ". 
			"");
		if (!$qru) {
			return false;
		}
			
	}
	
	//
	
	if ($type == 'ship') {
	
		$qru = mydb_query("".
			" UPDATE item ".
			" SET item.ship_id = '0' ".
			" WHERE item.ship_id = '".$id."' ". 
			"");
		if (!$qru) {
			return false;
		}
		
	}
		
	return true;
}


// =============================================================================
function try_del_ship($param) {

	$param['id'] = ''.intval($param['id']);

	$q = " SELECT ship.ship_id ".
		" FROM ship ".
		" WHERE ( ship.ship_id = '".$param['id']."' ) ".
		"";
	$qr = mydb_queryarray($q);

	if ($qr === false) {
		return false;
	}
	if (sizeof($qr) != 1) {
		return false;
	}
		
	//
	
	// process children here
	$r = my_get_shipclass_elem_children_clean('ship', $param['id']);
	if (!$r) return false;
	
	//
	
	$d = my_get_shipclass_elem_children_count('ship', $param['id']);
	$totalcount = $d['class'] + $d['model'] + $d['ship'] + $d['item'];
	if ($totalcount > 0) return false;
	
	//
	
	$qru = mydb_query("".
		" DELETE FROM ship ".
		" WHERE ship.ship_id = '".$param['id']."' ". 
		"");
	if (!$qru) {
		return false;
	}
	
	return true;
}


// =============================================================================
function try_del_shipmodel($param) {

	$param['id'] = ''.intval($param['id']);

	$q = " SELECT shipmodel.shipmodel_id ".
		" FROM shipmodel ".
		" WHERE ( shipmodel.shipmodel_id = '".$param['id']."' ) ".
		"";
	$qr = mydb_queryarray($q);

	if ($qr === false) {
		return false;
	}
	if (sizeof($qr) != 1) {
		return false;
	}
		
		
	//
	
	// process children here
	$r = my_get_shipclass_elem_children_clean('shipmodel', $param['id']);
	if (!$r) return false;
	
	//
	
	$d = my_get_shipclass_elem_children_count('shipmodel', $param['id']);
	$totalcount = $d['class'] + $d['model'] + $d['ship'] + $d['item'];
	
	if ($totalcount > 0) return on_shipmodeltree_del_error($param);
	
	//
	
	// delete blueprint here
	
	//
	
	$qru = mydb_query("".
		" DELETE FROM shipmodel ".
		" WHERE shipmodel.shipmodel_id = '".$param['id']."' ". 
		"");
	if (!$qru) {
		return false;
	}
	
	return true;
}


// =============================================================================
function try_del_shipmodelclass($param) {

	$param['id'] = ''.intval($param['id']);

	$q = " SELECT shipmodelclass.shipmodelclass_id ".
		" FROM shipmodelclass ".
		" WHERE ( shipmodelclass.shipmodelclass_id = '".$param['id']."' ) ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		return false;
	}
	if (sizeof($qr) != 1) {
		return false;
	}
	
	//
	
	// process children here
	$r = my_get_shipclass_elem_children_clean('shipmodelclass', $param['id']);
	if (!$r) return false;
	
	//
	
	$d = my_get_shipclass_elem_children_count('shipmodelclass', $param['id']);
	$totalcount = $d['class'] + $d['model'] + $d['ship'] + $d['item'];
	
	if ($totalcount > 0) return on_shipmodeltree_del_error($param);
	
	$qru = mydb_query("".
		" DELETE FROM shipmodelclass ".
		" WHERE shipmodelclass.shipmodelclass_id = '".$param['id']."' ". 
		"");
	if (!$qru) {
		return false;
	}
	
	return true;
}


// =============================================================================
function jqfn_shipmodeltree_del($param) {

	$out = '';
	
	if (!am_i_admin()) return on_shipmodeltree_del_error($param);
	
	// id=
	// type=
	
	$allowed_type =  array('shipmodelclass', 'shipmodel', 'ship');
	if (!isset($param['type'])) return on_shipmodeltree_del_error($param);
	if (!in_array($param['type'], $allowed_type)) return on_shipmodeltree_del_error($param);
	
	if (!isset($param['id'])) return on_shipmodeltree_del_error($param);
	if (!ctype_digit($param['id'])) return on_shipmodeltree_del_error($param);
	$param['id'] = ''.intval($param['id']);
	
	$r = false;
	
	if ($param['type'] == 'shipmodelclass') {
		$r = try_del_shipmodelclass($param);
	}
	
	if ($param['type'] == 'shipmodel') {
		$r = try_del_shipmodel($param);
	}
	
	if ($param['type'] == 'ship') {
		$r = try_del_ship($param);
	}
	
	if ($r) {
		return on_shipmodeltree_del_ok($param);
	} else {
		return on_shipmodeltree_del_error($param);
	}
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/shipmodeltree_del.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_shipmodeltree_del($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>