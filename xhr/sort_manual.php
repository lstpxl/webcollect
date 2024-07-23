<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/treeindex.php');


// =============================================================================
function outhtml_script_sort_manual() {

$str = <<<SCRIPTSTRING


function js_sort_manual_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('sort_manual_div');
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


function sort_manual_switch(item_id, reload_form) {
	if (typeof reload_form === 'undefined') reload_form = false;
	var url = '/xhr/sort_manual.php?i=' + item_id + '&c=switch';
	if (reload_form) url = url + '&reloadform=y';
	return ajax_my_get_query(url);
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function calc_sorter_class_list($parent_shipmodelclass_id) {

	//

	$parent_shipmodelclass_id = ''.intval($parent_shipmodelclass_id);

	if ($parent_shipmodelclass_id > 0) {
	
		$q = "SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$parent_shipmodelclass_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
	
	}
	
	//

	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.text, shipmodelclass.parent_id, shipmodelclass.treeindex ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.parent_id = '".$parent_shipmodelclass_id."' ".
		" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}



// =============================================================================
function calc_sorter_model_list($parent_shipmodelclass_id) {

	//

	$parent_shipmodelclass_id = ''.intval($parent_shipmodelclass_id);

	if ($parent_shipmodelclass_id > 0) {
	
		$q = "SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$parent_shipmodelclass_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
	
	}
	
	//

	$q = "SELECT shipmodel.shipmodel_id, ".
		" shipmodel.name, shipmodel.shipmodelclass_id, shipmodel.treeindex ".
		" FROM shipmodel ".
		" WHERE shipmodel.shipmodelclass_id = '".$parent_shipmodelclass_id."' ".
		" ORDER BY shipmodel.ti_subsort, shipmodel.treeindex ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}



// =============================================================================
function calc_sorter_ship_list($parent_shipmodel_id) {

	//

	$parent_shipmodel_id = ''.intval($parent_shipmodel_id);

	if ($parent_shipmodel_id > 0) {
	
		$q = "SELECT shipmodel.shipmodel_id ".
			" FROM shipmodel ".
			" WHERE shipmodel.shipmodel_id = '".$parent_shipmodel_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
	
	}
	
	//

	$q = "SELECT ship.ship_id, ".
		" ship.name, ship.shipmodel_id, ship.treeindex ".
		" FROM ship ".
		" WHERE ship.shipmodel_id = '".$parent_shipmodel_id."' ".
		" ORDER BY ship.ti_subsort, ship.treeindex ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}


// =============================================================================
function calc_sorter_item_list_by_model($parent_shipmodel_id) {

	//

	$parent_shipmodel_id = ''.intval($parent_shipmodel_id);
	
	// print $parent_ship_id;

	if ($parent_shipmodel_id > 0) {
	
		$q = "SELECT shipmodel.shipmodel_id ".
			" FROM shipmodel ".
			" WHERE shipmodel.shipmodel_id = '".$parent_shipmodel_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
	
	}
	
	//

	$q = "SELECT item.item_id, ".
		" item.lettering, item.ship_id, item.ti_parent ".
		" FROM item ".
		" WHERE item.shipmodel_id = '".$parent_shipmodel_id."' ".
		" AND item.ship_id = '0' ".
		" AND item.status = 'K' ".
		" ORDER BY ti_subsort, item.sortfield_c, item.sortfield_a ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}


// =============================================================================
function calc_sorter_item_list($parent_ship_id) {

	//

	$parent_ship_id = ''.intval($parent_ship_id);
	
	print $parent_ship_id;

	if ($parent_ship_id > 0) {
	
		$q = "SELECT ship.ship_id, ship.treeindex  ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$parent_ship_id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) return false;
	
	}
	
	//

	$q = "SELECT item.item_id, ".
		" item.lettering, item.ship_id, item.ti_parent ".
		" FROM item ".
		" WHERE item.ship_id = '".$parent_ship_id."' ".
		" AND item.status = 'K' ".
		" ORDER BY ti_subsort, item.sortfield_c, item.sortfield_a ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}


// =============================================================================
function outhtml_sort_manual_result($param) {

	$out = '';

	$param['i'] = ''.intval($param['i']);
	
	$curvalue = iurel_get_value($param['i'], $GLOBALS['user_id'], 'wantit');
	
	$out .= '<div>';
		$position = ($curvalue == 'Y');
		$text = ($position?'ДА':'НЕТ');
		$color = ($position?'fab1fd':'c8c8c8');
		$onclickstr = ' sort_manual_switch('.$param['i'].', true); return false; ';
		$out .= outhtml_switch_i1($position, $text, $color, $onclickstr);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_sort_manual_div($param) {

	$out = '';
	
	$out .= '<div id="sort_manual_div">';
		$out .= outhtml_sort_manual_result($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_sort_manual_placeafter(&$param) {

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
function do_sort_manual(&$param) {

	$l = false;
	
	if ($param['parentlevel'] == 'class') {

		my_mark_children_fresh('class', $param['parent'], true);

		if ($param['sortlevel'] == 'class') {
			$l = calc_sorter_class_list($param['parent']);
		}
		if ($param['sortlevel'] == 'model') {
			return false;
		}
		if ($param['sortlevel'] == 'ship') {
			return false;
		}
		if ($param['sortlevel'] == 'item') {
			return false;
		}
	}
	
	if ($param['parentlevel'] == 'model') {
	
		my_mark_children_fresh('model', $param['parent'], true);
	
		if ($param['sortlevel'] == 'ship') {
			// $l = calc_sorter_model_list($param['parent']);
			$l = calc_sorter_ship_list($param['parent']);
			
		}
		if ($param['sortlevel'] == 'item') {
			$l = calc_sorter_item_list_by_model($param['parent']);
		}
	}
	
	if ($param['parentlevel'] == 'ship') {
	
		my_mark_children_fresh('ship', $param['parent'], true);
	
		if ($param['sortlevel'] == 'item') {
			$l = calc_sorter_item_list($param['parent']);
		}
	}
	
	//
	
	if ($l === false) {
		$param['ajp']['errorcolor'] = 'red';
		return false;
	}
	
	if (sizeof($l) < 1) return false;
	
	//
	
	if ($param['c'] == 'move') {
	
		/* item */
	
		if ($param['sortlevel'] == 'item') {
		
			$ti_parent = $l[0]['ti_parent'];
		
			$idxi = false;
			$idxa = false;
			for ($i = 0; $i < sizeof($l); $i++) {
				if ($l[$i]['item_id'] == $param['id']) $idxi = $i;
				if ($param['placeafter'] > 0) {
					if ($l[$i]['item_id'] == $param['placeafter']) $idxa = $i;
				}
				set_item_ti_subsort($l[$i]['item_id'], (($i + 1) * 2));
				//my_mark_children_fresh('item', $l[$i]['item_id'], 'Y');
				my_mark_element_fresh('item', $l[$i]['item_id'], 'Y');
			}
			
			if ($idxi === false) return false;
			if (($param['placeafter'] > 0) && ($idxa === false)) return false;
			
			if ($param['placeafter'] > 0) {
				set_item_ti_subsort($param['id'], ((($idxa + 1) * 2 + 1)));
			} else {
				set_item_ti_subsort($param['id'], 1);
			}
			
			// $result = treeindex_rebuld_item_group($ti_parent);
			//
			
			$param['ajp']['result'] = 'ok';
			
		}
		
		/* ship */
		
		if ($param['sortlevel'] == 'ship') {
		
			// $ti_parent = $l[0]['ti_parent'];
		
			$idxi = false;
			$idxa = false;
			for ($i = 0; $i < sizeof($l); $i++) {
				if ($l[$i]['ship_id'] == $param['id']) $idxi = $i;
				if ($param['placeafter'] > 0) {
					if ($l[$i]['ship_id'] == $param['placeafter']) $idxa = $i;
				}
				set_ship_ti_subsort($l[$i]['ship_id'], (($i + 1) * 2));
			}
			
			if ($idxi === false) return false;
			if (($param['placeafter'] > 0) && ($idxa === false)) return false;
			
			if ($param['placeafter'] > 0) {
				set_ship_ti_subsort($param['id'], ((($idxa + 1) * 2 + 1)));
			} else {
				set_ship_ti_subsort($param['id'], 1);
			}
			
			// $result = treeindex_rebuld_ship_group($ti_parent);
			//
			
			$param['ajp']['result'] = 'ok';
			
		}
		
		//
		
		/* model */
		
		if ($param['sortlevel'] == 'model') {
		
			// $ti_parent = $l[0]['ti_parent'];
		
			$idxi = false;
			$idxa = false;
			for ($i = 0; $i < sizeof($l); $i++) {
				if ($l[$i]['shipmodel_id'] == $param['id']) $idxi = $i;
				if ($param['placeafter'] > 0) {
					if ($l[$i]['shipmodel_id'] == $param['placeafter']) $idxa = $i;
				}
				set_shipmodel_ti_subsort($l[$i]['shipmodel_id'], (($i + 1) * 2));
			}
			
			if ($idxi === false) return false;
			if (($param['placeafter'] > 0) && ($idxa === false)) return false;
			
			if ($param['placeafter'] > 0) {
				set_shipmodel_ti_subsort($param['id'], ((($idxa + 1) * 2 + 1)));
			} else {
				set_shipmodel_ti_subsort($param['id'], 1);
			}
			
			// $result = treeindex_rebuld_model_group($ti_parent);
			//
			
			$param['ajp']['result'] = 'ok';
			
		}
		
		//
		
		/* class */
		
		if ($param['sortlevel'] == 'class') {
		
			// $ti_parent = $l[0]['ti_parent'];
		
			$idxi = false;
			$idxa = false;
			for ($i = 0; $i < sizeof($l); $i++) {
				if ($l[$i]['shipmodelclass_id'] == $param['id']) $idxi = $i;
				if ($param['placeafter'] > 0) {
					if ($l[$i]['shipmodelclass_id'] == $param['placeafter']) $idxa = $i;
				}
				set_shipmodelclass_ti_subsort($l[$i]['shipmodelclass_id'], (($i + 1) * 2));
			}
			
			if ($idxi === false) return false;
			if (($param['placeafter'] > 0) && ($idxa === false)) return false;
			
			if ($param['placeafter'] > 0) {
				set_shipmodelclass_ti_subsort($param['id'], ((($idxa + 1) * 2 + 1)));
			} else {
				set_shipmodelclass_ti_subsort($param['id'], 1);
			}
			
			// $result = treeindex_rebuld_class_group($ti_parent);
			//
			
			$param['ajp']['result'] = 'ok';
			
		}
		
		//
	
	}
	
	if ($param['c'] == 'clearall') {
	
		$param['ajp']['result'] = 'error';
	
	}
	
}


// =============================================================================
function jqfn_sort_manual($param) {

	if (!$GLOBALS['is_registered_user']) return false;
	
	if (!am_i_admin()) return false;
	
	// parentlevel = ship, model, class
	// sortlevel = item, ship, model, class
	// c = move, clearall
	// parent (int)
	// id (int)
	// placeafter (int)
	
	if (!isset($param['parentlevel'])) return false;
	$l_plevel = array('ship', 'model', 'class');
	if (!in_array($param['parentlevel'], $l_plevel)) return false;
	
	if (!isset($param['sortlevel'])) return false;
	$l_slevel = array('item', 'ship', 'model', 'class');
	if (!in_array($param['sortlevel'], $l_slevel)) return false;
	
	if (!isset($param['parent'])) return false;
	if (!ctype_digit($param['parent'])) return false;
	$param['parent'] = ''.intval($param['parent']);
	
	if (!isset($param['c'])) return false;
	$l_command = array('move', 'clearall');
	if (!in_array($param['c'], $l_command)) return false;
	
	if (!isset($param['id'])) return false;
	if (!ctype_digit($param['id'])) return false;
	$param['id'] = ''.intval($param['id']);
	
	if ($param['c'] == 'move') {
		if (!isset($param['placeafter'])) return false;
		if (!ctype_digit($param['placeafter'])) return false;
		$param['placeafter'] = ''.intval($param['placeafter']);
	}
	
	// if (my_get_item_status($param['i']) === false) return false;
	
	//
	
	$out = '';
	
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_sort_manual_callback';
	
	do_sort_manual(&$param);
		
	
	//$result = try_update_sort_manual(&$param);

	

	
	/*
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'placeafter') {
		$param['ajp']['callback'] = 'js_sorter_test_callback';
		// $param['ajp']['elemtoplace'] = 'form_iurel_div';
		$result = try_update_sort_manual_placeafter(&$param);
		
		//$out .= outhtml_form_iurel_content($param);
	}
	*/
	
	$out .= ajax_encode_prefix($param['ajp']);
	
	header('Content-Type: text/html; charset=utf-8');
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/sort_manual.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_sort_manual($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>