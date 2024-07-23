<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function on_shipmodeltree_move_error($param) {
	
	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=shipmodeltree_move_result;';
	$out .= '-->';
	$out .= 'failure';
	
	header('Content-Type: text/html; charset=utf-8');
	
	print $out;

	return true;
}


// =============================================================================
function on_shipmodeltree_move_ok($param) {
	
	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=shipmodeltree_move_result;';
	$out .= '-->';
	$out .= 'ok';
	
	header('Content-Type: text/html; charset=utf-8');
	
	print $out;

	return true;
}



// =============================================================================
function try_move_shipmodel($param) {

	$param['parent_id'] = ''.intval($param['parent_id']);
	$param['what_id'] = ''.intval($param['what_id']);
	
	$qru = mydb_query("".
		" UPDATE shipmodel ".
		" SET shipmodel.shipmodelclass_id = '".$param['parent_id']."' ".
		" WHERE shipmodel.shipmodel_id = '".$param['what_id']."' ". 
		"");
	if (!$qru) {
		return on_shipmodeltree_move_error($param);
	}
	
	return on_shipmodeltree_move_ok($param);
}


// =============================================================================
function try_move_shipmodelclass($param) {

	$param['parent_id'] = ''.intval($param['parent_id']);
	$param['what_id'] = ''.intval($param['what_id']);
	
	$qru = mydb_query("".
		" UPDATE shipmodelclass ".
		" SET shipmodelclass.parent_id = '".$param['parent_id']."' ".
		" WHERE shipmodelclass.shipmodelclass_id = '".$param['what_id']."' ". 
		"");
	if (!$qru) {
		return on_shipmodeltree_move_error($param);
	}
	
	return on_shipmodeltree_move_ok($param);
}


// =============================================================================
function jqfn_shipmodeltree_move($param) {

	$out = '';
	
	if (!am_i_admin()) return on_shipmodeltree_move_error($param);
	
	// what_id=' + what_id 
	// + '&parent_id=' + parent_id + 
	// '&type=' + what_type + '';
	
	$allowed_type =  array('shipmodelclass', 'shipmodel');
	if (!isset($param['type'])) return on_shipmodeltree_move_error($param);
	if (!in_array($param['type'], $allowed_type)) return on_shipmodeltree_move_error($param);
	
	if (!isset($param['what_id'])) return on_shipmodeltree_move_error($param);
	if (!ctype_digit($param['what_id'])) return on_shipmodeltree_move_error($param);
	$param['what_id'] = ''.intval($param['what_id']);
	
	if (!isset($param['parent_id'])) return on_shipmodeltree_move_error($param);
	if (!ctype_digit($param['parent_id'])) return on_shipmodeltree_move_error($param);
	$param['parent_id'] = ''.intval($param['parent_id']);
	
	if ($param['type'] == 'shipmodelclass') {
		// само на себя
		if ($param['parent_id'] == $param['what_id']) {
			return on_shipmodeltree_move_error($param);
		}
	}
	
	
	$q = " SELECT shipmodelclass.shipmodelclass_id ".
		" FROM shipmodelclass ".
		" WHERE ( shipmodelclass.shipmodelclass_id = '".$param['parent_id']."' ) ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		return on_shipmodeltree_move_error($param);
	}
	if (sizeof($qr) != 1) {
		return on_shipmodeltree_move_error($param);
	}

	if ($param['type'] == 'shipmodelclass') {
		$q = " SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE ( shipmodelclass.shipmodelclass_id = '".$param['what_id']."' ) ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			return on_shipmodeltree_move_error($param);
		}
		if (sizeof($qr) != 1) {
			return on_shipmodeltree_move_error($param);
		}
		
		try_move_shipmodelclass($param);
	}
	
	if ($param['type'] == 'shipmodel') {
	
	
		$q = " SELECT shipmodel.shipmodel_id ".
			" FROM shipmodel ".
			" WHERE ( shipmodel.shipmodel_id = '".$param['what_id']."' ) ".
			"";
		$qr = mydb_queryarray($q);

		if ($qr === false) {
			return on_shipmodeltree_move_error($param);
		}
		if (sizeof($qr) != 1) {
			return on_shipmodeltree_move_error($param);
		}
		
		return try_move_shipmodel($param);
	}
	
	return false;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/shipmodeltree_move.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_shipmodeltree_move($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>