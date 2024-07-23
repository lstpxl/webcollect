<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');



// =============================================================================
function my_get_item_status($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.status ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		out_silent_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return ($qr[0]['status']);
}


// =============================================================================
function my_decode_item_status($code) {
	
	if ($code == 'I') return 'Загрузка';
	if ($code == 'W') return 'На модерации';
	if ($code == 'K') return 'Проверен';
	if ($code == 'R') return 'Отклонен';
	if ($code == 'D') return 'Удален';
	if ($code == 'H') return 'Требуется помощь в идентификации';
	if ($code == 'U') return 'В хранилище';
	return '?';
}


// =============================================================================
function my_get_color_item_status($code) {
	
	if ($code == 'I') return '7b1b1b';
	if ($code == 'W') return 'd1ab68';
	if ($code == 'K') return '89a5b6';
	if ($code == 'R') return 'c07cce';
	if ($code == 'D') return '6d6d6d';
	if ($code == 'H') return '903090';
	if ($code == 'U') return '303030';
	return '?';
}


// =============================================================================
function my_get_shipclass_name($shipmodelclass_id) {

	$shipmodelclass_id = ''.intval($shipmodelclass_id);

	if ($shipmodelclass_id < 1) return false;
	
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.text ".
		"FROM shipmodelclass ".
		"WHERE shipmodelclass.shipmodelclass_id = '".$shipmodelclass_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['text'];
}


// =============================================================================
function my_get_shipclass_parent($shipmodelclass_id) {

	$shipmodelclass_id = ''.intval($shipmodelclass_id);

	if ($shipmodelclass_id < 1) return false;
	
	$q = "SELECT shipmodelclass.parent_id ".
		"FROM shipmodelclass ".
		"WHERE shipmodelclass.shipmodelclass_id = '".$shipmodelclass_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['parent_id'];
}


// =============================================================================
function my_get_shipmodel_blueprint($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);

	if ($shipmodel_id < 1) return false;
	
	$q = "SELECT shipmodel.shipmodel_id, ".
		" shipmodel.has_blueprint ".
		" FROM shipmodel ".
		" WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ($qr[0]['has_blueprint'] == 'N') return false;
	
	$path = my_get_blueprint_storage_dir().'/'.str_pad((''.$shipmodel_id), 10, '0', STR_PAD_LEFT).'.png';
	
	if (!is_file($path)) {
		$qr = mydb_query("".
			" UPDATE shipmodel ".
			" SET shipmodel.has_blueprint = 'N' ".
			" WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return false;
	}
	
	$r = my_get_blueprint_http_path().'/'.str_pad((''.$shipmodel_id), 10, '0', STR_PAD_LEFT).'.png';

	return ''.$r;
}


// =============================================================================
function my_get_shipmodel_natoc_id($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);

	if ($shipmodel_id < 1) return false;
	
	$q = "SELECT shipmodel.natoc_id ".
		"FROM shipmodel ".
		"WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['natoc_id'];
}


// =============================================================================
function my_get_ship_model_id($ship_id) {

	$ship_id = ''.intval($ship_id);

	if ($ship_id < 1) return false;
	
	$q = "SELECT ship.shipmodel_id ".
		"FROM ship ".
		"WHERE ship.ship_id = '".$ship_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['shipmodel_id'];
}


// =============================================================================
function my_get_ship_shipyard_id($ship_id) {

	$ship_id = ''.intval($ship_id);

	if ($ship_id < 1) return false;
	
	$q = "SELECT ship.shipyard_id ".
		"FROM ship ".
		"WHERE ship.ship_id = '".$ship_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['shipyard_id'];
}


// =============================================================================
function my_get_shipmodel_class($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);

	if ($shipmodel_id < 1) return false;
	
	$q = "SELECT shipmodel.shipmodel_id, ".
		" shipmodel.shipmodelclass_id ".
		"FROM shipmodel ".
		"WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['shipmodelclass_id'];
}



// =============================================================================
function my_get_ship_class($ship_id) {

	$ship_id = ''.intval($ship_id);

	if ($ship_id < 1) return false;
	
	$q = "SELECT ship.ship_id, ".
		" ship.shipmodelclass_id ".
		"FROM ship ".
		"WHERE ship.ship_id = '".$ship_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['shipmodelclass_id'];
}


