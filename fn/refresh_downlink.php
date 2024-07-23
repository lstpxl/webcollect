<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function downlink_to_item($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.* ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$d = array();
	
	if ($qr[0]['itemset_id'] > 0) {
		$d['itemset_str'] = my_get_itemset_name($qr[0]['itemset_id']);
	} else {
		// $d['itemset_str'] = '';
	}
	
	if ($qr[0]['ship_id'] > 0) {
	
		$d['ship_str'] = my_get_ship_name($qr[0]['ship_id']);
		$d['ship_factoryserialnum_str'] = my_get_ship_factoryserialnum($qr[0]['ship_id']);
		$d['ship_has_model'] = get_ship_has_model($qr[0]['ship_id']);
		
		// $ship_ti = my_get_elemtree_treeindex('ship', $qr[0]['ship_id']);
		
		// $parentti = my_get_elemtree_treeindex('ship', $qr[0]['ship_id']);
		
	} else {
		// $d['ship_str'] = '';
		// $d['ship_factoryserialnum_str '] = '';
		
		$d['ship_has_model'] = $qr[0]['ship_has_model'];
	}
	
	if ($d['ship_has_model'] == 'Y') {
		if ($qr[0]['shipmodel_id'] > 0) {
			$d['shipmodel_str'] = my_get_shipmodel_name($qr[0]['shipmodel_id']);
			
			$natoc_id = my_get_model_natoc_id($qr[0]['shipmodel_id']);
			if ($natoc_id > 0) {
				$d['natoc_id'] = $natoc_id;
				$d['natoc_str'] = my_get_natoc_str($natoc_id);
			}
			
		} else {
			//$d['shipmodel_str'] = '';
		}
	} else {
		$d['shipmodel_str'] = '';
		$d['shipmodel_id'] = '0';
	}
	
	
	if ($qr[0]['shipmodelclass_id'] > 0) {
		$d['shipmodelclass_str'] = my_get_shipclass_name($qr[0]['shipmodelclass_id']);
	} else {
		//$d['shipmodelclass_str'] = '';
	}
	
	if ($qr[0]['factory_id'] > 0) {
		$d['factory_str'] = my_get_factory_name($qr[0]['factory_id']);
	} else {
		//$d['factory_str'] = '';
	}
	
	// 
	$d['ti_self'] = my_calc_item_treeindex($item_id);
	$d['sortfield_c'] = $d['ti_self'];
	$a = explode('i', $d['ti_self'], 3);
	if (sizeof($a) == 2) {
		$d['ti_parent'] = $a[0];
	} else {
		// ?
	}
	
	//
	
	// var_dump($d);
	
	// prepared query
	$a = array();
	$t = '';
	$q = "UPDATE item ".
		" SET item.downlink_time = ? ";
	$a[] = date('Y-m-d H:i:s');
	$t .= 's';
	
	foreach ($d as $key => $value) {
		$q .= ", item.".$key." = ? ";
		$a[] = $value;
		$t .= 's';
	}
	
	$q .= " WHERE item.item_id = ? ";
	$a[] = $item_id;
	$t .= 'i';
	
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	// update_item_searchstring($item_id);
	my_mark_element_fresh('item', $item_id, 'Y');
	
	//
	
	return true;
}


// =============================================================================
function force_downlink_item($item_id) {

	$item_id = ''.intval($item_id);

	// prepared query
	$a = array();
	$a[] = '1999-12-31 23:50:00';
	$a[] = $item_id;
	$t = 'si';
	$q = "UPDATE item ".
		" SET item.downlink_time = ? ".
		" WHERE item.item_id = ? ";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	return true;
}


// =============================================================================
function force_downlink_children_recursive($type, $id) {

	$r = my_elemtree_get_children_list_complete($type, $id);
	
	for ($i = 0; $i < sizeof($r); $i++) {
		if ($r[$i]['type'] == 'item') {
			force_downlink_item($r[$i]['id']);
		}
	}
	
	return true;
}


// =============================================================================
function calc_downlink_n_items($n) {

	$n = ''.intval($n);

	$qr = mydb_queryarray("".
		" SELECT item.item_id ".
		" FROM item ".
		// " WHERE item.status = 'K' ".
		" WHERE item.item_id != 0 ".
		" ORDER BY item.downlink_time ".
		" LIMIT ".$n." ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	//
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		print '<p>downlink_to_item #'.$qr[$i]['item_id'].'.';
		downlink_to_item($qr[$i]['item_id']);
	}

	return true;
}


?>