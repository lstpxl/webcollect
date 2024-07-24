<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/treeindex.php');


// =============================================================================
function outhtml_script_shipclass_tree() {

$str = <<<SCRIPTSTRING

var item2move_id = 0;
var item2move_type = '';
var item2move_name = '';


function js_item_saveinput_get(url, params) {
	
	var temp = document.createElement('form');
	temp.action = url;
	temp.method = 'GET';
	temp.style.display = 'none';
	for (var x in params) {
		var opt = document.createElement('textarea');
		opt.name = x;
		opt.value = params[x];
		temp.appendChild(opt);
	}
	document.body.appendChild(temp);
	temp.submit();
	return temp;
}


function js_shipclass_tree_reload() {
	// /index.php?m=a&sm=c
	js_item_saveinput_get('/index.php', {m:'a', sm:'c'});
}


function js_shipclass_tree_item_move(what_id, to_id, what_type) {
	
	var url = '/xhr/shipmodeltree_move.php?what_id=' + what_id + '&parent_id=' + to_id + '&type=' + what_type + '';
	
	var XMLHttpRequestObject = false;
  if (window.XMLHttpRequest) {
    try {
      XMLHttpRequestObject = new XMLHttpRequest();
    } catch (e) {}
  } else if (window.ActiveXObject) {
    try {
      XMLHttpRequestObject = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
      try {
        XMLHttpRequestObject = new ActiveXObject('Microsoft.XMLHTTP');
      } catch (e) {}
    }
  }
  if (!XMLHttpRequestObject) return false;
  XMLHttpRequestObject.open('GET', url, true);
  XMLHttpRequestObject.onreadystatechange = function() {
    try {
      if (XMLHttpRequestObject.readyState == 4) {
        if (XMLHttpRequestObject.status == 200) {
          var response = XMLHttpRequestObject.responseText;
          delete XMLHttpRequestObject;
          XMLHttpRequestObject = null;
		  if (String(response).indexOf("blockid=") > 0) {
			  var BlockNameIndex = (String(response).indexOf("blockid=") + 8);
			  var BlockNameEnd = (String(response).indexOf(";", BlockNameIndex));
			  var IndexClean = (String(response).indexOf("-->") + 3);
			  var BlockName = String(response).substring(BlockNameIndex, BlockNameEnd);
			  if (BlockName == 'shipmodeltree_move_result') {
				var htmlclean = String(response).substring(IndexClean, String(response).length);
				if (htmlclean == 'ok') {
					js_shipclass_tree_reload();
				} else {
					if (htmlclean == 'failure') {
						alert('Произошла ошибка при перемещении (' + htmlclean + ')');
					} else {
						alert('Произошла неизвестная ошибка (' + htmlclean + ')');
					}
				}
			  }
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function js_shipclass_tree_item_click(id, type) {

	if (item2move_id == 0) {
	
		item2move_id = id;
		item2move_type = type;
		
		var elemid = type + '_tree_i_element_div_id' + id;
		var elem = document.getElementById(elemid);
		if (elem) {
			elem.style.backgroundColor = 'rgb(255, 128, 128)';
			
			item2move_name = elem.innerHTML;
		}
		
		
		if (type == 'shipmodelclass') {
			var elem = document.getElementById('class_parent_id');
			if (elem) {
				elem.value = item2move_id;
			}
			var elem = document.getElementById('model_parent_id');
			if (elem) {
				elem.value = item2move_id;
			}
		}
		
		return true;
		
	} else {
	
		if ((item2move_id == id) && (item2move_type == type)) {
		
			var elemid = type + '_tree_i_element_div_id' + id;
			var elem = document.getElementById(elemid);
			if (elem) {
				elem.style.backgroundColor = 'rgb(255, 255, 255)';
			}
			
			item2move_id = 0;
			item2move_type = '';
			item2move_name = '';
		
		} else {
	
			if (confirm('Переместить сюда «' + item2move_name + '»?')) {
			
				what_id = item2move_id;
				what_type = item2move_type;
				
				var elemid = type + '_tree_element_div_id' + item2move_id;
				var elem = document.getElementById(elemid);
				if (elem) {
				
					var color = elem.style.backgroundColor;
					if (color == 'rgb(255, 128, 128)') {
						item2move_id = 0;
						elem.style.backgroundColor = 'transparent';
						return true;
					}
				}
				
				item2move_id = 0;
				item2move_type = 0;
			
				return js_shipclass_tree_item_move(what_id, id, what_type);
				
			} else {
				return true;
			}
		
		}
		
	}
	
	return false;
}


function js_shipclass_tree_item_remove(id, type) {
	
	var url = '/xhr/shipmodeltree_del.php?id=' + id + '&type=' + type + '';
	
	var XMLHttpRequestObject = false;
  if (window.XMLHttpRequest) {
    try {
      XMLHttpRequestObject = new XMLHttpRequest();
    } catch (e) {}
  } else if (window.ActiveXObject) {
    try {
      XMLHttpRequestObject = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
      try {
        XMLHttpRequestObject = new ActiveXObject('Microsoft.XMLHTTP');
      } catch (e) {}
    }
  }
  if (!XMLHttpRequestObject) return false;
  XMLHttpRequestObject.open('GET', url, true);
  XMLHttpRequestObject.onreadystatechange = function() {
    try {
      if (XMLHttpRequestObject.readyState == 4) {
        if (XMLHttpRequestObject.status == 200) {
          var response = XMLHttpRequestObject.responseText;
          delete XMLHttpRequestObject;
          XMLHttpRequestObject = null;
		  if (String(response).indexOf("blockid=") > 0) {
			  var BlockNameIndex = (String(response).indexOf("blockid=") + 8);
			  var BlockNameEnd = (String(response).indexOf(";", BlockNameIndex));
			  var IndexClean = (String(response).indexOf("-->") + 3);
			  var BlockName = String(response).substring(BlockNameIndex, BlockNameEnd);
			  if (BlockName == 'shipmodeltree_del_result') {
				var htmlclean = String(response).substring(IndexClean, String(response).length);
				if (htmlclean == 'ok') {
					js_shipclass_tree_reload();
				} else {
					if (htmlclean == 'failure') {
						alert('Произошла ошибка при удалении (' + htmlclean + ')');
					} else {
						alert('Произошла неизвестная ошибка (' + htmlclean + ')');
					}
				}
			  }
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


function js_shipclass_tree_item_del_click(id, type) {
	
	var item2remove_name = '';

	var elemid = type + '_tree_i_element_div_id' + id;
	var elem = document.getElementById(elemid);
	if (elem) {
		item2remove_name = elem.innerHTML;
	}

	if (confirm('Удалить «' + item2remove_name + '»?')) {
		return js_shipclass_tree_item_remove(id, type);
	}
	
	return false;
}

SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}



// =============================================================================
function my_mark_element_fresh($type, $id, $fresh) {

	$atypes = array('item', 'ship', 'model', 'class');
	if (!in_array($type, $atypes)) return false;

	$id = ''.intval($id);
	
	$fresh = ($fresh == true);
	$value = $fresh?'Y':'N';
	
	if ($type == 'class') {
		$qr = mydb_query("".
			" UPDATE shipmodelclass ".
			" SET shipmodelclass.refresh = '".$value."' ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return true;
	}
	
	
	if ($type == 'model') {
		$qr = mydb_query("".
			" UPDATE shipmodel ".
			" SET shipmodel.refresh = '".$value."' ".
			" WHERE shipmodel.shipmodel_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return true;
	}
	
	
	
	if ($type == 'ship') {
		$qr = mydb_query("".
			" UPDATE ship ".
			" SET ship.refresh = '".$value."' ".
			" WHERE ship.ship_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return true;
	}

	if ($type == 'item') {
		$qr = mydb_query("".
			" UPDATE item ".
			" SET item.refresh = '".$value."' ".
			" WHERE item.item_id = '".$id."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		return true;
	}
	
	return false;
}


// =============================================================================
function my_mark_children_fresh($type, $id, $fresh) {

	if ($type == 'shipmodelclass') $type = 'class';
	if ($type == 'shipmodel') $type = 'model';

	$atypes = array('item', 'ship', 'model', 'class');
	if (!in_array($type, $atypes)) return false;
	
	if ($type == 'item') return true;

	$id = ''.intval($id);
	
	$fresh = ($fresh == true);
	//$value = $fresh?'Y':'N';
	
	//

	/*
	$d = array();
	$d['class'] = 0;
	$d['model'] = 0;
	$d['ship'] = 0;
	$d['item'] = 0;
	*/

	//
	
	if ($type == 'class') {
	
		$q = "SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.parent_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		for ($i = 0; $i < sizeof($qr); $i++) {
			my_mark_element_fresh('class', $qr[$i]['shipmodelclass_id'], $fresh);
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
		for ($i = 0; $i < sizeof($qr); $i++) {
			my_mark_element_fresh('model', $qr[$i]['shipmodel_id'], $fresh);
		}
		
		//
		
		$q = " SELECT ship.ship_id ".
			" FROM ship ".
			" WHERE ship.shipmodelclass_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		for ($i = 0; $i < sizeof($qr); $i++) {
			my_mark_element_fresh('ship', $qr[$i]['item_id'], $fresh);
		}
				
		//
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodelclass_id = '".$id."' ".
			" AND item.ship_id = '0' ".
			"";
		$qr = mydb_queryarray($q);
		for ($i = 0; $i < sizeof($qr); $i++) {
			my_mark_element_fresh('item', $qr[$i]['item_id'], $fresh);
		}
	
	}
	
	//
	
	
	if ($type == 'model') {
	
		$q = " SELECT ship.ship_id ".
			" FROM ship ".
			" WHERE ship.shipmodel_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		for ($i = 0; $i < sizeof($qr); $i++) {
			my_mark_element_fresh('ship', $qr[$i]['item_id'], $fresh);
		}
				
		//
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodel_id = '".$id."' ".
			" AND item.ship_id = '0' ".
			"";
		$qr = mydb_queryarray($q);
		for ($i = 0; $i < sizeof($qr); $i++) {
			my_mark_element_fresh('item', $qr[$i]['item_id'], $fresh);
		}
			
	}
	
	//
	
	if ($type == 'ship') {
	
		$$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.ship_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		for ($i = 0; $i < sizeof($qr); $i++) {
			my_mark_element_fresh('item', $qr[$i]['item_id'], $fresh);
		}
		
	}
	
	//
	
	return $d;
}


// =============================================================================
function my_get_shipclass_elem_children_count($type, $id) {

	$id = ''.intval($id);

	$d = array();
	$d['class'] = 0;
	$d['model'] = 0;
	$d['ship'] = 0;
	$d['item'] = 0;

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
			//$d['item'] += $z['item'];
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
			//$d['item'] += $z['item'];
		}
		
		//
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodelclass_id = '".$id."' ".
			" AND item.ship_id = '0' ".
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
			//$d['item'] += $z['item'];
		}
		
		//
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodel_id = '".$id."' ".
			" AND item.ship_id = '0' ".
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


// =============================================================================
function my_get_shipclass_elem_children_count_bysortindex($type, $id) {

	$id = ''.intval($id);
	
	$indexstr = my_get_elemtree_treeindex($type, $id);

	$d = array();
	$d['class'] = 0;
	$d['model'] = 0;
	$d['ship'] = 0;
	$d['item'] = 0;
	
	//
	
	$q = "SELECT COUNT(shipmodelclass.shipmodelclass_id) AS n ".
		" FROM shipmodelclass ".
		" WHERE ( LOCATE( '".$indexstr."', CONCAT(' ', shipmodelclass.treeindex)) > 0 ) ";
	if ($type == 'shipmodelclass') $q .= " AND ( shipmodelclass.shipmodelclass_id != '".$id."' ) ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$d['class'] += $qr[0]['n'];
	
	//
	
	$q = "SELECT COUNT(shipmodel.shipmodel_id) AS n ".
		" FROM shipmodel ".
		" WHERE ( LOCATE( '".$indexstr."', CONCAT(' ', shipmodel.treeindex)) > 0 ) ";
	if ($type == 'shipmodel') $q .= " AND ( shipmodel.shipmodel_id != '".$id."' ) ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$d['model'] += $qr[0]['n'];
	
	//
	
	$q = "SELECT COUNT(ship.ship_id) AS n ".
		" FROM ship ".
		" WHERE ( LOCATE( '".$indexstr."', CONCAT(' ', ship.treeindex)) > 0 ) ";
	if ($type == 'ship') $q .= " AND ( ship.ship_id != '".$id."' ) ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$d['ship'] += $qr[0]['n'];
	
	//
	
	$q = " SELECT COUNT(item.item_id) AS n ".
		" FROM item ".
		" WHERE (LOCATE('".$indexstr."', CONCAT(' ', item.sortfield_c)) > 0 ) ";
		$q .= " AND ( item.status = 'K' ) ";
		if ($type == 'item') $q .= " AND ( item.item_id != '".$id."' ) ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$d['item'] += $qr[0]['n'];
	
	//
	
	
	return $d;
}


// =============================================================================
function _my_elemtree_clear_treeindex_recursive($type, $id) {

	$id = ''.intval($id);

	// a topmost index
	// b shipmodelclass
	// c shipmodel
	// d ship
	// i item
	
	
	if ($type == 'shipmodelclass') {
	
		if ($id > 0) {
			$qr = mydb_query("".
				" UPDATE shipmodelclass ".
				" SET shipmodelclass.treeindex = '' ".
				" WHERE shipmodelclass.shipmodelclass_id = '".$id."' ".
				//" AND shipmodelclass.treeindex = '' ".
				"");
			if (!$qr) {
				my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
		}
		
		//
		
		/*
		$add = 1;
		
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
		*/
		
	
		$q = "SELECT shipmodelclass.shipmodelclass_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.parent_id = '".$id."' ".
			// " AND treeindex = '' ".
			" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			// $x = str_pad(''.($i + $add), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_clear_treeindex_recursive('shipmodelclass', $qr[$i]['shipmodelclass_id']);
		}
		
		// $add += sizeof($qr);
		
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
		
		
		$q = " SELECT shipmodel.shipmodel_id ".
			" FROM shipmodel ".
			" WHERE shipmodelclass_id = '".$id."' ".
			// " AND treeindex = '' ".
			" ORDER BY shipmodel.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			// $x = str_pad(''.($i + $add), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_clear_treeindex_recursive('shipmodel', $qr[$i]['shipmodel_id']);
		}
		
		// $add += sizeof($qr);
		
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
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodelclass_id = '".$id."' ".
			// " AND item.sortfield_c = '' ".
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
			//$x = str_pad(''.($i + $add), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_clear_treeindex_recursive('item', $qr[$i]['item_id']);
		}
		//$add += sizeof($qr);
	
	}
	
	//
	
	
	if ($type == 'shipmodel') {
	
		if ($id > 0) {
			$qr = mydb_query("".
				" UPDATE shipmodel ".
				" SET shipmodel.treeindex = '' ".
				" WHERE shipmodel.shipmodel_id = '".$id."' ".
				//" AND shipmodel.treeindex = '' ".
				"");
			if (!$qr) {
				my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
		}
		
		//
		
		// $add = 1;
	
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
			// " AND treeindex = '' ".
			" ORDER BY (ship.factoryserialnum = 0), ship.factoryserialnum, ship.name ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			//$x = str_pad(''.($i + $add), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_clear_treeindex_recursive('ship', $qr[$i]['ship_id']);
		}
		
		//$add += sizeof($qr);
		
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
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.shipmodel_id = '".$id."' ".
			// " AND item.sortfield_c = '' ".
			" ORDER BY (item.shipmodel_str != ''), item.shipmodel_str, ".
			" (item.ship_str != ''), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			// $x = str_pad(''.($i + $add), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_clear_treeindex_recursive('item', $qr[$i]['item_id']);
		}
		//$add += sizeof($qr);
			
	}
	
	//
	
	if ($type == 'ship') {
	
		if ($id > 0) {
			$qr = mydb_query("".
				" UPDATE ship ".
				" SET ship.treeindex = '' ".
				" WHERE ship.ship_id = '".$id."' ".
				//" AND ship.treeindex = '' ".
				"");
			if (!$qr) {
				my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
		}
		
		//
		
		//$add = 1;
	
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
		
		$q = " SELECT item.item_id ".
			" FROM item ".
			" WHERE item.ship_id = '".$id."' ".
			// " AND item.sortfield_c = '' ".
			" ORDER BY (item.shipmodel_str != ''), item.shipmodel_str, ".
			" (item.ship_str != ''), item.ship_str, ".
			" item.item_id ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		for ($i = 0; $i < sizeof($qr); $i++) {
			//$x = str_pad(''.($i + $add), $padsize, '0', STR_PAD_LEFT);
			my_elemtree_clear_treeindex_recursive('item', $qr[$i]['item_id']);
		}
		//$add += sizeof($qr);
		
	}
	
	//
	
	if ($type == 'item') {
	
		if ($id > 0) {
			$qr = mydb_query("".
				" UPDATE item ".
				" SET item.sortfield_c = '' ".
				" WHERE item.item_id = '".$id."' ".
				//" AND item.sortfield_c = '' ".
				"");
			if (!$qr) {
				my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			// update_item_searchstring($id);
			
		}
			
	}
	
	//
	
	return true;
}


// =============================================================================
function my_get_shipclass_elem_children_str($type, $id) {

	// $d = my_get_shipclass_elem_children_count($type, $id);
	$d = my_get_shipclass_elem_children_count_bysortindex($type, $id);
	
	
	$str = '';
	if ($d['class'] > 0) $str .= ''.$d['class'].'с ';
	if ($d['model'] > 0) $str .= ''.$d['model'].'п ';
	if ($d['ship'] > 0) $str .= ''.$d['ship'].'к ';
	if ($d['item'] > 0) $str .= ''.$d['item'].'з ';
	
	if ($str == '') $str = '&mdash;';
	
	return $str;
}


// =============================================================================
function my_has_shipmodel_blueprint($id) {

	$id = ''.intval($id);

	$q = " SELECT shipmodel.shipmodel_id, ".
		" shipmodel.has_blueprint ".
		" FROM shipmodel ".
		" WHERE ( shipmodel.shipmodel_id = '".$id."' ) ".
		"";
	$qr = mydb_queryarray($q);

	if ($qr === false) {
		return false;
	}
	if (sizeof($qr) != 1) {
		return false;
	}
	
	return ($qr[0]['has_blueprint'] == 'Y');
}


// =============================================================================
function outhtml_shipclass_tree_element($type, $id, $level = 0) {

	$id = ''.intval($id);
	$level = ''.intval($level);

	$out = '';
	
	$allowedtype = array('shipmodelclass','shipmodel');
	if (!is_string($type)) return false;
	if (!in_array($type, $allowedtype)) return false;
	
	$levelpad = 40;
	
	if ($type == 'shipmodelclass') {
		$vertical_margin = 8;
		
		$str = my_get_shipclass_name($id);
		$style = ' color: #404040; ';
		if ($level == 1) $style = ' color: #ffffff; ';
		$dotcolor = 'c0c0c0';
		
	} elseif ($type == 'shipmodel') {
		$vertical_margin = 1;
		$elemidstr = 'shipmodel_tree_element_div_id'.$id;
		$str = 'проект '.my_get_shipmodel_name($id);
		$style = ' color: #f00000;  font-size: 10pt; ';
		$dotcolor = 'f0c0c0';
		
	} else {
		// invalid type
	}
	
	$elemidstr = $type.'_tree_element_div_id'.$id;
	$elemidstri = $type.'_tree_i_element_div_id'.$id;
	
	$childrenstr = my_get_shipclass_elem_children_str($type, $id);
	
	$styleins = '';
	
	if (($type == 'shipmodelclass') && ($level == 1)) $styleins = ' background-color: #66737b; color: #ffffff; font-weight: bold; padding-top: 7px; padding-bottom: 5px; ';

	$out .= '<div style=" padding-left: '.($levelpad * $level).'px; margin-top: '.$vertical_margin.'px; margin-bottom: '.$vertical_margin.'px; '.$styleins.' ">';
	
		$out .= '<a href="#" id="'.$elemidstr.'" style=" '.$style.' padding: 0px 8px 0px 8px; " onclick=" js_shipclass_tree_item_click('.$id.', \''.$type.'\'); return false; ">';
			$out .= '<span style=" font-size: 8pt; background-color: #'.$dotcolor.'; color: #ffffff; margin-right: 8px; padding: 0px 2px 0px 2px; ">'.$level.'</span>';
			
			//$out .= '<span style=" color: #404080; ">'.my_get_elemtree_treeindex($type, $id).'</span>';
			
			
			$out .= '<span id="'.$elemidstri.'" >'.$str.'</span>';
		$out .= '</a>';
		
		$out .= '<span style=" font-size: 8pt; color: #a0a0a0; margin-left: 8px; padding: 0px 2px 0px 2px; ">'.$childrenstr.'</span>';
		
		$out .= '<span style=" font-size: 8pt; background-color: #'.$dotcolor.'; color: #ffffff; margin-left: 8px; padding: 0px 2px 0px 2px; ">';
			$out .= '<a href="#" id="'.$elemidstr.'" style=" '.$style.' color: #ffffff; " onclick=" js_shipclass_tree_item_del_click('.$id.', \''.$type.'\'); return false; ">';
				$out .= 'x';
			$out .= '</a>';
		$out .= '</span>';
		
		if ($type == 'shipmodel') {
			$has_blueprint = my_has_shipmodel_blueprint($id);
			$str = $has_blueprint?'силуэт загружен':'без силуэта';
			$bg = $has_blueprint?'8080c0':'c0c0c0';
			$out .= '<span style=" font-size: 8pt; background-color: #'.$bg.'; color: #ffffff; margin-left: 8px; padding: 0px 2px 0px 2px; ">';
				$out .= '<a href="/admin/blueprint.php?shipmodel_id='.$id.'"  style=" color: #ffffff; " >';
					$out .= $str;
				$out .= '</a>';
			$out .= '</span>';
		}
		
	$out .= '</div>';

	return $out;
}


// =============================================================================
function outhtml_shipclass_tree_branch($id, $level = 0) {

	$id = ''.intval($id);
	$level = ''.intval($level);
	
	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=shipclass_tree_element_div_id'.$id.';';
	$out .= '-->';
	
	//
	
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.text, shipmodelclass.parent_id ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.parent_id = '".$id."' ".
		" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$q = " SELECT shipmodel.shipmodel_id ".
		" FROM shipmodel ".
		" WHERE shipmodelclass_id = '".$id."' ";
		" ORDER BY shipmodel.numcode, shipmodel.nick ".
		"";
	$mqr = mydb_queryarray($q);
	if ($mqr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	$out .= '<div id="shipclass_tree_element_gross_div_id'.$id.'">';
	
	if ($id > 0) $out .= outhtml_shipclass_tree_element('shipmodelclass', $id, $level);
	
	for ($i = 0; $i < sizeof($mqr); $i++) {
		$out .= outhtml_shipclass_tree_element('shipmodel', $mqr[$i]['shipmodel_id'], ($level + 1));
	}
		
	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= outhtml_shipclass_tree_branch($qr[$i]['shipmodelclass_id'], ($level + 1));
	}
	

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipclass_addform() {
	
	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=shipclass_tree_addform;';
	$out .= '-->';
	
	$out .= '<div id="shipclass_tree_addform">';
	
	//

	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';

			$out .= '<div style=" ">';
			
			$link = '/index.php';
			$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';
			$out .= '<input type="hidden" name="sm" value="c" />';
			$out .= '<input type="hidden" name="m" value="a" />';
			$out .= '<input type="hidden" id="class_parent_id" name="parent_id" value="0" />';
			
			$out .= '<div style=" float: left; margin-right: 10px; padding-top: 3px; vertical-align: top; padding-right: 5px; font-size: 11pt; color: #f0f0f0; ">';
			$out .= '<strong style="  ">Добавить элемент классификации</strong>';
			$out .= '</div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; padding-right: 5px; ">';
			$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 80px; " size="30" name="text" value="" />';
			$out .= '</div>';
					
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; ">';
			$out .= '<button class="hoverwhiteborder" type="none" name="c" value="addshipmodelclass" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 100px; " >Добавить</button>';
			$out .= '</div>';
			
			$out .= '<div style=" clear: left; "></div>';

			$out .= '</form>';
			
			$out .= '</div>';
		
		$out .= '</div>';

	$out .= '</div>';


	//
	
	//

	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';

			$out .= '<div style=" ">';
			
			$link = '/index.php';
			$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';
			$out .= '<input type="hidden" name="sm" value="c" />';
			$out .= '<input type="hidden" name="m" value="a" />';
			$out .= '<input type="hidden" id="model_parent_id" name="parent_id" value="0" />';
			
			$out .= '<div style=" float: left; margin-right: 10px;  margin-bottom: 20px; padding-top: 3px; vertical-align: top; padding-right: 5px; font-size: 11pt; color: #f0f0f0; ">';
			$out .= '<strong style="  ">Добавить проект</strong>';
			$out .= '</div>';
			
			$out .= '<div style=" clear: left; "></div>';
			
			$out .= '<div style=" float: left; margin-right: 5px; padding-top: 3px; vertical-align: top; font-size: 10pt; color: #f0f0f0; ">';
			$out .= '<strong style="  ">код</strong>';
			$out .= '</div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; padding-right: 5px; ">';
			$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 80px; " size="8" name="textnumcode" value="" />';
			$out .= '</div>';
			
			
			$out .= '<div style=" float: left; margin-right: 5px; padding-top: 3px; vertical-align: top; font-size: 10pt; color: #f0f0f0; ">';
			$out .= '<strong style="  ">шифр</strong>';
			$out .= '</div>';
			
			
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; padding-right: 5px; ">';
			$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 80px; " size="20" name="textnick" value="" />';
			$out .= '</div>';
			
					
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; ">';
			$out .= '<button class="hoverwhiteborder" type="none" name="c" value="addshipmodel" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 100px; " >Добавить</button>';
			$out .= '</div>';
			
			$out .= '<div style=" clear: left; "></div>';

			$out .= '</form>';
			
			$out .= '</div>';
		
		$out .= '</div>';

	$out .= '</div>';
	
		//

	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';

			$out .= '<div style=" ">';
			
			$link = '/index.php';
			$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';
			$out .= '<input type="hidden" name="sm" value="c" />';
			$out .= '<input type="hidden" name="m" value="a" />';
			
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; ">';
				$out .= '<button class="hoverwhiteborder" type="none" name="c" value="rebuild" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 100px; " >Перестроить структуру каталога</button>';
			$out .= '</div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; ">';
				$out .= '<button class="hoverwhiteborder" type="none" name="c" value="rebuildrandom" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 100px; " >Перестроить хаот.</button>';
			$out .= '</div>';
			
			$out .= '<div style=" clear: left; "></div>';

			$out .= '</form>';
			
			$out .= '</div>';
		
		$out .= '</div>';

	$out .= '</div>';



	//

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipclass_tree_try_add_class($param) {

	$out = '';
	
	//print 'z1';

	if (!isset($param['text'])) return '';
	
	//print 'z2';
	
	if (!isset($param['parent_id'])) return '';
	//print 'z3';
	$param['parent_id'] = ''.intval($param['parent_id']);
	if (my_get_shipclass_name($param['parent_id']) === false) $param['parent_id'] = '0';

	//
	
	//print 'z4';
	
	$str = $param['text'];
	
	$str = str_replace('ё', 'е', $str);
	mb_regex_encoding('UTF-8');
	$str = mb_ereg_replace('[^а-яa-zА-ЯA-Z1-90-]', ' ', $str);
	$str = str_replace('    ', ' ', $str);
	$str = str_replace('  ', ' ', $str);
	$str = trim($str);
	
	if (mb_strlen($str) < 3) return '';
	
	if (mb_strlen($str) > 200) $str = mb_substr($str, 0);
	
	// prepared query
	$a = array();
	$a[] = $str;
	$q = "".
		" SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.text ".
		" FROM shipmodelclass ".
		" WHERE ( shipmodelclass.text = ? ) ".
		";";
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	/*
	if (sizeof($qres) > 0) {
		$out .= '<div>';
		$out .= 'Такой уже есть!';
		$out .= '</div>';
		return $out.PHP_EOL;
	}
	*/
	
	//
	
	// prepared query
	$a = array();
	$a[] = $str;
	$a[] = $param['parent_id'];
	$q = "".
		" INSERT INTO shipmodelclass ".
		" SET shipmodelclass.text = ?, ".
		" shipmodelclass.parent_id = ? ".
		";";
	$t = 'si';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$new_id = mydb_insert_id();
	if (!($new_id > 0)) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipclass_tree_try_add_model($param) {

	$out = '';

	if (!isset($param['textnumcode'])) return '';
	
	if (!isset($param['textnick'])) return '';
	
	$s = $param['textnumcode'].$param['textnick'];
	if (mb_strlen($s) < 1) {
		return ''.PHP_EOL;
	}
	
	if (!isset($param['parent_id'])) return '';
	$param['parent_id'] = ''.intval($param['parent_id']);
	if (my_get_shipclass_name($param['parent_id']) === false) $param['parent_id'] = '0';
	
	//
	
	$str = $param['textnumcode'];
	$str = str_replace('ё', 'е', $str);
	mb_regex_encoding('UTF-8');
	$str = mb_ereg_replace('[^а-яa-zА-ЯA-Z01-9-/]', ' ', $str);
	$str = str_replace('    ', ' ', $str);
	$str = str_replace('  ', ' ', $str);
	$str = trim($str);
	if (mb_strlen($str) > 200) $str = mb_substr($str, 0);
	$numcode = $str;
	
	$str = $param['textnick'];
	$str = str_replace('ё', 'е', $str);
	mb_regex_encoding('UTF-8');
	$str = mb_ereg_replace('[^а-яa-zА-ЯA-Z01-9-/]', ' ', $str);
	$str = str_replace('    ', ' ', $str);
	$str = str_replace('  ', ' ', $str);
	$str = trim($str);
	if (mb_strlen($str) > 200) $str = mb_substr($str, 0);
	$nick = $str;
	
	if (mb_strlen($numcode.$nick) < 2) return '';
	
	print '('.$numcode.' «'.$nick.'»)';
	
	// prepared query
	$a = array();
	$a[] = $numcode;
	$a[] = $nick;
	$q = "".
		" SELECT shipmodel.shipmodel_id, ".
		" shipmodel.name, ".
		" shipmodel.numcode, ".
		" shipmodel.nick ".
		" FROM shipmodel ".
		" WHERE ( shipmodel.numcode = ? ) ".
		" AND ( shipmodel.nick = ? ) ".
		";";
	$t = 'ss';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	
	if (sizeof($qr) > 0) {
		$out .= '<div>';
		$out .= 'Такой уже есть!';
		$out .= '</div>';
		return $out.PHP_EOL;
	}
	
	//
	
	$name = ''.$numcode;
	if (mb_strlen($nick) > 0) $name .= ' «'.$nick.'»';
	
	//
	
	// prepared query
	$a = array();
	$a[] = $numcode;
	$a[] = $nick;
	$a[] = $name;
	$a[] = $param['parent_id'];
	$q = "".
		" INSERT INTO shipmodel ".
		" SET shipmodel.numcode = ?,  ".
		" shipmodel.nick = ?, ".
		" shipmodel.name = ?, ".
		" shipmodel.shipmodelclass_id = ? ".
		";";
	$t = 'sssi';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$new_id = mydb_insert_id();
	if (!($new_id > 0)) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//

	return $out.PHP_EOL;
}




// =============================================================================
function my_check_links() {

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
		
	for ($i = 0; $i < sizeof($qr); $i++) {
	
		my_elemtree_set_treeindex('item', $qr[$i]['item_id'],'zzzzz');

		if ($qr[$i]['ship_id'] > 0) {
			if (my_get_ship_name($qr[$i]['ship_id']) === false) {
				$qr = mydb_query("".
					" UPDATE item ".
					" SET item.ship_id = '0' ".
					" WHERE item.item_id = '".$qr[$i]['item_id']."' ".
					"");
				if (!$qr) {
					my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
			}
		}
		
		if ($qr[$i]['shipmodel_id'] > 0) {
			if (my_get_shipmodel_name($qr[$i]['shipmodel_id']) === false) {
				$qr = mydb_query("".
					" UPDATE item ".
					" SET item.shipmodel_id = '0' ".
					" WHERE item.item_id = '".$qr[$i]['item_id']."' ".
					"");
				if (!$qr) {
					my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
			}
		}
		
		if ($qr[$i]['shipmodelclass_id'] > 0) {
			if (my_get_shipclass_name($qr[$i]['shipmodelclass_id']) === false) {
				$qr = mydb_query("".
					" UPDATE item ".
					" SET item.shipmodelclass_id = '0' ".
					" WHERE item.item_id = '".$qr[$i]['item_id']."' ".
					"");
				if (!$qr) {
					my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
			}
		}
	
	}

}



// =============================================================================
function my_check_links2() {

	$q = " SELECT item.item_id, item.ship_id, item.shipmodel_id, item.shipmodelclass_id ".
		" FROM item ".
		" WHERE (LOCATE('z', item.sortfield_c) > 0) ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
		
	for ($i = 0; $i < sizeof($qr); $i++) {
	
		if ($qr[$i]['shipmodelclass_id'] > 0) my_elemtree_rebuild_treeindex_local('shipmodelclass', $qr[$i]['shipmodelclass_id']);
		if ($qr[$i]['shipmodel_id'] > 0) my_elemtree_rebuild_treeindex_local('shipmodel', $qr[$i]['shipmodel_id']);
		if ($qr[$i]['ship_id'] > 0) my_elemtree_rebuild_treeindex_local('ship', $qr[$i]['ship_id']);
		if ($qr[$i]['item_id'] > 0) my_elemtree_rebuild_treeindex_local('item', $qr[$i]['item_id']);
	}

}


// =============================================================================
function my_check_links3_rec($type, $id) {


	if ($type == 'shipmodelclass') {
		$parent_id = my_get_shipclass_parent($id);
		if ($parent_id === false) {
			print '('.$type.' id='.$id.')';
			return false;
		}
		if ($parent_id > 0) {
			return my_check_links3_rec('shipmodelclass', $parent_id);
		} else {
			return true;
		}
	}
	
	if ($type == 'shipmodel') {
		$parent_id = my_get_shipmodel_class($id);
		if ($parent_id === false) {
			print '('.$type.' id='.$id.')';
			return false;
		}
		if ($parent_id > 0) {
			return my_check_links3_rec('shipmodelclass', $parent_id);
		} else {
			return true;
		}
	}
	
	if ($type == 'ship') {
		$parent_id = my_get_ship_model_id($id);
		if ($parent_id === false) {
			print '('.$type.' id='.$id.')';
			return false;
		}
		if ($parent_id > 0) {
			return my_check_links3_rec('shipmodel', $parent_id);
		} else {
			return true;
		}
	}
	
	if ($type == 'item') {
	
		$parent_id = get_item_ship_id($id);
		if ($parent_id === false) {
			print '('.$type.' id='.$id.')';
			return false;
		}
		if ($parent_id > 0) {
			return my_check_links3_rec('ship', $parent_id);
		} else {
			return true;
		}
		
		//
		
		$parent_id = get_item_shipmodel_id($id);
		if ($parent_id === false) {
			print '('.$type.' id='.$id.')';
			return false;
		}
		if ($parent_id > 0) {
			return my_check_links3_rec('shipmodel', $parent_id);
		} else {
			return true;
		}
		
		//
		
		$parent_id = get_item_shipclass_id($id);
		if ($parent_id === false) {
			print '('.$type.' id='.$id.')';
			return false;
		}
		if ($parent_id > 0) {
			return my_check_links3_rec('shipmodelclass', $parent_id);
		} else {
			return true;
		}
		
	}

	return false;
}


// =============================================================================
function my_check_links3() {

	$q = " SELECT ".
		" item.item_id, item.ship_id, ".
		" item.shipmodel_id, item.shipmodelclass_id ".
		" FROM item ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
		
	for ($i = 0; $i < sizeof($qr); $i++) {
		if ($qr[$i]['ship_id'] > 0) my_check_links3_rec('ship', $qr[$i]['ship_id']);
		if ($qr[$i]['shipmodel_id'] > 0) my_check_links3_rec('shipmodel', $qr[$i]['shipmodel_id']);
		if ($qr[$i]['shipmodelclass_id'] > 0) my_check_links3_rec('shipmodelclass', $qr[$i]['shipmodelclass_id']);
	}

	return true;
}


// =============================================================================
function outhtml_shipclass_tree_result_rebuild_struct($param) {

	// my_elemtree_build_treeindex();
	
	// my_check_links();
	// my_elemtree_rebuild_treeindex_recursive('shipmodelclass', 0, 'a');
	//my_check_links2();
	// my_check_links3();
	
	//my_clear_all_sortfield_c();
	//my_elemtree_rebuild_treeindex_recursive('shipmodelclass', 0, 'a');
	
	treeindex_rebuild_total();
	
	return '';
}



// =============================================================================
function outhtml_shipclass_tree_result_rebuild_random($param) {

	$q = " SELECT item.item_id ".
		" FROM item ".
		" WHERE item.status = 'K' ".
		" AND item.sortfield_c = 'xxxxx' ".
		" ORDER BY item.item_id ".
		" LIMIT 25 ".
		" ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		my_calc_item_treeindex_v($qr[$i]['item_id']);
	}
	
	//

	$q = " SELECT item.item_id ".
		" FROM item ".
		" WHERE item.status = 'K' ".
		" ORDER BY item.item_id ".
		" ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	for ($i = 0; $i < 50; $i++) {
		$k = rand(0, sizeof($qr));
		for ($x = $k; $x < ($k + 9); $x++) {
			//my_elemtree_rebuild_treeindex_local('item', $qr[$x]['item_id']);
			if ($x < sizeof($qr)) {
				my_calc_item_treeindex_v($qr[$x]['item_id']);
			}
		}
	}
	
	return '';
}



// =============================================================================
function outhtml_shipclass_tree_result($param) {
	
	$out = '';
	
	$out .= outhtml_script_shipclass_tree();
	
	if (isset($param['c'])) {
		if ($param['c'] == 'addshipmodelclass') {
			$out .= outhtml_shipclass_tree_try_add_class($param);
		}
		if ($param['c'] == 'addshipmodel') {
			$out .= outhtml_shipclass_tree_try_add_model($param);
		}
		if ($param['c'] == 'rebuild') {
			$out .= outhtml_shipclass_tree_result_rebuild_struct($param);
		}
		if ($param['c'] == 'rebuildrandom') {
			$out .= outhtml_shipclass_tree_result_rebuild_random($param);
		}
	}
	

	
	$out .= '<!--';
	$out .= 'blockid=shipclass_tree_div;';
	$out .= '-->';

	$out .= '<div>';
	$out .= outhtml_shipclass_tree_branch(0);
	$out .= '</div>';
	
	$out .= '<div>';
	$out .= outhtml_shipclass_addform();
	$out .= '</div>';	
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipclass_tree($param) {
	
	$out = '';
	
	$out .= '<div id="shipclass_tree_div">';
	$out .= outhtml_shipclass_tree_result($param);
	$out .= '</div>';
	
	return $out.PHP_EOL;
}

?>