// =============================================================================
function my_get_shipmodel_name($shipmodel_id, $part = 0) {

	$shipmodel_id = ''.intval($shipmodel_id);
	
	if ($shipmodel_id < 1) return false;
	
	$q = "SELECT shipmodel.shipmodel_id, ".
		" shipmodel.nick, shipmodel.numcode, shipmodel.name ".
		"FROM shipmodel ".
		"WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		//print '«'.$shipmodel_id;
		//print '«'.sizeof($qr);
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ($part == 0)	return $qr[0]['name'];
	if ($part == 1)	return $qr[0]['numcode'];
	if ($part == 2)	return $qr[0]['nick'];
	return false;
}


// =============================================================================
function my_get_shipyard_name($shipyard_id) {

	$shipyard_id = ''.intval($shipyard_id);
	
	if ($shipyard_id < 1) return false;
	
	$q = "SELECT shipyard.shipyard_id, ".
		" shipyard.name ".
		"FROM shipyard ".
		"WHERE shipyard.shipyard_id = '".$shipyard_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		//print '«'.$shipmodel_id;
		//print '«'.sizeof($qr);
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr[0]['name'];
}


// =============================================================================
function my_get_shipmodel_name_long($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);

	$q = "SELECT shipmodel.shipmodel_id, ".
		" shipmodel.nick, shipmodel.numcode, shipmodel.type, shipmodel.name ".
		"FROM shipmodel ".
		"WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$r = ''.$qr[0]['numcode'];
	if ($qr[0]['nick'] != '') $r .= ' шифр '.' «'.$qr[0]['nick'].'»';
	if ($qr[0]['type'] != '') $r .= ' тип '.' «'.$qr[0]['type'].'»';
	
	if ($r == '') $r = $qr[0]['name'];
	
	if ($r != '') {
		$r = 'Проект '.$r;
	}
		
	return $r;
}


// =============================================================================
function my_get_shipmodel_name_alldetail($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);

	$str = my_get_shipmodel_name_long($shipmodel_id);

	if ($str === false) return false;

	$natoc_id = my_get_model_natoc_id($shipmodel_id);
	if ($natoc_id > 0) {
		$s = my_get_natoc_str($natoc_id);
		$str .= ', '.$s;
	}
	
	$class_id = my_get_shipmodel_class($shipmodel_id);
	if ($class_id > 0) {
		$s = my_get_shipclass_name($class_id);
		$str .= ', '.$s;
	}
			
	return $str;
}


// =============================================================================
function get_item_shipmodel_name_full($shipmodel_id, $shipmodel_str = false) {

	if ($shipmodel_str === false) $shipmodel_str = '';

	$shipmodel_id = ''.intval($shipmodel_id);

	if ($shipmodel_str == '') {
		$result = 'Проект не указан';
	} else {
		$result = $shipmodel_str;
	}


	if ($shipmodel_id > 0) {

		$tmp = my_get_shipmodel_name_long($shipmodel_id);

		/*
		$p1 = my_get_shipmodel_name($shipmodel_id, 1);
		$p2 = my_get_shipmodel_name($shipmodel_id, 2);
		
		$tmp = '';
		if ($p1 != '') $tmp .= ' '.$p1;
		if ($p2 != '') $tmp .= ' шифр «'.$p2.'»';
		if ($tmp != '') $tmp = 'Проект '.$tmp;
		*/

		if ($tmp != '') $result = $tmp;

		//$shipmodelclass_id = my_get_shipmodel_class($shipmodel_id);
		//$shipmodelclass_str = my_get_shipclass_name($shipmodelclass_id);
	}

	return $result;
}


// =============================================================================
function my_get_ship_name($ship_id) {

	$ship_id = ''.intval($ship_id);
	
	$q = "SELECT ship.ship_id, ".
		" ship.name ".
		" FROM ship ".
		" WHERE ship.ship_id = '".$ship_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['name'];
}


