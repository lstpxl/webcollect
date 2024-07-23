<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');


// =============================================================================
function get_itemset_list($itemset_id) {

	$itemset_id = ''.intval($itemset_id);

	$q = "".
		" SELECT item.item_id ".
		" FROM item ".
		" WHERE ".
		" ( ".
		" ( item.status = 'K' ) ".
		" OR ".
		" ( item.status = 'U' ) ".
		" ) ".
		" AND ( item.itemset_id = '".$itemset_id."' ) ".
		" ORDER BY ".
		" (item.itemset_id < 1),  ".
		" item.status DESC, item.itemset_str, ".
		" item.sortfield_c, item.sortfield_a, ".
		" item.time_submit_start DESC ".
		" LIMIT 1000 ".
		";";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}


// =============================================================================
function outhtml_itemset_list($itemset_id) {

	$out = '';
	
	$str = 'Серия '.my_get_itemset_name($itemset_id);
	$GLOBALS['pagetitle'] = $str.' - '.$GLOBALS['pagetitle'];
	
	$str = '<span style=" color: #bfc5c9; margin-left: 10px; ">'.'Серия '.'</span>'.htmlspecialchars(my_get_itemset_name($itemset_id), ENT_QUOTES);
	$out .= outhtml_item_list_head_itemset($str);
	
	$list = get_itemset_list($itemset_id);
	
	for ($i = 0; $i < sizeof($list); $i++) {
		$out .= outhtml_item_inlist($list[$i]['item_id']);
	}
	

	$out .= '<div style=" clear: both; min-height: 50px; "></div>';
	
	return $out;
}


// =============================================================================
function outhtml_item_itemset($param) {
	
	$out = '';
	
	if (!am_i_registered_user()) {
		return outhtml_welcome_screen($param);
	}
	
	if (!isset($param['itemset_id'])) $param['itemset_id'] = 0;
	if (!ctype_digit($param['itemset_id'])) $param['itemset_id'] = 0;
	$param['itemset_id'] = ''.intval($param['itemset_id']);
	
	if (my_get_itemset_name($param['itemset_id']) === false) $param['itemset_id'] = 0;
	
	// if ($param['itemset_id'] == 0) {}
	
	if ($param['itemset_id'] < 1) {
		return outhtml_welcome_screen($param);
	}

	$out .= outhtml_itemset_list($param['itemset_id']);

	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>