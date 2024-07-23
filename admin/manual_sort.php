<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/sort_manual.php');


// =============================================================================
function outhtml_script_sorter_test() {

$str = <<<SCRIPTSTRING

function js_sort_manual_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['result'] == 'string') {
		if (aresp['result'] == 'ok') {
			var elem = document.getElementById('backdiv');
			if (elem) {
				elem.style.backgroundColor = '#f8f8f8';
			}
		}
	}
	
	if (typeof aresp['errorcolor'] == 'string') {
		var elem = document.getElementById('backdiv');
		if (elem) {
			elem.style.backgroundColor = aresp['errorcolor'];
		}
	}
	
	return true;
}

function js_sorter_test_place_elem_after(id, previd, parentlevel, parent, sortlevel) {

	// alert('Place item #' + id + ' after item #' + previd);

	var url = '/xhr/sort_manual.php?parentlevel=' + parentlevel + '&parent=' + parent + '&sortlevel=' + sortlevel + '&id=' + id + '&c=move' + '&placeafter=' + previd;

	var elem = document.getElementById('backdiv');
	if (elem) {
		elem.style.backgroundColor = '#f0faff';
	}

	return ajax_my_get_query(url);
}

function js_sorter_test_onupdate(elem) {
	
	var id = elem.id;
	var prevelem = elem.previousSibling;
	if (prevelem) {
		if (prevelem.nodeType != 1) {
			prevelem = false;
		}
	}
	if (prevelem) {
		var previd = prevelem.id;
	} else {
		var previd = 0;
	}
	
	var sortlevel = elem.getAttribute('sortlevel');
	if (typeof sortlevel != 'string') return false;
	
	/*
	var elem = document.getElementById('level_hi');
	if (!elem) return false;
	var parentlevel = elem.value;
	if (typeof sortlevel == 'string') parentlevel = sortlevel;
	*/
	
	
	
	var elem = document.getElementById('parentlevel_hi');
	if (!elem) return false;
	var parentlevel = elem.value;
	
	var elem = document.getElementById('parent_hi');
	if (!elem) return false;
	var parent = elem.value;
	
	return js_sorter_test_place_elem_after(id, previd, parentlevel, parent, sortlevel);
}