// =============================================================================
function my_get_ship_factoryserialnum($ship_id) {

	$ship_id = ''.intval($ship_id);
	
	$q = "SELECT ship.ship_id, ".
		" ship.factoryserialnum ".
		" FROM ship ".
		" WHERE ship.ship_id = '".$ship_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['factoryserialnum'];
}


// =============================================================================
function my_get_model_natoc_id($model_id) {

	$model_id = ''.intval($model_id);
	
	$q = "SELECT shipmodel.shipmodel_id, ".
		" shipmodel.natoc_id ".
		"FROM shipmodel ".
		"WHERE shipmodel.shipmodel_id = '".$model_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['natoc_id'];
}


// =============================================================================
function my_get_natoc_str($natoc_id) {

	$natoc_id = ''.intval($natoc_id);
	
	$q = "SELECT natoc.text ".
		"FROM natoc ".
		"WHERE natoc.natoc_id = '".$natoc_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['text'];
}


// =============================================================================
function my_get_occasion_str($occasion_id) {

	$occasion_id = ''.intval($occasion_id);
	
	$q = "SELECT occasion.name ".
		"FROM occasion ".
		"WHERE occasion.occasion_id = '".$occasion_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['name'];
}


// =============================================================================
function my_get_item_ship_factoryserialnum($item_id) {

	$item_id = ''.intval($item_id);
	
	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_factoryserialnum_str, item.ship_id ".
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

	if ($qr[0]['ship_factoryserialnum_str'] != '') return $qr[0]['ship_factoryserialnum_str'];

	if ($qr[0]['ship_id'] > 0) {

			$q = "SELECT ship.ship_id, ".
				" ship.name ".
				"FROM ship ".
				"WHERE ship.ship_id = '".$qr[0]['ship_id']."' ";
			$qr2 = mydb_queryarray($q);
			if ($qr2 === false) {
				my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($qr2) != 1) {
				my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
				return false;
			}

			if ($qr2[0]['factoryserialnum'] != '') return $qr2[0]['factoryserialnum'];
	}
	
	return '';
}


// =============================================================================
function my_get_factory_name($factory_id) {

	$factory_id = ''.intval($factory_id);
	
	$q = "SELECT factory.factory_id, ".
		" factory.name ".
		"FROM factory ".
		"WHERE factory.factory_id = '".$factory_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['name'];
}



// =============================================================================
function my_get_itemset_name($itemset_id) {

	$itemset_id = ''.intval($itemset_id);
	
	$q = "SELECT itemset.name ".
		"FROM itemset ".
		"WHERE itemset.itemset_id = '".$itemset_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['name'];
}


// =============================================================================
function my_get_metal_name($metal_id) {

	$metal_id = ''.intval($metal_id);
	
	$q = "SELECT metal.longtext ".
		"FROM metal ".
		"WHERE metal.metal_id = '".$metal_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['longtext'];
}


// =============================================================================
function my_get_enamel_name($enamel_id) {

	$enamel_id = ''.intval($enamel_id);
	
	$q = "SELECT enamel.longtext ".
		"FROM enamel ".
		"WHERE enamel.enamel_id = '".$enamel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['longtext'];
}


// =============================================================================
function my_get_binding_name($binding_id) {

	$binding_id = ''.intval($binding_id);
	
	$q = "SELECT binding.longtext ".
		"FROM binding ".
		"WHERE binding.binding_id = '".$binding_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	return $qr[0]['longtext'];
}



// =============================================================================
function my_get_top_shipmodelclass_id($shipmodelclass_id) {

	$shipmodelclass_id = ''.intval($shipmodelclass_id);

	if ($shipmodelclass_id < 1) return false;
	
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.parent_id ".
		"FROM shipmodelclass ".
		"WHERE shipmodelclass.shipmodelclass_id = '".$shipmodelclass_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ($qr[0]['parent_id'] == 0) return $shipmodelclass_id;
	
	return my_get_top_shipmodelclass_id($qr[0]['parent_id']);
}


// =============================================================================
function get_item_itemset_id($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.itemset_id ".
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

	return $qr[0]['itemset_id'];
}


// =============================================================================
function get_item_ship_has_model($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.ship_has_model ".
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

	return $qr[0]['ship_has_model'];
}


