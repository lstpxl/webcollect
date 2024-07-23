<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');



// =============================================================================
function set_item_ti_subsort($item_id, $ti_subsort) {

	$item_id = ''.intval($item_id);

	// prepared query
	$a = array();
	$a[] = $ti_subsort;
	$a[] = $item_id;
	$q = "".
		" UPDATE item ".
		" SET item.ti_subsort = ?, ".
		" item.refresh = 'Y' ".
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
function set_ship_ti_subsort($ship_id, $ti_subsort) {

	$ship_id = ''.intval($ship_id);

	// prepared query
	$a = array();
	$a[] = $ti_subsort;
	$a[] = $ship_id;
	$q = "".
		" UPDATE ship ".
		" SET ship.ti_subsort = ?, ".
		" ship.refresh = 'Y' ".
		" WHERE ship.ship_id = ? ".
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
function set_shipmodel_ti_subsort($shipmodel_id, $ti_subsort) {

	$shipmodel_id = ''.intval($shipmodel_id);

	// prepared query
	$a = array();
	$a[] = $ti_subsort;
	$a[] = $shipmodel_id;
	$q = "".
		" UPDATE shipmodel ".
		" SET shipmodel.ti_subsort = ?, ".
		" shipmodel.refresh = 'Y' ".
		" WHERE shipmodel.shipmodel_id = ? ".
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
function set_shipmodelclass_ti_subsort($shipmodelclass_id, $ti_subsort) {

	$shipmodelclass_id = ''.intval($shipmodelclass_id);

	// prepared query
	$a = array();
	$a[] = $ti_subsort;
	$a[] = $shipmodelclass_id;
	$q = "".
		" UPDATE shipmodelclass ".
		" SET shipmodelclass.ti_subsort = ?, ".
		" shipmodelclass.refresh = 'Y' ".
		" WHERE shipmodelclass.shipmodelclass_id = ? ".
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
function my_get_elemtree_treeindex($type, $id) {
	
	$id = ''.intval($id);
	
	if ($type == 'shipmodelclass') {
	
		if ($id == 0) return 'a';
	
		$q = "SELECT shipmodelclass.treeindex ".
			"FROM shipmodelclass ".
			"WHERE shipmodelclass.shipmodelclass_id = '".$id."' ";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return $qr[0]['treeindex'];
	
	}
	
	if ($type == 'shipmodel') {
	
		$q = "SELECT shipmodel.treeindex ".
			"FROM shipmodel ".
			"WHERE shipmodel.shipmodel_id = '".$id."' ";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return $qr[0]['treeindex'];
	
	}
	
	if ($type == 'ship') {
	
		$q = "SELECT ship.treeindex ".
			"FROM ship ".
			"WHERE ship.ship_id = '".$id."' ";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return $qr[0]['treeindex'];
	
	}
	
	if ($type == 'item') {
	
		$q = " SELECT item.ti_self ".
			" FROM item ".
			" WHERE item.item_id = '".$id."' ".
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return $qr[0]['ti_self'];
	
	}
	
	return false;
}



// =============================================================================
function treeindex_rebuld_item_group($ti_parent) {

	// ab0zz
		
	$q = " SELECT item.item_id ".
		" FROM item ".
		" WHERE item.ti_parent = '".$ti_parent."' ".
		" ORDER BY item.ti_subsort, item.item_id ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$padsize = mb_strlen(''.sizeof($qr));
	
	// print '<p>sizeof group «'.$ti_parent.'» = '.sizeof($qr).'.';
				
	for ($i = 0; $i < sizeof($qr); $i++) {
		$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
		$r = my_elemtree_set_treeindex('item', $qr[$i]['item_id'], $ti_parent.'i'.$x);
		// print '<p>my_elemtree_set_treeindex #'.$qr[$i]['item_id'].'.';
	}
		
	return true;
}



// =============================================================================
function treeindex_rebuld_ship_group($shipmodel_id) {

	if ($shipmodel_id > 0) {
	
		$q = "SELECT shipmodel.shipmodel_id, shipmodel.treeindex ".
			" FROM shipmodel ".
			" WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
		
		$ti_parent = $qr[0]['treeindex'];
		
		if ($ti_parent == '') $ti_parent = 'azz';
	
	} else {
	
		$ti_parent = 'azz';
		
	}
		
	$q = "SELECT ship.ship_id, ".
		" ship.treeindex ".
		" FROM ship ".
		" WHERE ship.shipmodel_id = '".$shipmodel_id."' ".
		" ORDER BY ship.ti_subsort, ship.treeindex ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$padsize = mb_strlen(''.sizeof($qr));
				
	for ($i = 0; $i < sizeof($qr); $i++) {
		$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
		// $r = my_elemtree_set_treeindex('item', $qr[$i]['item_id'], $ti_parent.'i'.$x);
		$r = my_elemtree_set_treeindex('ship', $qr[$i]['ship_id'], ($ti_parent.'d'.$x));
	}
		
	return true;
}



// =============================================================================
function treeindex_rebuld_model_group($shipmodelclass_id) {

	if ($shipmodelclass_id > 0) {
	
		$q = "SELECT shipmodelclass.shipmodelclass_id, shipmodelclass.treeindex ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$shipmodelclass_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
		
		$ti_parent = $qr[0]['treeindex'];
		
		if ($ti_parent == '') $ti_parent = 'az';
	
	} else {
	
		$ti_parent = 'az';
		
	}
		
	$q = "SELECT shipmodel.shipmodel_id, ".
		" shipmodel.treeindex ".
		" FROM shipmodel ".
		" WHERE shipmodel.shipmodelclass_id = '".$shipmodelclass_id."' ".
		" ORDER BY shipmodel.ti_subsort, shipmodel.treeindex ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$padsize = mb_strlen(''.sizeof($qr));
				
	for ($i = 0; $i < sizeof($qr); $i++) {
		$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
		// $r = my_elemtree_set_treeindex('item', $qr[$i]['item_id'], $ti_parent.'i'.$x);
		$r = my_elemtree_set_treeindex('shipmodel', $qr[$i]['shipmodel_id'], ($ti_parent.'c'.$x));
	}
		
	return true;
}


// =============================================================================
function treeindex_rebuld_class_group($parent_id) {

	if ($parent_id > 0) {
	
		$q = "SELECT shipmodelclass.shipmodelclass_id, shipmodelclass.treeindex ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$parent_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
		
		$ti_parent = $qr[0]['treeindex'];
		
		if ($ti_parent == '') $ti_parent = 'a';
	
	} else {
	
		$ti_parent = 'a';
		
	}
		
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.treeindex ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.parent_id = '".$parent_id."' ".
		" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.treeindex ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$padsize = mb_strlen(''.sizeof($qr));
				
	for ($i = 0; $i < sizeof($qr); $i++) {
		$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
		// $r = my_elemtree_set_treeindex('item', $qr[$i]['item_id'], $ti_parent.'i'.$x);
		$r = my_elemtree_set_treeindex('shipmodelclass', $qr[$i]['shipmodelclass_id'], ($ti_parent.'b'.$x));
	}
		
	return true;
}


// =============================================================================
function my_set_item_ti_parent($id, $ti_parent) {

	$id = ''.intval($id);
	
		$qr = mydb_query("".
			" UPDATE item ".
			" SET item.ti_parent = '".$ti_parent."' ".
			" WHERE item.item_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		return true;

}


// =============================================================================
function treeindex_rebuld_group_recursive_z($type, $id) {
	
	$l = my_elemtree_get_children_list($type, $id);
	
	
	
	$hasrefresh = false;
	for ($i = 0; $i < sizeof($l); $i++) {
		if ($l[$i]['refresh'] == 'Y') {
			$hasrefresh = true;
			
			
			print '<br>';
			var_dump($l[$i]);
			print '<br>';
	
		}
	}
	
	if ($hasrefresh) {
	
		$ti_parent = my_get_elemtree_treeindex($type, $id);
		
		for ($i = 0; $i < sizeof($l); $i++) {
			if ($l[$i]['type'] == 'item') {
				my_set_item_ti_parent($l[$i]['id'], $ti_parent);
			} else {
				my_mark_children_fresh($l[$i]['type'], $l[$i]['id'], true);
			}
		}
	
		if ($type == 'shipmodelclass') {
			treeindex_rebuld_class_group($id);
			treeindex_rebuld_model_group($id);
			
			treeindex_rebuld_item_group($ti_parent);
		}
		
		if ($type == 'shipmodel') {
			treeindex_rebuld_ship_group($id);

			treeindex_rebuld_item_group($ti_parent);
		}
		
		if ($type == 'ship') {

			treeindex_rebuld_item_group($ti_parent);
		}
	
		return true;
	
	} else {
		
		// we need to go deeper
		
		$donerefresh = false;
		
		for ($i = 0; $i < sizeof($l); $i++) {
			if ($l[$i]['type'] != 'item') {
				$r = treeindex_rebuld_group_recursive_z($l[$i]['type'], $l[$i]['id']);
				if ($r) return true;
			}
		}
		
	}
	
	return $hasrefresh;
}



// =============================================================================
function treeindex_rebuld_group_recursive_ztop() {

	$q = " SELECT shipmodelclass.shipmodelclass_id, shipmodelclass.parent_id ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.refresh = 'Y' ".
		" ORDER BY LENGTH(shipmodelclass.treeindex), ti_subsort, shipmodelclass_id ".
		" LIMIT 3 ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	print '<p>Shipmodelclass to refresh = '.sizeof($qr).'</p>';
	
	if (sizeof($qr) > 0) {
		return treeindex_rebuld_group_recursive_z('shipmodelclass', $qr[0]['parent_id']);
	}
	
	$q = " SELECT shipmodel.shipmodel_id, shipmodel.shipmodelclass_id ".
		" FROM shipmodel ".
		" WHERE shipmodel.refresh = 'Y' ".
		" ORDER BY LENGTH(shipmodel.treeindex), ti_subsort, shipmodel_id ".
		" LIMIT 3 ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	print '<p>shipmodel to refresh = '.sizeof($qr).'</p>';
	if (sizeof($qr) > 0) {
		return treeindex_rebuld_group_recursive_z('shipmodelclass', $qr[0]['shipmodelclass_id']);
	}
	
	$q = " SELECT ship.ship_id, ship.shipmodel_id ".
		" FROM ship ".
		" WHERE ship.refresh = 'Y' ".
		" ORDER BY LENGTH(ship.treeindex), ti_subsort, ship_id ".
		" LIMIT 3 ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	print '<p>ship to refresh = '.sizeof($qr).'</p>';
	if (sizeof($qr) > 0) {
		return treeindex_rebuld_group_recursive_z('shipmodel', $qr[0]['shipmodel_id']);
	}
	
	$q = " SELECT item.item_id, item.ti_parent ".
		" FROM item ".
		" WHERE item.refresh = 'Y' ".
		" ORDER BY LENGTH(item.ti_parent), ti_subsort, item_id ".
		// " LIMIT 80 ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	print '<p>item to refresh = '.sizeof($qr).'</p>';
	if (sizeof($qr) > 0) {
		$max = sizeof($qr);
		if ($max > 15) $max = 15;
		for ($i = 0; $i < $max; $i++) {
			print '<p>Rebuilding «'.$qr[$i]['ti_parent'].'» of item #'.$qr[$i]['item_id'].'</p>';
			$result = treeindex_rebuld_item_group($qr[$i]['ti_parent']);
			if (!$result) return false;
		}
		return true;
	}
	
	//return treeindex_rebuld_group_recursive_z('shipmodelclass', 0);
	return false;
}


// =============================================================================
function my_calc_item_treeindex_v($item_id) {

	$item_id = ''.intval($item_id);
		
	$ship_id = get_item_ship_id($item_id);
	if ($ship_id > 0) {
		$ship_idx = my_get_elemtree_treeindex('ship', $ship_id);
		if (mb_strpos($ship_idx, 'z') === false) {
			my_elemtree_set_treeindex('item', $item_id, $ship_idx.'ix');
			return treeindex_rebuld_item_group($ship_idx);
		}
	}

	
	// если не всё гладко
	
	$s = 'a';
	
	$class_id = get_item_shipclass_id($item_id);
	if ($class_id > 0) {
		$class_idx = my_get_elemtree_treeindex('shipmodelclass', $class_id);
		$s = $class_idx;
	} else {
		$s .= 'z';
	}
	
	$model_id = get_item_shipmodel_id($item_id);
	if ($model_id > 0) {
		$model_idx = my_get_elemtree_treeindex('shipmodel', $model_id);
		$p = mb_strpos($model_idx, 'c');
		if ($p === false) {
			$s .= 'x';
		} else {
			$s .= mb_substr($model_idx, $p);
		}
	} else {
		$s .= 'z';
	}
	
	$ship_id = get_item_ship_id($item_id);
	if ($ship_id > 0) {
		$ship_idx = my_get_elemtree_treeindex('ship', $ship_id);
		$p = mb_strpos($ship_idx, 'd');
		if ($p === false) {
			$s .= 'x';
		} else {
			$s .= mb_substr($ship_idx, $p);
		}
	} else {
		$s .= 'z';
	}
	
	// print $s;
	
	my_elemtree_set_treeindex('item', $item_id, $s.'ix');
	
	return treeindex_rebuld_item_group($s);
}


// =============================================================================
function my_elemtree_rebuild_treeindex_local($type, $id) {

	// a topmost index
	// b shipmodelclass
	// c shipmodel
	// d ship
	// i item

	if ($type == 'shipmodelclass') {
		$parent_id = my_get_shipclass_parent($id);
		if ($parent_id > 0) {
			$treeindex = my_get_elemtree_treeindex('shipmodelclass', $parent_id);
			my_elemtree_rebuild_treeindex_recursive('shipmodelclass', $parent_id, $treeindex);
			return true;
		} else {
			$str = my_elemtree_set_treeindex_recursive_undef($type, $id, 'a');
			my_elemtree_rebuild_treeindex_recursive('shipmodelclass', 0, $str);
		}
	}
	
	if ($type == 'shipmodel') {
		$parent_id = my_get_shipmodel_class($id);
		if ($parent_id > 0) {
			$treeindex = my_get_elemtree_treeindex('shipmodelclass', $parent_id);
			my_elemtree_rebuild_treeindex_recursive('shipmodelclass', $parent_id, $treeindex);
			return true;
		} else {
			$str = my_elemtree_set_treeindex_recursive_undef($type, $id, 'azc');
			my_elemtree_rebuild_treeindex_recursive('shipmodel', $id, $str);
			return true;
		}
	}
	
	if ($type == 'ship') {
		$has_model = get_ship_has_model($id);
		if ($has_model) {
			$parent_id = my_get_ship_model_id($id);
			if ($parent_id > 0) {
				$treeindex = my_get_elemtree_treeindex('shipmodel', $parent_id);
				my_elemtree_rebuild_treeindex_recursive('shipmodel', $parent_id, $treeindex);
				return true;
			} else {
				$str = my_elemtree_set_treeindex_recursive_undef($type, $id, 'azzd');
				my_elemtree_rebuild_treeindex_recursive('ship', $id, $str);
				return true;
			}
		} else {
			$class_id = my_get_ship_class($id);
			if ($class_id > 0) {
				$treeindex = my_get_elemtree_treeindex('shipmodelclass', $class_id);
				my_elemtree_rebuild_treeindex_recursive('shipmodelclass', $class_id, $treeindex);
				return true;
			} else {
				$str = my_elemtree_set_treeindex_recursive_undef($type, $id, 'azzd');
				my_elemtree_rebuild_treeindex_recursive('ship', $id, $str);
				return true;
			}
		}
	}
	
	if ($type == 'item') {
	
		// return my_calc_item_treeindex_v($id);
		
		//
		/*
		
		$parent_id = get_item_ship_id($id);
		
		if ($parent_id > 0) {
			$treeindex = my_get_elemtree_treeindex('ship', $parent_id);
			my_elemtree_rebuild_treeindex_recursive('ship', $parent_id, $treeindex);
			return true;
		} else {
			$parent_id = get_item_shipmodel_id($id);
			if ($parent_id > 0) {
				$treeindex = my_get_elemtree_treeindex('shipmodel', $parent_id);
				my_elemtree_rebuild_treeindex_recursive('shipmodel', $parent_id, $treeindex);
				return true;
			} else {
				$parent_id = get_item_shipclass_id($id);
				if ($parent_id > 0) {
					$treeindex = my_get_elemtree_treeindex('shipmodelclass', $parent_id);
					my_elemtree_rebuild_treeindex_recursive('shipmodelclass', $parent_id, $treeindex);
					return true;
				} else {
					$str = my_elemtree_set_treeindex_recursive_undef($type, $id, 'azzzi');
					// my_elemtree_build_treeindex_recursive('item', $parent_id, $str);
					return true;
				}
			}
		}
		*/
	}

	return false;
}



// =============================================================================
function my_elemtree_set_treeindex($type, $id, $treeindexstr) {

	$id = ''.intval($id);

	if ($type == 'shipmodelclass') {
		
		$qr = mydb_query("".
			" UPDATE shipmodelclass ".
			" SET shipmodelclass.treeindex = '".$treeindexstr."', ".
			" shipmodelclass.refresh = 'N' ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		my_mark_children_fresh($type, $id, 'Y');
		
		return true;
	}
	
	if ($type == 'shipmodel') {
		
		$qr = mydb_query("".
			" UPDATE shipmodel ".
			" SET shipmodel.treeindex = '".$treeindexstr."', ".
			" shipmodel.refresh = 'N' ".
			" WHERE shipmodel.shipmodel_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		my_mark_children_fresh($type, $id, 'Y');
		
		return true;
	}
	
	//
	
	if ($type == 'ship') {
		
		$qr = mydb_query("".
			" UPDATE ship ".
			" SET ship.treeindex = '".$treeindexstr."', ".
			" ship.refresh = 'N' ".
			" WHERE ship.ship_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		my_mark_children_fresh($type, $id, 'Y');
		
		return true;
	}
	
	//
	
	if ($type == 'item') {
	
		$p = mb_strpos($treeindexstr, 'i');
		if ($p === false) {
			$ti_parent = 'xxxxx';
		} else {
			$ti_parent = mb_substr($treeindexstr, 0, $p);
		}
		
		$qr = mydb_query("".
			" UPDATE item ".
			" SET item.sortfield_c = '".$treeindexstr."' , ".
			" item.ti_self = '".$treeindexstr."', ".
			" item.ti_parent = '".$ti_parent."', ".
			" item.refresh = 'N' ".
			" WHERE item.item_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// print '<p>N</p>';
		
		return true;
	}
	
	//
	
	return false;
}


// =============================================================================
function my_elemtree_set_treeindex_recursive_undef($type, $id, $treeindexstr) {

	$id = ''.intval($id);

	// a topmost index
	// b shipmodelclass
	// c shipmodel
	// d ship
	// i item
	
	
	if ($type == 'shipmodelclass') {

		
		$q = "SELECT COUNT(shipmodelclass.shipmodelclass_id) AS n ".
			" FROM shipmodelclass ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		
	
		$q = "SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$x = '';
		for ($i = 0; $i < sizeof($qr); $i++) {
			if ($qr[$i]['shipmodelclass_id'] == $id) {
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
			}
		}
		
		if ($x == '') return false;
		
		my_elemtree_set_treeindex('shipmodelclass', $id, ($treeindexstr.$x));
				
		return ($treeindexstr.$x);
	
	}
	
	//
	
	
	if ($type == 'shipmodel') {
		
		$q = "SELECT COUNT(shipmodel.shipmodel_id) AS n ".
			" FROM shipmodel ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		
		
		$q = " SELECT shipmodel.shipmodel_id ".
			" FROM shipmodel ".
			" ORDER BY shipmodel.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$x = '';
		for ($i = 0; $i < sizeof($qr); $i++) {
			if ($qr[$i]['shipmodel_id'] == $id) {
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
			}
		}
		
		if ($x == '') return false;
		
		my_elemtree_set_treeindex('shipmodel', $id, ($treeindexstr.$x));
				
		return ($treeindexstr.$x);
	}
	
	//
	
	if ($type == 'ship') {
		
		$q = "SELECT COUNT(ship.ship_id) AS n ".
			" FROM ship ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		
		
		$q = " SELECT ship.ship_id ".
			" FROM ship ".
			" ORDER BY (ship.factoryserialnum = 0), ship.factoryserialnum, ship.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$x = '';
		for ($i = 0; $i < sizeof($qr); $i++) {
			if ($qr[$i]['ship_id'] == $id) {
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
			}
		}
		
		if ($x == '') return false;
		
		my_elemtree_set_treeindex('ship', $id, ($treeindexstr.$x));
				
		return ($treeindexstr.$x);
	}
	
	//
	
	if ($type == 'item') {
	
		$q = "SELECT COUNT(item.item_id) AS n ".
			" FROM item ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" ORDER BY IF(item.shipmodel_str = '', 1, 0), item.shipmodel_str, ".
			" IF(item.ship_str = '', 1, 0), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$x = '';
		for ($i = 0; $i < sizeof($qr); $i++) {
			if ($qr[$i]['item_id'] == $id) {
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
			}
		}
		
		if ($x == '') return false;
		
		my_elemtree_set_treeindex('item', $id, ($treeindexstr.$x));
		
		return ($treeindexstr.$x);
	}
	
	//
	
	return false;
}


// =============================================================================
function my_elemtree_rebuild_treeindex_recursive($type, $id, $treeindexstr) {

	$id = ''.intval($id);

	// a topmost index
	// b shipmodelclass
	// c shipmodel
	// d ship
	// i item
	
	
	if ($type == 'shipmodelclass') {
	
		if ($id > 0) my_elemtree_set_treeindex($type, $id, $treeindexstr);

		$q = "SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.parent_id = '".$id."' ".
			" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		$padsize = mb_strlen(''.sizeof($qr));
		
		/*
		if ($treeindexstr == 'ab001') {
			print_r($qr);
			print $id.' ';
		}
		*/
		
		if ($id == 2) {
			// print_r($qr);
			//print $id.' ';
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$x = str_pad(''.$i, $padsize, '0', STR_PAD_LEFT);
			my_elemtree_rebuild_treeindex_recursive('shipmodelclass', $qr[$i]['shipmodelclass_id'], $treeindexstr.'b'.$x);
		}
		
		//
		
		/*
		$q = "SELECT COUNT(shipmodel.shipmodel_id) AS n ".
			" FROM shipmodel ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		*/
		
		//
		
		$q = " SELECT shipmodel.shipmodel_id ".
			" FROM shipmodel ".
			" WHERE shipmodelclass_id = '".$id."' ".
			" ORDER BY shipmodel.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen(''.sizeof($qr));
		
		if ($id > 0) {

			for ($i = 0; $i < sizeof($qr); $i++) {
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				my_elemtree_rebuild_treeindex_recursive('shipmodel', $qr[$i]['shipmodel_id'], $treeindexstr.'c'.$x);
			}
		
		} else {
					
			for ($i = 0; $i < sizeof($qr); $i++) {
				/*
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				my_elemtree_rebuild_treeindex_local('shipmodel', $qr[$i]['shipmodel_id'], $treeindexstr.'c'.$x);
				*/
				
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				// $str = my_elemtree_set_treeindex_recursive_undef('shipmodel', $qr[$i]['shipmodel_id'], 'azc'.$x);
				$r = my_elemtree_set_treeindex('shipmodel', $qr[$i]['shipmodel_id'], 'azc'.$x);
				my_elemtree_rebuild_treeindex_recursive('shipmodel', $qr[$i]['shipmodel_id'], 'azc'.$x);
			}
		
			my_elemtree_rebuild_treeindex_recursive('shipmodel', 0, 'azz');
		}
		
		//
		
		//
		
		$q = " SELECT ship.ship_id ".
			" FROM ship ".
			" WHERE shipmodelclass_id = '".$id."' ".
			" AND has_model = 'N' ".
			" ORDER BY ship.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen(''.sizeof($qr));
		
		if ($id > 0) {

			for ($i = 0; $i < sizeof($qr); $i++) {
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				my_elemtree_rebuild_treeindex_recursive('ship', $qr[$i]['ship_id'], $treeindexstr.'zd'.$x);
			}
		
		} else {
					
			for ($i = 0; $i < sizeof($qr); $i++) {
				/*
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				my_elemtree_rebuild_treeindex_local('shipmodel', $qr[$i]['shipmodel_id'], $treeindexstr.'c'.$x);
				*/
				
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				// $str = my_elemtree_set_treeindex_recursive_undef('shipmodel', $qr[$i]['shipmodel_id'], 'azc'.$x);
				$r = my_elemtree_set_treeindex('ship', $qr[$i]['ship_id'], 'azzd'.$x);
				my_elemtree_rebuild_treeindex_recursive('ship', $qr[$i]['ship_id'], 'azzd'.$x);
			}
		
			my_elemtree_rebuild_treeindex_recursive('ship', 0, 'azzz');
		}
		
		//
		
		/*
		$q = "SELECT COUNT(item.item_id) AS n ".
			" FROM item ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		*/
		
		$padsize = mb_strlen(''.sizeof($qr));
		
		/*
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodelclass_id = '".$id."' ".
			" AND item.ship_id = '0' ".
			" ORDER BY IF(item.shipmodel_str = '', 1, 0), item.shipmodel_str, ".
			" IF(item.ship_str = '', 1, 0), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_rebuild_treeindex_recursive('item', $qr[$i]['item_id'], $treeindexstr.'i'.$x);
		}
		$add += sizeof($qr);
		*/
	
	}
	
	//
	
	
	if ($type == 'shipmodel') {
	
		if ($id > 0) my_elemtree_set_treeindex($type, $id, $treeindexstr);
		
		/*
		$q = "SELECT COUNT(ship.ship_id) AS n ".
			" FROM ship ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		*/
		
		
		
		
		$q = " SELECT ship.ship_id ".
			" FROM ship ".
			" WHERE shipmodel_id = '".$id."' ".
			" ORDER BY (ship.factoryserialnum = 0), ship.factoryserialnum, ship.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen(''.sizeof($qr));
		
		
		

		//
		
		if ($id > 0) {

			for ($i = 0; $i < sizeof($qr); $i++) {
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				my_elemtree_rebuild_treeindex_recursive('ship', $qr[$i]['ship_id'], $treeindexstr.'d'.$x);
			}
		
		} else {
					
			for ($i = 0; $i < sizeof($qr); $i++) {
				
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				// $str = my_elemtree_set_treeindex_recursive_undef('ship', $qr[$i]['ship_id'], 'azzd'.$x);
				$r = my_elemtree_set_treeindex('ship', $qr[$i]['ship_id'], 'azzd'.$x);
				my_elemtree_rebuild_treeindex_recursive('ship', $qr[$i]['ship_id'], 'azzd'.$x);
			}
		
			my_elemtree_rebuild_treeindex_recursive('ship', 0, 'azzz');
		}
		
		//
		
		/*
		$q = "SELECT COUNT(item.item_id) AS n ".
			" FROM item ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		*/
		
		/*
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodel_id = '".$id."' ".
			" AND item.ship_id = '0' ".
			" ORDER BY (item.shipmodel_str != ''), item.shipmodel_str, ".
			" (item.ship_str != ''), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen(''.sizeof($qr));
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_rebuild_treeindex_recursive('item', $qr[$i]['item_id'], $treeindexstr.'i'.$x);
		}
		*/
			
	}
	
	//
	
	if ($type == 'ship') {
	
		if ($id > 0) my_elemtree_set_treeindex($type, $id, $treeindexstr);
		
		/*
		$q = "SELECT COUNT(item.item_id) AS n ".
			" FROM item ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen($qr[0]['n']);
		*/
		
		return true;
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.ship_id = '".$id."' ".
			" ORDER BY (item.shipmodel_str != ''), item.shipmodel_str, ".
			" (item.ship_str != ''), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$padsize = mb_strlen(''.sizeof($qr));
		
		
		
		//
		
		if ($id > 0) {

			for ($i = 0; $i < sizeof($qr); $i++) {
			
				my_calc_item_treeindex_v($qr[$i]['item_id']);
			
				/*
				$x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				my_elemtree_rebuild_treeindex_recursive('item', $qr[$i]['item_id'], $treeindexstr.'i'.$x);
				*/
			}
		
		} else {
					
			for ($i = 0; $i < sizeof($qr); $i++) {
			
				my_calc_item_treeindex_v($qr[$i]['item_id']);
				
				// $x = str_pad(''.($i), $padsize, '0', STR_PAD_LEFT);
				// $str = my_elemtree_set_treeindex_recursive_undef('item', $qr[$i]['item_id'], 'azzzi'.$x);
				// $r = my_elemtree_set_treeindex('item', $qr[$i]['item_id'], 'azzzi'.$x);
				//my_elemtree_rebuild_treeindex_recursive('item', $qr[$i]['item_id'], $str);
			}
		
			//my_elemtree_rebuild_treeindex_recursive('item', 0, 'azzz');
		}
		
	}
	
	//
	
	if ($type == 'item') {
	
		if ($id > 0) my_elemtree_set_treeindex($type, $id, $treeindexstr);
	
	}
	
	//
	
	return true;
}


// =============================================================================
function my_elemtree_get_children_list_complete($type, $id) {

	$r = my_elemtree_get_children_list($type, $id);
	
	$m = $r;
	for ($i = 0; $i < sizeof($r); $i++) {
		if ($r[$i]['type'] != 'item') {
			$a = my_elemtree_get_children_list_complete($r[$i]['type'], $r[$i]['id']);
			$m = array_merge($m, $a);
		}
	}
	
	return $m;
}


// =============================================================================
function my_elemtree_get_children_list($type, $id) {

	$id = ''.intval($id);

	$result = array();
	
	if ($type == 'shipmodelclass') {
	
		$q = "SELECT shipmodelclass.shipmodelclass_id, ".
			" shipmodelclass.refresh ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.parent_id = '".$id."' ".
			" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
				
		for ($i = 0; $i < sizeof($qr); $i++) {
			$e = array();
			$e['type'] = 'shipmodelclass';
			$e['id'] = $qr[$i]['shipmodelclass_id'];
			$e['refresh'] = $qr[$i]['refresh'];
			$result[] = $e;
		}
		
		//
		
		$q = " SELECT shipmodel.shipmodel_id, ".
			" shipmodel.refresh ".
			" FROM shipmodel ".
			" WHERE shipmodelclass_id = '".$id."' ".
			" ORDER BY shipmodel.ti_subsort, shipmodel.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$e = array();
			$e['type'] = 'shipmodel';
			$e['id'] = $qr[$i]['shipmodel_id'];
			$e['refresh'] = $qr[$i]['refresh'];
			$result[] = $e;
		}
		
		//
		
		$q = " SELECT item.item_id, ".
			" item.refresh ".
			" FROM item ".
			" WHERE ( shipmodelclass_id = '".$id."' ) ".
			" AND ( item.ship_id = 0 ) ".
			" AND ( item.shipmodel_id = 0 ) ".
			" ORDER BY item.ti_subsort, ".
			" (item.shipmodel_str != ''), item.shipmodel_str, ".
			" (item.ship_str != ''), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$e = array();
			$e['type'] = 'item';
			$e['id'] = $qr[$i]['item_id'];
			$e['refresh'] = $qr[$i]['refresh'];
			$result[] = $e;
		}
		
	}
	
	//
	
	
	if ($type == 'shipmodel') {
	
		$q = " SELECT ship.ship_id, ".
			" ship.refresh ".
			" FROM ship ".
			" WHERE shipmodel_id = '".$id."' ".
			" ORDER BY ship.ti_subsort, (ship.factoryserialnum = 0), ship.factoryserialnum, ship.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$e = array();
			$e['type'] = 'ship';
			$e['id'] = $qr[$i]['ship_id'];
			$e['refresh'] = $qr[$i]['refresh'];
			$result[] = $e;
		}
		
		//
		
		$q = " SELECT item.item_id, ".
			" item.refresh ".
			" FROM item ".
			" WHERE ( shipmodel_id = '".$id."' ) ".
			" AND ( item.ship_id = 0 ) ".
			" ORDER BY item.ti_subsort, ".
			" (item.shipmodel_str != ''), item.shipmodel_str, ".
			" (item.ship_str != ''), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$e = array();
			$e['type'] = 'item';
			$e['id'] = $qr[$i]['item_id'];
			$e['refresh'] = $qr[$i]['refresh'];
			$result[] = $e;
		}

	}
	
	//
	
	if ($type == 'ship') {
			
		$q = " SELECT item.item_id, ".
			" item.refresh ".
			" FROM item ".
			" WHERE item.ship_id = '".$id."' ".
			" ORDER BY item.ti_subsort, ".
			" (item.shipmodel_str != ''), item.shipmodel_str, ".
			" (item.ship_str != ''), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			$e = array();
			$e['type'] = 'item';
			$e['id'] = $qr[$i]['item_id'];
			$e['refresh'] = $qr[$i]['refresh'];
			$result[] = $e;
		}
				
	}
	
	return $result;
}


// =============================================================================
function my_elemtree_set_children_refresh($type, $id) {
	
	$l = my_elemtree_get_children_list($type, $id);
	
	$hasrefresh = false;
	for ($i = 0; $i < sizeof($l); $i++) {
		if ($l[$i]['refresh'] == 'Y') {
			$hasrefresh = true;
		}
	}
	
	return true;
}


// =============================================================================
function my_clear_all_sortfield_c() {

	$q = " SELECT item.item_id, item.ship_id, item.shipmodel_id, item.shipmodelclass_id ".
		" FROM item ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		my_elemtree_set_treeindex('item', $qr[$i]['item_id'],'xxxxx');
	}
	
	return true;
}


// =============================================================================
function my_calc_item_treeindex($item_id) {
	
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
	
	//
	
	$cur_treeindex = $qr[0]['ti_self'];
	$ship_id = $qr[0]['ship_id'];
	$shipmodel_id = $qr[0]['shipmodel_id'];
	$shipmodelclass_id = $qr[0]['shipmodelclass_id'];
	$ship_has_model = $qr[0]['ship_has_model'];
	
	//
	
	$a = explode('i', $cur_treeindex, 3);
	if (sizeof($a) == 2) {
		$group_index = intval($a[1]);
	} else {
		$group_index = 9999;
	}
	
	//
	
	if ($ship_id > 0) {
		$parent_ti = my_get_elemtree_treeindex('ship', $ship_id);
		if ((!$parent_ti) || ($parent_ti == '')) {
			my_mark_element_fresh('ship', $ship_id, 'Y');
			return 'azzzi'.$group_index;
		}
		return $parent_ti.'i'.$group_index;
	}
	
	if (($ship_has_model == 'Y') && ($shipmodel_id > 0)) {
		$parent_ti = my_get_elemtree_treeindex('shipmodel', $shipmodel_id);
		if ((!$parent_ti) || ($parent_ti == '')) {
			my_mark_element_fresh('shipmodel', $shipmodel_id, 'Y');
			return 'azzzi'.$group_index;
		}
		return $parent_ti.'zi'.$group_index;
	}
	
	if ($shipmodelclass_id > 0) {
		$parent_ti = my_get_elemtree_treeindex('shipmodelclass', $shipmodelclass_id);
		if ((!$parent_ti) || ($parent_ti == '')) {
			my_mark_element_fresh('shipmodelclass', $shipmodelclass_id, 'Y');
			return 'azzzi'.$group_index;
		}
		return $parent_ti.'zzi'.$group_index;
	}
	
	return $parent_ti.'azzzi'.$group_index;
}


// =============================================================================
function treeindex_rebuild_total() {
	// my_clear_all_sortfield_c();
	my_elemtree_rebuild_treeindex_recursive('shipmodelclass', 0, 'a');
	return true;
}


?>