(function (){

	var console = window.console;

	if( !console.log ){
		console.log = function (){
			alert([].join.apply(arguments, ' '));
		};
	}


});
	

		
SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function calc_items_sample_list() {
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.ti_parent = 'ab0b0b00b0c0d0' ".
		" ORDER BY item.ti_subsort, item.sortfield_c, item.sortfield_a ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}



// =============================================================================
function outhtml_sorter_test_list($param) {

	$out = '';
	
	$l = calc_items_sample_list();

	$out .= '<ul id="items" style="  ">';
	
	for ($i = 0; $i < sizeof($l); $i++) {
		$out .= '<li id="'.$l[$i]['item_id'].'">';
			$out .= outhtml_item_inlist_small_moderation($l[$i]['item_id']);
			// $out .= '<div style=" clear: both; "></div>';
		$out .= '</li>';
	}
	
	$out .= '</ul>';
	
	$out .= '<script>';
	$out .= ' (function (){

			var slist = document.getElementById("items");

			new Sortable(slist, {
				group: "words",
				onAdd: function (evt){ console.log(\'onAdd.slist:\', evt.item); },
				onUpdate: function (evt){ console.log(\'onUpdate.slist:\', evt.item); window.js_sorter_test_onupdate(evt.item); },
				onRemove: function (evt){ console.log(\'onRemove.slist:\', evt.item); }
			});

		})();

	';
	
	$out .= '</script>';
			
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_test($param) {

	$out = '';
	
	$out .= '<script src="/sortable/Sortable.js"></script>';
	$out .= outhtml_script_sorter_test();
	
	$out .= '<div style=" background-color: #f8f8f8f; padding-left: 0px; " >';
		
		$out .= '<div style=" padding: 20px 20px 20px 20px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; padding-left: 18px; ">Ручная сортировка знаков</h1>';
			
			$out .= '<div style=" padding-left: 20px; ">';
			
				$out .= outhtml_sorter_test_list($param);
			
			$out .= '</div>';
			
		$out .= '</div>';
		
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
		//

		$out .= '</div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_element($type, $id, $parentlevel = 0) {

	$id = ''.intval($id);
	$parentlevel = ''.intval($parentlevel);

	$out = '';
	
	$allowedtype = array('shipmodelclass','shipmodel');
	if (!is_string($type)) return false;
	if (!in_array($type, $allowedtype)) return false;
	
	$levelpad = 40;
	
	if ($type == 'shipmodelclass') {
		$vertical_margin = 8;
		
		$str = my_get_shipclass_name($id);
		$style = ' color: #404040; ';
		if ($parentlevel == 1) $style = ' color: #ffffff; ';
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
	
	if (($type == 'shipmodelclass') && ($parentlevel == 1)) $styleins = ' background-color: #66737b; color: #ffffff; font-weight: bold; padding-top: 7px; padding-bottom: 5px; ';

	$out .= '<div style=" padding-left: '.($levelpad * $parentlevel).'px; margin-top: '.$vertical_margin.'px; margin-bottom: '.$vertical_margin.'px; '.$styleins.' ">';
	
		$out .= '<a href="#" id="'.$elemidstr.'" style=" '.$style.' padding: 0px 8px 0px 8px; " onclick=" js_shipclass_tree_item_click('.$id.', \''.$type.'\'); return false; ">';
			$out .= '<span style=" font-size: 8pt; background-color: #'.$dotcolor.'; color: #ffffff; margin-right: 8px; padding: 0px 2px 0px 2px; ">'.$parentlevel.'</span>';
			
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
function outhtml_sorter_href($type, $id) {

	$scripturl = '/admin/manual_sort.php';
	
	$parentlevel = 0;
	$id = ''.intval($id);
	
	if ($type == 'shipmodelclass') {
		$parentlevel = 'class';
	}
	
	if ($type == 'shipmodel') {
		$parentlevel = 'model';
	}
	
	if ($type == 'ship') {
		$parentlevel = 'ship';
	}
	
	if ($type == 'item') {
		$parentlevel = 'item';
	}
	
	return $scripturl.'?parentlevel='.$parentlevel.'&parent='.$id;
}


// =============================================================================
function outhtml_sorter_recu_header($type, $id, $tail, $selected) {

	$out = '';
	
	if ($type == 'shipmodelclass') {
	
		if ($id < 1) {
			$str = 'Весь каталог';
			
			//$str = $qr[0]['text'];
			$insstyle = '';
			$insstyle .= ' padding: 2px 4px; float: left; margin: 2px; border-radius: 2px; color: #303030; background-color: #ffffff; ';
			$insprop = '';
			if ($selected) {
				$insstyle .= ' background-color: #6d828a; color: #e4e9ec; cursor: default; ';
			} else {
				$href = outhtml_sorter_href($type, $id);
				$insprop .= ' onclick=" window.location.href = \''.$href.'\'; " ';
				$insprop .= ' class=" hoverwgborder " ';
				$insstyle .= ' cursor: pointer; ';
			} 
			$html = '<div style=" '.$insstyle.' " '.$insprop.' >'.$str.'</div>';
			
			//
		
			$out .= '<div>';
				$out .= $html;
				// if ($tail != '') $out .= '<div style=" padding-left: 20px; " >'.$tail.'</div>';
				if ($tail != '') $out .=  '<div style=" float: left;  padding: 2px 4px; margin: 2px; color: #909090; " >&bullet;</div>';
				if ($tail != '') $out .=  $tail;
			$out .= '</div>';
			
			return $out;
		}
	
		$q = "SELECT shipmodelclass.text, ".
			" shipmodelclass.parent_id ".
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
		
		$str = $qr[0]['text'];
		$insstyle = '';
		$insstyle .= ' padding: 2px 4px; float: left; margin: 2px; border-radius: 2px; color: #303030; background-color: #ffffff; ';
		$insprop = '';
		if ($selected) {
			$insstyle .= ' background-color: #6d828a; color: #e4e9ec; cursor: default; ';
		} else {
			$href = outhtml_sorter_href($type, $id);
			$insprop .= ' onclick=" window.location.href = \''.$href.'\'; " ';
			$insprop .= ' class=" hoverwgborder " ';
			$insstyle .= ' cursor: pointer; ';
		} 
		$html = '<div style=" '.$insstyle.' " '.$insprop.' >'.$str.'</div>';
		
		//
	
		$out .= '<div>';
			$out .= outhtml_sorter_recu_header('shipmodelclass', $qr[0]['parent_id'], $html, false);
			// if ($tail != '') $out .= '<div style=" padding-left: 20px; " >'.$tail.'</div>';
			if ($tail != '') $out .=  '<div style=" float: left;  padding: 2px 4px; margin: 2px; color: #909090; " >&bullet;</div>';
				if ($tail != '') $out .=  $tail;
		$out .= '</div>';
		
		return $out;

	}
	
	if ($type == 'shipmodel') {

		if ($id < 1) {
		
			$str = 'Проект неизвестен';
			
			//$str = $qr[0]['text'];
			$insstyle = '';
			$insstyle .= ' padding: 2px 4px; float: left; margin: 2px; border-radius: 2px; color: #303030; background-color: #ffffff; ';
			$insprop = '';
			if ($selected) {
				$insstyle .= ' background-color: #6d828a; color: #e4e9ec; cursor: default; ';
			} else {
				$href = outhtml_sorter_href($type, $id);
				$insprop .= ' onclick=" window.location.href = \''.$href.'\'; " ';
				$insprop .= ' class=" hoverwgborder " ';
				$insstyle .= ' cursor: pointer; ';
			} 
			$html = '<div style=" '.$insstyle.' " '.$insprop.' >'.$str.'</div>';
			
			//
		
			$out .= '<div>';
				$out .= $html;
				// if ($tail != '') $out .= '<div style=" padding-left: 20px; " >'.$tail.'</div>';
				if ($tail != '') $out .=  '<div style=" float: left;  padding: 2px 4px; margin: 2px; color: #909090; " >&bullet;</div>';
				if ($tail != '') $out .=  $tail;
			$out .= '</div>';
			
			return $out;
		}
	
		$q = "SELECT shipmodel.name, ".
			" shipmodel.shipmodelclass_id ".
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
		
		$str = $qr[0]['name'];
		$insstyle = '';
		$insstyle .= ' padding: 2px 4px; float: left; margin: 2px; border-radius: 2px; color: #303030; background-color: #ffffff; ';
		$insprop = '';
		if ($selected) {
			$insstyle .= ' background-color: #6d828a; color: #e4e9ec; cursor: default; ';
		} else {
			$href = outhtml_sorter_href($type, $id);
			$insprop .= ' onclick=" window.location.href = \''.$href.'\'; " ';
			$insprop .= ' class=" hoverwgborder " ';
			$insstyle .= ' cursor: pointer; ';
		} 
		$html = '<div style=" '.$insstyle.' " '.$insprop.' >'.$str.'</div>';
		
		//
	
		$out .= '<div>';
			$out .= outhtml_sorter_recu_header('shipmodelclass', $qr[0]['shipmodelclass_id'], $html, false);
			// if ($tail != '') $out .= '<div style=" padding-left: 20px; " >'.$tail.'</div>';
			if ($tail != '') $out .=  '<div style=" float: left;  padding: 2px 4px; margin: 2px; color: #909090; " >&bullet;</div>';
				if ($tail != '') $out .=  $tail;
		$out .= '</div>';
		
		return $out;
		
	}
	
	if ($type == 'ship') {

		if ($id < 1) {
		
			$str = 'Корабль неизвестен';
			
			//$str = $qr[0]['text'];
			$insstyle = '';
			$insstyle .= ' padding: 2px 4px; float: left; margin: 2px; border-radius: 2px; color: #303030; background-color: #ffffff; ';
			$insprop = '';
			if ($selected) {
				$insstyle .= ' background-color: #6d828a; color: #e4e9ec; cursor: default; ';
			} else {
				$href = outhtml_sorter_href($type, $id);
				$insprop .= ' onclick=" window.location.href = \''.$href.'\'; " ';
				$insprop .= ' class=" hoverwgborder " ';
				$insstyle .= ' cursor: pointer; ';
			} 
			$html = '<div style=" '.$insstyle.' " '.$insprop.' >'.$str.'</div>';
			
			//
		
			$out .= '<div>';
				$out .= $html;
				// if ($tail != '') $out .= '<div style=" padding-left: 20px; " >'.$tail.'</div>';
				if ($tail != '') $out .=  '<div style=" float: left;  padding: 2px 4px; margin: 2px; color: #909090; " >&bullet;</div>';
				if ($tail != '') $out .=  $tail;
			$out .= '</div>';
			
			return $out;
		}
	
		$q = "SELECT ship.name, ".
			" ship.shipmodel_id ".
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
		
		$str = $qr[0]['name'];
		$insstyle = '';
		$insstyle .= ' padding: 2px 4px; float: left; margin: 2px; border-radius: 2px; color: #303030; background-color: #ffffff; ';
		$insprop = '';
		if ($selected) {
			$insstyle .= ' background-color: #6d828a; color: #e4e9ec; cursor: default; ';
		} else {
			$href = outhtml_sorter_href($type, $id);
			$insprop .= ' onclick=" window.location.href = \''.$href.'\'; " ';
			$insprop .= ' class=" hoverwgborder " ';
			$insstyle .= ' cursor: pointer; ';
		} 
		$html = '<div style=" '.$insstyle.' " '.$insprop.' >'.$str.'</div>';
		
		//
	
		$out .= '<div>';
			$out .= outhtml_sorter_recu_header('shipmodel', $qr[0]['shipmodel_id'], $html, false);
			// if ($tail != '') $out .= '<div style=" padding-left: 20px; " >'.$tail.'</div>';
			if ($tail != '') $out .=  '<div style=" float: left;  padding: 2px 4px; margin: 2px; color: #909090; " >&bullet;</div>';
				if ($tail != '') $out .=  $tail;
		$out .= '</div>';
		
		return $out;
		
	}
	
	if ($type == 'item') {
	
		$q = " SELECT item.treeindex ".
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
		return $qr[0]['treeindex'];
	
	}
	
	return false;
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_sorter_li_class($id) {

	$out = '';
	
	$str = my_get_shipclass_name($id);
	$ins_style = ' color: #404040; ';
	
	$out .= '<div class=" hoverwgborder " style=" background-color: #e0e8f0; float: left; cursor: grab;  border: solid 1px gray; border-radius: 3px; padding: 3px 6px; margin: 3px; '.$ins_style.' ">';
	
		$out .= $str;
		
		$out .= ' <span style=" color: #808080; ">(';
			$out .= my_get_shipclass_elem_children_str('shipmodelclass', $id);
		$out .= ' )</span>';
		
		if (true) {
			$href = outhtml_sorter_href('shipmodelclass', $id);
			$out .= '<div class=" hoverwgborder " style=" float: right; cursor: pointer;  border: solid 1px gray; border-radius: 3px; background-color: #c0c0c0; padding: 1px 2px; margin-left: 10px; " onclick=" window.location.href = \''.$href.'\'; ">';
				$out .= 'туда';
			$out .= '</div>';
		}
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_li_model($id) {

	$out = '';
	
	$str = my_get_shipmodel_name($id);
	$ins_style = ' color: #404040; ';
	
	$out .= '<div class=" hoverwgborder " style=" background-color: #e0f0e0; float: left; cursor: grab;  border: solid 1px gray; border-radius: 3px; padding: 3px 6px; margin: 3px; '.$ins_style.' ">';
	
		$out .= $str;
		
		$out .= ' <span style=" color: #808080; ">(';
			$out .= my_get_shipclass_elem_children_str('shipmodel', $id);
		$out .= ' )</span>';
		
		if (true) {
			$href = outhtml_sorter_href('shipmodel', $id);
			$out .= '<div class=" hoverwgborder " style=" float: right; cursor: pointer;  border: solid 1px gray; border-radius: 3px; background-color: #c0c0c0; padding: 1px 2px; margin-left: 10px; " onclick=" window.location.href = \''.$href.'\'; ">';
				$out .= 'туда';
			$out .= '</div>';
		}
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_li_ship($id) {

	$out = '';
	
	$str = my_get_ship_name($id);
	$ins_style = ' color: #404040; ';
	
	$out .= '<div class=" hoverwgborder " style=" background-color: #f0f0e0; float: left; cursor: grab;  border: solid 1px gray; border-radius: 3px; padding: 3px 6px; margin: 3px; '.$ins_style.' ">';
	
		$out .= $str;
		
		$out .= ' <span style=" color: #808080; ">(';
			$out .= my_get_shipclass_elem_children_str('ship', $id);
		$out .= ' )</span>';
		
		if (true) {
			$href = outhtml_sorter_href('ship', $id);
			$out .= '<div class=" hoverwgborder " style=" float: right; cursor: pointer;  border: solid 1px gray; border-radius: 3px; background-color: #c0c0c0; padding: 1px 2px; margin-left: 10px; " onclick=" window.location.href = \''.$href.'\'; ">';
				$out .= 'туда';
			$out .= '</div>';
		}
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_li_item($id) {

	$out = '';
	

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, item.ship_str, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.item_id = '".$id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	// bagde thumb div
	$out .= '<div style=" float: left; margin-right: 4px; margin-bottom: 10px; width: 160px; height: 160px; cursor: grab; ">';
	
		// $insprop = onclick=" window.location.href = \''.$href.'\'; "
		
		$propins = '';
		if (am_i_moderator() || am_i_lim_moderator()) {
			$href = '/item/edit.php?i='.$id;
			$propins = ' onclick=" window.location.href = \''.$href.'\'; " ';
		}
		
		// bagde image div
		$out .= '<div style=" width: 160px; height: 160px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 0px 0px; background-size: 160px 160px; background-image: url(\'/item/image.php?i='.$id.'&n=1&s=m\'); "  >';
			
			$out .= '<div style=" position:relative; display: block; width: 160px; height: 160px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " '.$propins.' >';

					
				// top overlay elements
				
				// item number
				if ($GLOBALS['is_registered_user']) {
					$out .= '<div style=" position: absolute; left: 0px; top: 0px; padding: 0px 3px 2px 3px; height: 15px; color: #808080; background-color: #ffffff; opacity: 0.7; border-radius: 3px; -moz-border-radius: 3px; font-size: 8pt; ">';
						$out .= '#'.$id;
					$out .= '</div>';
				}
				
			
			// end inner transparent layer v2
			if (am_i_moderator() || am_i_lim_moderator()) {
				$out .= '</a>'; 
			} else {
				$out .= '</div>'; 
			}
			
			$out .= '<div style=" clear: both; "></div>';

		$out .= '</div>';

		//

	$out .= '</div>';
	// end thumb
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_class($param) {

	$out = '';
	
	/*
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.parent_id ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.shipmodelclass_id = '".$root_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) return false;
	*/
	
	//
	
	
	$out .= '<script src="/sortable/Sortable.js"></script>';
	$out .= outhtml_script_sorter_test();
	
	$l = calc_sorter_class_list($param['parent']);
	
	$out .= '<div id="backdiv" style=" background-color: #f8f8f8f; padding-left: 0px; " >';
		
		$out .= '<div style=" padding: 20px 20px 20px 20px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; padding-left: 18px; ">Ручная сортировка</h1>';
			

			$out .= '<div style=" padding-left: 20px; margin-top: 20px; margin-bottom: 20px; ">';
				$out .= outhtml_sorter_recu_header('shipmodelclass', $param['parent'], '', true);
				$out .= '<div style=" clear: both; "></div>';
			$out .= '</div>';
			
			//
			
			$out .= '<div style=" margin-top: 10px; margin-bottom: 10px; height: 2px; border-top: dashed 1px gray; "></div>';
			
			//
			
			$out .= '<div style=" padding-left: 20px; ">';

				$out .= '<ul id="items1" style=" ">';
				
					$l = calc_sorter_class_list($param['parent']);
				
					for ($i = 0; $i < sizeof($l); $i++) {
						$out .= '<li id="'.$l[$i]['shipmodelclass_id'].'" sortlevel="class" style=" float: none; " >';
							$out .= outhtml_sorter_li_class($l[$i]['shipmodelclass_id']);
							$out .= '<div style=" clear: both; "></div>';
						$out .= '</li>';
					}
					
					$out .= '<div style=" clear: both; height: 2px; "></div>';
				
				$out .= '</ul>';
				
				$out .= '<script>';
				$out .= ' (function (){
						var slist = document.getElementById("items1");
						new Sortable(slist, {
							group: "class",
							onAdd: function (evt){ console.log(\'onAdd.slist:\', evt.item); },
							onUpdate: function (evt){ console.log(\'onUpdate.slist:\', evt.item); window.js_sorter_test_onupdate(evt.item); },
							onRemove: function (evt){ console.log(\'onRemove.slist:\', evt.item); }
						});
					})();
				';
				$out .= '</script>';

			$out .= '</div>';
			
			//
			
			$out .= '<div style=" margin-top: 10px; margin-bottom: 10px; height: 2px; border-top: dashed 1px gray; "></div>';
			
			//
			
			// print 'z1';
			
			$out .= '<div style=" padding-left: 20px; margin-top: 20px; ">';

				$out .= '<ul id="items2" style=" ">';
				
					$l = calc_sorter_model_list($param['parent']);
					
					// var_dump($l);
				
					for ($i = 0; $i < sizeof($l); $i++) {
						$out .= '<li id="'.$l[$i]['shipmodel_id'].'" sortlevel="model" style=" float: none; " >';
							$out .= outhtml_sorter_li_model($l[$i]['shipmodel_id']);
							$out .= '<div style=" clear: both; "></div>';
						$out .= '</li>';
					}
					
					$out .= '<div style=" clear: both; height: 2px; "></div>';
				
				$out .= '</ul>';
				
				$out .= '<script>';
				$out .= ' (function (){
						var slist2 = document.getElementById("items2");
						new Sortable(slist2, {
							group: "model",
							onAdd: function (evt){ console.log(\'onAdd.slist:\', evt.item); },
							onUpdate: function (evt){ console.log(\'onUpdate.slist:\', evt.item); window.js_sorter_test_onupdate(evt.item); },
							onRemove: function (evt){ console.log(\'onRemove.slist:\', evt.item); }
						});
					})();
				';
				$out .= '</script>';

			$out .= '</div>';
			
			//
			
		$out .= '</div>';
		
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
		//

		$out .= '</div>';

	$out .= '</div>';
	
	//
	
	
				
	//

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_model($param) {

	$out = '';
		
	$out .= '<script src="/sortable/Sortable.js"></script>';
	$out .= outhtml_script_sorter_test();
	
	$out .= '<div id="backdiv" style=" background-color: #f8f8f8f; padding-left: 0px; " >';
		
		$out .= '<div style=" padding: 20px 20px 20px 20px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; padding-left: 18px; ">Ручная сортировка</h1>';
			
			$out .= '<div style=" padding-left: 20px; margin-top: 20px; margin-bottom: 20px; ">';
				$out .= outhtml_sorter_recu_header('shipmodel', $param['parent'], '', true);
				$out .= '<div style=" clear: both; "></div>';
			$out .= '</div>';
			
			//
			
			$out .= '<div style=" margin-top: 10px; margin-bottom: 10px; height: 2px; border-top: dashed 1px gray; "></div>';
			
			//
			
			$out .= '<div style=" padding-left: 20px; ">';

				$out .= '<ul id="items1" style=" ">';
				
					$l = calc_sorter_ship_list($param['parent']);
				
					for ($i = 0; $i < sizeof($l); $i++) {
						$out .= '<li id="'.$l[$i]['ship_id'].'" style=" float: none; " sortlevel="ship" >';
							$out .= outhtml_sorter_li_ship($l[$i]['ship_id']);
							$out .= '<div style=" clear: both; "></div>';
						$out .= '</li>';
					}
					
					$out .= '<div style=" clear: both; height: 2px; "></div>';
				
				$out .= '</ul>';
				
				$out .= '<script>';
				$out .= ' (function (){
						var slist1 = document.getElementById("items1");
						new Sortable(slist1, {
							group: "ship",
							onAdd: function (evt){ console.log(\'onAdd.slist1:\', evt.item); },
							onUpdate: function (evt){ console.log(\'onUpdate.slist1:\', evt.item); window.js_sorter_test_onupdate(evt.item); },
							onRemove: function (evt){ console.log(\'onRemove.slist1:\', evt.item); }
						});
					})();
				';
				$out .= '</script>';

			$out .= '</div>';
			
			//
			
			//
			
			$out .= '<div style=" margin-top: 10px; margin-bottom: 10px; height: 2px; border-top: dashed 1px gray; "></div>';
			
			//
			
				$out .= '<div style=" padding-left: 20px; ">';

				$out .= '<ul id="items2" style=" ">';
				
					$l = calc_sorter_item_list_by_model($param['parent']);
				
					for ($i = 0; $i < sizeof($l); $i++) {
						$out .= '<li id="'.$l[$i]['item_id'].'" style=" float: left; " sortlevel="item" >';
							$out .= outhtml_sorter_li_item($l[$i]['item_id']);
							$out .= '<div style=" clear: both; "></div>';
						$out .= '</li>';
					}
					
					$out .= '<div style=" clear: both; height: 2px; "></div>';
				
				$out .= '</ul>';
				
				$out .= '<script>';
				$out .= ' (function (){
						var slist2 = document.getElementById("items2");
						new Sortable(slist2, {
							group: "item",
							onAdd: function (evt){ console.log(\'onAdd.slist2:\', evt.item); },
							onUpdate: function (evt){ console.log(\'onUpdate.slist2:\', evt.item); window.js_sorter_test_onupdate(evt.item); },
							onRemove: function (evt){ console.log(\'onRemove.slist2:\', evt.item); }
						});
					})();
				';
				$out .= '</script>';

			$out .= '</div>';
			
			//
			
		$out .= '</div>';
		
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
		//

		$out .= '</div>';

	$out .= '</div>';
	
	//
	
	
				
	//

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_sorter_ship($param) {

	$out = '';
		
	$out .= '<script src="/sortable/Sortable.js"></script>';
	$out .= outhtml_script_sorter_test();
	
	$out .= '<div id="backdiv" style=" background-color: #f8f8f8; padding-left: 0px; " >';
		
		$out .= '<div style=" padding: 20px 20px 20px 20px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; padding-left: 18px; ">Ручная сортировка</h1>';
			
			$out .= '<div style=" padding-left: 20px; margin-top: 20px; margin-bottom: 20px; ">';
				$out .= outhtml_sorter_recu_header('ship', $param['parent'], '', true);
				$out .= '<div style=" clear: both; "></div>';
			$out .= '</div>';
			
			//
			
			$out .= '<div style=" padding-left: 20px; ">';

				$out .= '<ul id="items1" style=" ">';
				
					$l = calc_sorter_item_list($param['parent']);
				
					for ($i = 0; $i < sizeof($l); $i++) {
						$out .= '<li id="'.$l[$i]['item_id'].'" style=" float: left; " sortlevel="item" >';
							$out .= outhtml_sorter_li_item($l[$i]['item_id']);
							$out .= '<div style=" clear: both; "></div>';
						$out .= '</li>';
					}
					
					$out .= '<div style=" clear: both; height: 2px; "></div>';
				
				$out .= '</ul>';
				
				$out .= '<script>';
				$out .= ' (function (){
						var slist = document.getElementById("items1");
						new Sortable(slist, {
							group: "item",
							onAdd: function (evt){ console.log(\'onAdd.slist:\', evt.item); },
							onUpdate: function (evt){ console.log(\'onUpdate.slist:\', evt.item); window.js_sorter_test_onupdate(evt.item); },
							onRemove: function (evt){ console.log(\'onRemove.slist:\', evt.item); }
						});
					})();
				';
				$out .= '</script>';

			$out .= '</div>';
			
			//
			
		$out .= '</div>';
		
		$out .= '<div style=" clear: both; height: 20px; "></div>';

	$out .= '</div>';
	
	//
	
	
				
	//

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_admin_manual_sort($param) {

	/* http://navy.webcollect.ru/admin/manual_sort.php?parentlevel=2&parent=533 */

	if (!am_i_admin()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}
	
	$GLOBALS['pagetitle'] = 'Ручная сортировка / Админ / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	//
	
	if (!isset($param['parentlevel'])) {
		$param['parentlevel'] = 'class';
		$param['parent'] = '0';
	}
	$l_plevel = array('ship', 'model', 'class');
	if (!in_array($param['parentlevel'], $l_plevel)) {
		$param['parentlevel'] = 'class';
		$param['parent'] = '0';
	}
	
	//
	
	if (!isset($param['parent'])) $param['parent'] = 'z';
	if (!ctype_digit($param['parent'])) $param['parent'] = 'z';
	if ($param['parent'] == 'z') {
		$param['parentlevel'] = 'class';
		$param['parent'] = '0';
	}
	$param['parent'] = ''.intval($param['parent']);
	
	//
	
	$out .= '<input type="hidden" id="parentlevel_hi" value="'.$param['parentlevel'].'"/>';
	$out .= '<input type="hidden" id="parent_hi" value="'.$param['parent'].'"/>';
	
	//

	if ($param['parentlevel'] == 'class') {
		$out .= outhtml_sorter_class($param);
	}
	if ($param['parentlevel'] == 'model') {
		$out .= outhtml_sorter_model($param);
	}
	if ($param['parentlevel'] == 'ship') {
		$out .= outhtml_sorter_ship($param);
	}

	//
	
	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>