// =============================================================================
function get_ship_has_model($ship_id) {

	$ship_id = ''.intval($ship_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT ship.has_model ".
		" FROM ship ".
		" WHERE ship.ship_id = '".$ship_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['has_model'];
}


// =============================================================================
function get_item_topshipclass_id($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.top_shipmodelclass_id ".
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

	return $qr[0]['top_shipmodelclass_id'];
}


// =============================================================================
function get_item_shipclass_id($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.shipmodelclass_id ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		print ' get_item_shipclass_id, item_id=«'.$item_id.'» ';
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['shipmodelclass_id'];
}


// =============================================================================
function get_item_sortfield_c($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.sortfield_c ".
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

	return $qr[0]['sortfield_c'];
}


// =============================================================================
function get_item_shipclass_name($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodelclass_str ".
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

	return $qr[0]['shipmodelclass_str'];
}


// =============================================================================
function get_item_shipmodel_id($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_id ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		print ' get_item_shipmodel_id, item_id=«'.$item_id.'» ';
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['shipmodel_id'];
}


// =============================================================================
function get_item_shipmodel_name($item_id, $part=0) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, item.ship_str, item.notes, item.shipmodel_id ".
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

	if ($part > 0) {
		if ($qr[0]['shipmodel_id'] > 0) {

			$q = "SELECT shipmodel.shipmodel_id, ".
				" shipmodel.nick, shipmodel.numcode, shipmodel.name ".
				"FROM shipmodel ".
				"WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ";
			$qr2 = mydb_queryarray($q);
			if ($qr2 === false) {
				my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($qr2) != 1) {
				my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
				return false;
			}

			if ($part == 1) return $qr2[0]['numcode'];
			if ($part == 2) return $qr2[0]['nick'];
		}
	}

	return $qr[0]['shipmodel_str'];
}


// =============================================================================
function get_item_ship_id($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		print ' get_item_ship_id, item_id=«'.$item_id.'» ';
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['ship_id'];
}


// =============================================================================
function get_item_is_ship_has_model($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.ship_has_model, item.ship_id ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		print ' get_item_ship_id, item_id=«'.$item_id.'» ';
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return ($qr[0]['ship_has_model'] == 'Y');
}


// =============================================================================
function get_item_ship_name($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_str ".
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

	return $qr[0]['ship_str'];
}



// =============================================================================
function get_item_occasion_str($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.occasion_id ".
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

	$str = my_get_occasion_str($qr[0]['occasion_id']);

	return $str;
}


// =============================================================================
function get_next_item_to_moderate($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.item_id ".
		" FROM item ".
		" WHERE item.status = 'W' ".
		" ORDER BY time_submit_finish DESC, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) < 1) {
		return false;
	}
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		if ($qr[$i]['item_id'] == $item_id) {
			if ($i < (sizeof($qr) - 1)) {
				return $qr[$i + 1]['item_id'];
			} else {
				return $qr[0]['item_id'];
			}
		}
	}
	
	return $qr[0]['item_id'];
}



// =============================================================================
function get_item_view_info($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.status, ".
		" item.ship_id, item.ship_str, ".
		" item.shipmodelclass_id, item.shipmodelclass_str, ".
		" item.ship_factoryserialnum_str, ".
		" item.shipmodel_id, item.shipmodel_str, ".
		" item.natoc_id, item.natoc_str, ".
		" item.notes, item.lettering, ".
		" item.extlink, ".
		" item.width, item.height, ".
		" item.metal_id, item.enamel_id, ".
		" item.binding_id, item.has_patch, ".
		" item.batchsize, item.occasion_id, ".
		" item.factory_id, item.factory_str, ".
		" item.issuedate, item.submitter_id, ".
		" item.itemset_id, item.itemset_str, ".
		" item.time_submit_start, ".
		" item.time_submit_finish, ".
		" item.moderator_id ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$data = $qr[0];
	
	$data['statusstr'] = my_decode_item_status($data['status']);
	$data['statuscolor'] = my_get_color_item_status($data['status']);

	$data['modelname'] = get_item_shipmodel_name_full($qr[0]['shipmodel_id'], $qr[0]['shipmodel_str']);
	
	$data['occasion_str'] = get_item_occasion_str($item_id);
	
	return $data;
}


?>