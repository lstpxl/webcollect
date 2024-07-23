<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_cmselector() {

$str = <<<SCRIPTSTRING

var js_cmselector_selected = 'c0';
var js_cmselector_openkey = 'c0';
var js_cmselector_mode = 'select';
var js_cmselector_search_str = '';

function js_cmselector_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
		if (aresp['elemtoplace'] == 'cmselector_div') {
			var elem2 = document.getElementById('model_autocomplete_div');
			if (elem2) {
				elem2.innerHTML = '';
			}
		}
	}

	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('cmselector_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['show_upstore'] == 'string') {
		var elem = document.getElementById('model_natoc_upstore_div');
		if (elem) {
			if (aresp['show_upstore'] == 'yes') {	
				// alert('z1');
				elem.style.visibility = 'visible';
			}
			if (aresp['show_upstore'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['show_upstore'] == 'string') {
		var elem = document.getElementById('model_natoc_upstore_div');
		if (elem) {
			if (aresp['show_upstore'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_upstore'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	
	if (typeof aresp['show_uplink'] == 'string') {
		var elem = document.getElementById('model_natoc_uplink_div');
		if (elem) {
			if (aresp['show_uplink'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show_uplink'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	
	if (typeof aresp['color_input'] == 'string') {
		var elem = document.getElementById('model_natoc_input');
		if (elem) {
			if (aresp['color_input'] == 'red') aresp['color_input'] = 'ffd7d7';
			if (aresp['color_input'] == 'yellow') aresp['color_input'] = 'fff4ae';
			if (aresp['color_input'] == 'green') aresp['color_input'] = 'd6ffd5';
			if (aresp['color_input'] == 'purple') aresp['color_input'] = 'ffd6ff';
			elem.style.backgroundColor = '#' + aresp['color_input'];
		}
	}
	
	if (typeof aresp['focusinput'] == 'string') {
		if (aresp['focusinput'] == 'yes') {
			var elem = document.getElementById('cmselector_search_input');
			if (elem) {
				elem.focus();
				var length = elem.value.length;  
				elem.setSelectionRange(length, length);  
			}
		}
	}
	
	
	
	return true;
}


function cmselector_clear() {
	var elem = document.getElementById('cmselector_div');
	if (elem) {
		elem.innerHTML = '';
		return true;
	}
	return false;
}


function cmselector_search(q, pn) {

	if (typeof pn === 'undefined') pn = '0';

	var elem = document.getElementById('cmselector_div');
	if (elem) {
		// elem.innerHTML = 'запрос...';
	}

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
		
			
		
			var url = '/xhr/cmselector.php?i=' + item_id + '&c=search' + '&pn=' + pn + '&q=' + q + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}

function cmselector_gotopn(pn) {

	if (typeof js_cmselector_openkey != 'string') return false;

	var url = '/xhr/cmselector.php?e=' + js_cmselector_openkey + '&c=out' + '&state=open' + '&pn=' + pn + '&mode=' + js_cmselector_mode + '&searchstr=' + js_cmselector_search_str + '';
	return ajax_my_get_query(url);
	
	return false;
}


function cmselector_use(natoc_id) {
	
	natoc_id = '' + natoc_id;
	
	if (!is_numeric(natoc_id)) return false;

	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = '' + elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/cmselector.php?i=' + item_id + '&c=select' + '&natoc_id=' + natoc_id + '';
			return ajax_my_get_query(url);
		}
	}
	return false;
}


function js_cmselector_open(element) {
	js_cmselector_openkey = element;
	var url = '/xhr/cmselector.php?e=' + element + '&c=out' + '&state=open' + '&sel=' + js_cmselector_selected + '';
	return ajax_my_get_query(url);
	
	return false;
}


function js_cmselector_close() {

	var url = '/xhr/cmselector.php?e=' + js_cmselector_selected + '&c=out' + '&state=close' + '&sel=' + js_cmselector_selected + '';
	return ajax_my_get_query(url);
	
	return false;
			
	return false;
}

function js_cmselector_reset() {

	js_cmselector_selected = 'c0';
	
	var elem = document.getElementById('cmselectorinput');
	if (elem) {
		elem.value = 'c0';
	}

	var url = '/xhr/cmselector.php?e=' + js_cmselector_selected + '&c=out' + '&state=close' + '&sel=' + js_cmselector_selected + '';
	return ajax_my_get_query(url);
	
	return false;
			
	return false;
}

 
function js_cmselector_goto(element) {
	js_cmselector_openkey = element;
	var url = '/xhr/cmselector.php?e=' + element + '&c=out' + '&state=open' + '&sel=' + js_cmselector_selected + '';
	return ajax_my_get_query(url);
	
	return false;
}


function js_cmselector_select(element) {

	var elem = document.getElementById('cmselectorinput');
	if (elem) {
		elem.value = element;
	}

	js_cmselector_openkey = element;
	js_cmselector_selected = element;
	var url = '/xhr/cmselector.php?e=' + element + '&c=out' + '&state=close' + '&sel=' + js_cmselector_selected + '';
	return ajax_my_get_query(url);
	
	return false;
}

function js_cmselector_switch_mode(mode) {

	js_cmselector_mode = mode;
	
	element = 'c0';

	var url = '/xhr/cmselector.php?e=' + element + '&c=out' + '&state=open' + '&sel=' + js_cmselector_selected + '&mode=' + js_cmselector_mode + '';
	return ajax_my_get_query(url);
	
	return false;
}


function cmselector_search_search() {

	element = 'c0';

	var url = '/xhr/cmselector.php?e=' + element + '&c=out' + '&state=open' + '&sel=' + js_cmselector_selected + '&mode=' + js_cmselector_mode + '&searchstr=' + js_cmselector_search_str + '';
	return ajax_my_get_query(url);
	
	return false;
}


function cmselector_search_onchange_sub() {

	var elem = document.getElementById('cmselector_search_input');
	if (!elem) return false;
	var str = elem.value;

	if (js_cmselector_search_str != str) {
		js_cmselector_search_str = str;

		cmselector_search_search();
	}

}


function cmselector_search_onchange() {

	var elem = document.getElementById('cmselector_search_input');
	if (!elem) return false;
	var str = elem.value;

	if (js_cmselector_search_str != str) {
		
		// js_shipmodel_sel_paint('red');

		setTimeout('cmselector_search_onchange_sub()', 800);
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function get_cmselector_page_size() {
	return (10);
}


// =============================================================================
function outhtml_cmselector_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_cmselector_page_size();

	return outhtml_uni_paginator_mini($pagesize, 2, 'cmselector_gotopn', $param['pn'], $total).PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_switch_button($curmode) {

	$out = '';
	
	//$out .= '<div style=" width: 20px; height: 20px; float: left; ">';
	
	if ($curmode == 'select') {
		$label = 'выбор';
		$onclick = ' js_cmselector_switch_mode(\'select\'); return false; ';
	}
	if ($curmode == 'search') {
		$label = 'поиск';
		$onclick = ' js_cmselector_switch_mode(\'search\'); return false; ';
	}
	
		
		
		$out .= '<div class="hoverblacklightblackbg" style=" position: absolute; top: 5px; left: 15px; color: #f0f0f0; border-radius: 2px; -moz-border-radius: 2px;  margin: 2px; cursor: pointer; box-shadow: 0 1px 1px rgba(0,0,0,0.6); background-repeat: no-repeat; width: 90px; height: 15px; text-align: center; " onclick=" '.$onclick.' ">';
			$out .= $label;
		$out .= '</div>';
		
	//$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_reset_button() {

	$out = '';
	
	//$out .= '<div style=" width: 20px; height: 20px; float: left; ">';
	
		$onclick = ' js_cmselector_reset(); return false; ';
		
		$out .= '<div class="hoverblacklightblackbg" style=" position: absolute; top: 5px; right: 65px; color: #f0f0f0; border-radius: 2px; -moz-border-radius: 2px;  margin: 2px; cursor: pointer; box-shadow: 0 1px 1px rgba(0,0,0,0.6); background-repeat: no-repeat; width: 50px; height: 15px; text-align: center; " onclick=" '.$onclick.' ">';
			$out .= 'сброс';
		$out .= '</div>';
		
	//$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_close_button() {
	
	$out = '';
	
	//$out .= '<div style=" width: 20px; height: 20px; float: left; ">';
	
		$onclick = ' js_cmselector_close(); return false; ';
		
		$out .= '<div class="hoverbluelightbluebg" style="  position: absolute; top: 5px; right: 5px; color: #f0f0f0; border-radius: 2px; -moz-border-radius: 2px;  margin: 2px; cursor: pointer; box-shadow: 0 1px 1px rgba(0,0,0,0.6); background-repeat: no-repeat; width: 50px; height: 15px; text-align: center; " onclick=" '.$onclick.' ">';
			$out .= 'отмена';
		$out .= '</div>';
		
	//$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_closed($str, $onselect, $closed = true) {

	$out = '';
	
	$levelpad = 40;
	$level = 0;
	$vertical_margin = 0;
	
	//
	
	$styleins = ' margin-top: '.$vertical_margin.'px; margin-bottom: '.$vertical_margin.'px; ';
	
	$styleins .= ' width: 200px; background-color: #f0f0f0; font-size: 11px; border: solid 1px #404040; border-radius: 3px; -moz-border-radius: 3px; cursor: pointer; height: 17px; ';
	// box-shadow: inset 0 1px 2px rgba(0,0,0,0.4);
	
	//
	
	$out .= '<div style=" padding-left: '.($levelpad * $level).'px; '.$styleins.' ">';
	
		$out .= '<div style=" float: left; padding-top: 3px; padding-left: 24px; height: 17px; overflow: hidden; color: #364957; width: 150px; background-image: url(\'/images/folder_blue.png\'); background-position: 3px 1px; background-repeat: no-repeat; white-space:nowrap; " >';
			$out .= $str;
		$out .= '</div>';
		
		$bstyle = '  ';
		
		if ($closed) {
			$out .= '<div class="hoverwgborder" style=" float: right; background-color: #ffffff; border-radius: 2px; -moz-border-radius: 2px;  margin: 2px; cursor: pointer; box-shadow: 0 1px 1px rgba(0,0,0,0.6); background-image: url(\'/images/downarrow.png\'); background-position: 1px 3px; background-repeat: no-repeat; width: 16px; height: 11px; " onclick=" '.$onselect.' ">';
			$out .= '</div>';
		}
		
		$out .= '<div style=" clear: both; "></div>';
		
	$out .= '</div>';
	
	//
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_uni_element($is_parent, $str, $icon, $onselect, $onexpand, $level = 0) {

	$out = '';
	
	$levelpad = 30;
	// $level = 0;
	$vertical_margin = 2;
	$styleins = '';
	$dotcolor = '000000';
	// $str = 'Uni selector element';
	
	//
	
	$styleins = ' margin-top: '.$vertical_margin.'px; margin-bottom: '.$vertical_margin.'px; ';
	
	//
	
	$out .= '<div style=" padding-left: '.($levelpad * $level).'px; '.$styleins.' ">';
	
		$out .= '<div style=" width: 20px; height: 20px; float: left; ">';
		
			if ($onexpand != '') {
			
				if ($is_parent) {
					$collapseicon = '/images/icon_arrow_up.png';
					$collapseiconstyle = ' background-position: 1px 2px; ';
				} else {
					$collapseicon = '/images/icon_arrow_right.png';
					$collapseiconstyle = ' background-position: 2px 1px; ';
				}
				/*
				$out .= '<div style=" width: 20px; height: 20px; border: solid 1px #a0a0a0; ">';
					$out .= '[+]';
				$out .= '</div>';
				*/
				$out .= '<div class="hoverwgborder" style=" float: right; background-color: #ffffff; border-radius: 2px; -moz-border-radius: 2px;  margin: 2px; cursor: pointer; box-shadow: 0 1px 1px rgba(0,0,0,0.6); '.$collapseiconstyle.' background-image: url(\''.$collapseicon.'\');  background-repeat: no-repeat; width: 12px; height: 12px; " onclick=" '.$onexpand.' ">';
				$out .= '</div>';
			}
		
		$out .= '</div>';
	
		if ($onselect != '') {
			$onselectins = ' class="hoverwgborder" onclick=" '.$onselect.'" ';
			$styleins = ' cursor: pointer; ';
		} else {
			$onselectins = '';
			$styleins = '';
		}
		
	
		$out .= '<div '.$onselectins.' style=" float: left; padding-top: 3px; padding-left: 24px; height: 17px; overflow: hidden; color: #364957; width: 250px; background-image: url(\''.$icon.'\'); background-position: 3px 1px; background-repeat: no-repeat; white-space:nowrap; '.$styleins.' " >';
			$out .= $str;
		$out .= '</div>';
		
		$out .= '<div style=" clear: both; "></div>';
		
	$out .= '</div>';
	
	//
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_list_element($type, $id) {

	$str = get_cmselector_element_text($type, $id);
	$code = $type.$id;
	
	$onclick = ' js_cmselector_select(\''.$code.'\'); return false; ';
	
	if ($type == 'c') {
		$childerncount = calc_cmselector_element_children($code);
		if ($childerncount > 0) {
			$onclickexpand = ' js_cmselector_goto(\''.$code.'\'); return false; ';
		} else {
			$onclickexpand = '';
		}
		$icon = '/images/folder_blue.png';
	} else {
		$onclickexpand = '';
		$icon = '/images/ship1.png';
	}
	$level = 1;
	
	
	// ($is_parent, $str, $icon, $onselect, $onexpand, $level = 0) {

	return outhtml_cmselector_uni_element(false, $str, $icon, $onclick, $onclickexpand, $level);
}


// =============================================================================
function outhtml_cmselector_uplevel_element($shipmodelclass_id) {

	$str = get_cmselector_element_text('c', $shipmodelclass_id);
	$code = 'c'.$shipmodelclass_id;
	$onclick = ' js_cmselector_select(\''.$code.'\'); return false; ';
	
	$parent_code = get_cmselector_element_parent('c'.$shipmodelclass_id);
	
	$onclickexpand = ' js_cmselector_goto(\''.$parent_code.'\'); return false; ';
	$level = 0;
	
	$icon = '/images/folder_blue.png';

	return outhtml_cmselector_uni_element(true, $str, $icon, $onclick, $onclickexpand, $level);
}


// =============================================================================
function outhtml_cmselector_root_element($type, $id) {

	

	$str = 'Все проекты';
	$onclick = ' js_shipclass_tree_item_click('.$id.', \''.$type.'\'); return false; ';
	$onclickexpand = ' js_shipclass_tree_item_click('.$id.', \''.$type.'\'); return false; ';
	$level = 0;

	return outhtml_cmselector_uni_element($str, $onclick, $onclickexpand, $level);
}


// =============================================================================
function outhtml_cmselector_tree_element($type, $id, $level = 0) {

	$id = ''.intval($id);
	$level = ''.intval($level);

	$out = '';
	
	$allowedtype = array('shipmodelclass','shipmodel');
	if (!is_string($type)) return false;
	if (!in_array($type, $allowedtype)) return false;
	
	if (($type == 'shipmodelclass') && ($id == 0)) return outhtml_cmselector_root_element();
	
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
			//$has_blueprint = my_has_shipmodel_blueprint($id);
			//$str = $has_blueprint?'силуэт загружен':'без силуэта';
			$bg = 'c0c0c0';
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
function get_cmselector_element_treeindex($element) {

	if ($element == 'c0') {
		return 'a';
	}

	$a = mb_substr($element, 0, 1);
	$d = mb_substr($element, 1, 99);
	$id = ''.intval($d);
	
	if ($a == 'c') {
		
		$q = "SELECT shipmodelclass.shipmodelclass_id, shipmodelclass.treeindex ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$id."' ".
			" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
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
		
		return $qr[0]['treeindex'];
		
	}
	
	if ($a == 'm') {
		
		$q = " SELECT shipmodel_id, shipmodel.treeindex ".
			" FROM shipmodel ".
			" WHERE shipmodel.shipmodel_id = '".$id."' ".
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
		
		return $qr[0]['treeindex'];
		
	}

	//
	
	return false;
}


// =============================================================================
function get_cmselector_element_text($type, $id) {

	if ($type == 'c') {
		if ($id != 0) {
			$txt = my_get_shipclass_name($id);
			return $txt;
		} else {
			return 'Все';
		}
	}
	
	if ($type == 'm') {
		$txt = my_get_shipmodel_name_long($id);
		return $txt;
	}
	
	return false;
}



// =============================================================================
function get_cmselector_element_parent($currentelement) {

	$a = mb_substr($currentelement, 0, 1);
	$d = mb_substr($currentelement, 1, 99);
	$id = ''.intval($d);
	
	if ($a == 'c') {
		$q = " SELECT shipmodelclass_id, parent_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) < 1) {
			return false;
		}
		return 'c'.$qr[0]['parent_id'];
	} 
	
	if ($a == 'm') {
		$q = " SELECT shipmodel_id, shipmodelclass_id ".
			" FROM shipmodel ".
			" WHERE shipmodel.shipmodel_id = '".$id."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			return false;
		}
		return 'c'.$qr[0]['shipmodelclass_id'];
	}
	
	return false;
}


// =============================================================================
function calc_cmselector_element_children($currentelement) {

	$a = mb_substr($currentelement, 0, 1);
	
	if ($a == 'm') return 0;
	
	$d = mb_substr($currentelement, 1, 99);
	$id = ''.intval($d);
	
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.text, shipmodelclass.parent_id, shipmodelclass.treeindex ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.parent_id = '".$id."' ".
		" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$count = sizeof($qr);
	
	//
	
	$q = " SELECT shipmodel.shipmodel_id, shipmodel.treeindex ".
		" FROM shipmodel ".
		" WHERE shipmodelclass_id = '".$id."' ";
		" ORDER BY shipmodel.numcode, shipmodel.nick ".
		"";
	$mqr = mydb_queryarray($q);
	if ($mqr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$count += sizeof($mqr);

	//
	
	return $count;
}

// =============================================================================
function my_cmp_alpha1($a, $b) {
	$q0 = "a";
	$q1 = "a1";
	
	if ($a[$q0] < $b[$q0]) return -1;
	if ($a[$q0] > $b[$q0]) return 1;
	if ($a[$q1] < $b[$q1]) return -1;
	if ($a[$q1] > $b[$q1]) return 1;
	return 0;
}


// =============================================================================
function my_cmp_alpha2($a, $b) {
	$q0 = "a";
	$q1 = "a1";
	$q2 = "a2";
	
	if ($a[$q0] < $b[$q0]) return -1;
	if ($a[$q0] > $b[$q0]) return 1;
	if ($a[$q1] < $b[$q1]) return -1;
	if ($a[$q1] > $b[$q1]) return 1;
	if ($a[$q2] < $b[$q2]) return -1;
	if ($a[$q2] > $b[$q2]) return 1;
	return 0;
}


// =============================================================================
function my_cmp_alpha3($a, $b) {
	$q0 = "a";
	$q1 = "a1";
	$q2 = "a2";
	$q3 = "a3";
	
	if ($a[$q0] < $b[$q0]) return -1;
	if ($a[$q0] > $b[$q0]) return 1;
	if ($a[$q1] < $b[$q1]) return -1;
	if ($a[$q1] > $b[$q1]) return 1;
	if ($a[$q2] < $b[$q2]) return -1;
	if ($a[$q2] > $b[$q2]) return 1;
	if ($a[$q3] < $b[$q3]) return -1;
	if ($a[$q3] > $b[$q3]) return 1;
	return 0;
}


// =============================================================================
function calc_cmselector_search_list($str, $pagenumber=0) {

	$str = prep_string_for_search($str);
	$qstr = eyo_str($str);
	
	if (mb_strlen($qstr) < 1) return false;
	$arr = explode(' ', $qstr, 8);
	if (sizeof($arr) < 1) return false;
	
	$list = array();
	
	// shipmodelclass
	
	// prepared query
	$a = array();
	$a[] = $arr[0];
	$t = 's';
	$q = " SELECT shipmodelclass_id, text, treeindex ".
		" FROM shipmodelclass ".
		" WHERE ( LOCATE( ?, CONCAT(' ', REPLACE(LOWER(shipmodelclass.text), 'ё', 'е')) ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE( ?, CONCAT(' ', REPLACE(LOWER(shipmodelclass.text), 'ё', 'е')) ) > 0 ) ";
		$a[] = $arr[$i];
		$t .= 's';
	}
	$q .= " ORDER BY shipmodelclass.treeindex, shipmodelclass.text ".
		" LIMIT 9999 ".   
		"";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	$tokens = sizeof($arr);
	for ($i = 1; $i < sizeof($qr); $i++) {
		$e = $qr[$i];
		$e['a'] = 'c';
		$e['id'] = $e['shipmodelclass_id'];
		
		$e['a1'] = mb_strpos(mb_strtolower($e['text']), $arr[0]);
		if ($tokens > 1) $e['a2'] = mb_strpos(mb_strtolower($e['text']), $arr[1]);
		if ($tokens > 2) $e['a3'] = mb_strpos(mb_strtolower($e['text']), $arr[3]);
		
		$list[] = $e;
	}
	
	// for ($i = 1; $i < sizeof($qr); $i++) {
	// my_cmp_alpha3($a, $b)
	// usort($g, "my_cmp_alpha3");

	
	
	// shipmodel
	
	// prepared query
	$a = array();
	$a[] = $arr[0];
	$t = 's';
	$q = " SELECT shipmodel.shipmodel_id, shipmodel.shipmodelclass_id, shipmodel.name ".
		" FROM shipmodel ".
		" WHERE ( LOCATE( ?, CONCAT(' ', REPLACE(LOWER(shipmodel.name), 'ё', 'е')) ) > 0 ) ";
	for ($i = 1; $i < sizeof($arr); $i++) {
		$q .= "AND ( LOCATE( ?, CONCAT(' ', REPLACE(LOWER(shipmodel.name), 'ё', 'е')) ) > 0 ) ";
		$a[] = $arr[$i];
		$t .= 's';
	}
	$q .= " ORDER BY shipmodel.numcode, shipmodel.nick ".
		" LIMIT 9999 ".   
		"";
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	for ($i = 1; $i < sizeof($qr); $i++) {
		$e = $qr[$i];
		$e['a'] = 'm';
		$e['id'] = $e['shipmodel_id'];
		
		$e['a1'] = mb_strpos(mb_strtolower($e['name']), $arr[0]);
		if ($tokens > 1) $e['a2'] = mb_strpos(mb_strtolower($e['name']), $arr[1]);
		if ($tokens > 2) $e['a3'] = mb_strpos(mb_strtolower($e['name']), $arr[3]);
		
		$list[] = $e;
	}
	
	//
	
	if ($tokens > 2) {
		usort($list, "my_cmp_alpha3");
	} elseif ($tokens > 1) {
		usort($list, "my_cmp_alpha2");
	} elseif ($tokens > 0) {
		usort($list, "my_cmp_alpha1");
	}
	
	//
	
	$pagesize = get_cmselector_page_size();
	// $pagenumber = $param['pn'];
	if (!ctype_digit($pagenumber)) $pagenumber = 0;
	
	$total = sizeof($list);
	
	$totalpages = ceil($total / $pagesize);
	if ($pagenumber > $totalpages) $pagenumber = $totalpages;
	$from = $pagenumber * $pagesize;
	$to = $from + $pagesize;
	if ($to > $total) $to = $total;
	
	$result = array();
	
	// print '#'.$from;

	for ($i = $from; $i < $to; $i++) {
		$list[$i]['code'] = $list[$i]['a'].$list[$i]['id'];
		// $list[$i]['current'] = ($list[$i]['code'] == $currentelement);
		$result[] = $list[$i];
	}
	
	//
	
	$r = array();
	$r['list'] = $result;
	$r['total'] = $total;
	
	return $r;
}


// =============================================================================
function calc_cmselector_element_list($currentelement, $pagenumber=0) {

	$a = mb_substr($currentelement, 0, 1);
	$d = mb_substr($currentelement, 1, 99);
	$d = ''.intval($d);
	$id = ''.intval($d);
	
	//
	
	if ($a == 'c') $type = 'shipmodelclass';
	if ($a == 'm') $type = 'shipmodel';
	
	//
	
	if ($a == 'c') {
		$root_id = $id;
	
		/*
		$q = " SELECT shipmodelclass_id, parent_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$d."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			$param['element'] = 'c0';
		}
		$parent_id = $qr[0]['parent_id'];
		*/
	}
	
	if ($a == 'm') {
		$q = " SELECT shipmodel_id, shipmodelclass_id ".
			" FROM shipmodel ".
			" WHERE shipmodel.shipmodel_id = '".$d."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			$param['element'] = 'c0';
		}
		$parent_id = $qr[0]['shipmodelclass_id'];
		$root_id = $parent_id;
	}
	
	//
	
	//print $currentelement.' '.$root_id;

	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.text, shipmodelclass.parent_id, shipmodelclass.treeindex ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.parent_id = '".$root_id."' ".
		" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$q = " SELECT shipmodel.shipmodel_id, shipmodel.treeindex ".
		" FROM shipmodel ".
		" WHERE shipmodelclass_id = '".$root_id."' ";
		" ORDER BY shipmodel.numcode, shipmodel.nick ".
		"";
	$mqr = mydb_queryarray($q);
	if ($mqr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	//

	$list = array();
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		$e = array();
		$e['treeindex'] = $qr[$i]['treeindex'];
		$e['id'] = $qr[$i]['shipmodelclass_id'];
		$e['a'] = 'c';
		$list[] = $e;
	}
	
	for ($i = 0; $i < sizeof($mqr); $i++) {
		$e = array();
		$e['treeindex'] = $mqr[$i]['treeindex'];
		$e['id'] = $mqr[$i]['shipmodel_id'];
		$e['a'] = 'm';
		$list[] = $e;
	}
		
	//
	
	$pagesize = get_cmselector_page_size();
	if (!ctype_digit($pagenumber)) $pagenumber = 0;
	
	$total = sizeof($list);
	
	$totalpages = ceil($total / $pagesize);
	if ($pagenumber > $totalpages) $pagenumber = $totalpages;
	$from = $pagenumber * $pagesize;
	$to = $from + $pagesize;
	if ($to > $total) $to = $total;
	
	$result = array();

	for ($i = $from; $i < $to; $i++) {
		$list[$i]['code'] = $list[$i]['a'].$list[$i]['id'];
		$list[$i]['current'] = ($list[$i]['code'] == $currentelement);
		$result[] = $list[$i];
	}
	
	//
	
	
	$r = array();
	$r['list'] = $result;
	$r['total'] = $total;
	
	return $r;
}


// =============================================================================
function outhtml_cmselector_tree_branch($type, $id, $level = 0) {

	$id = ''.intval($id);
	$level = ''.intval($level);
	
	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=shipclass_tree_element_div_id'.$id.';';
	$out .= '-->';
	
	//
	
	if ($type == 'shipmodel') {
		$out .= outhtml_cmselector_tree_element($type, $id, ($level + 1));
		return $out;
	}
	
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

	//$out .= '<div id="shipclass_tree_element_gross_div_id'.$id.'">';
	
	if ($id > 0) $out .= outhtml_cmselector_tree_element('shipmodelclass', $id, $level);
	
	for ($i = 0; $i < sizeof($mqr); $i++) {
		$out .= outhtml_cmselector_tree_element('shipmodel', $mqr[$i]['shipmodel_id'], ($level + 1));
	}
		
	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= outhtml_cmselector_tree_element('shipmodelclass', $qr[$i]['shipmodelclass_id'], ($level + 1));
	}
	

	//$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_m_select(&$param) {

	$out = '';
	
	// $d
	// $parent_id
	
	//
	
	// check root element
	
	if (!isset($param['element'])) $param['element'] = 'c0';
	if (mb_strlen($param['element']) < 2) $param['element'] = 'c0';
	$a = mb_substr($param['element'], 0, 1);
	$d = mb_substr($param['element'], 1, 99);
	if (!(($a == 'c') || ($a == 'm'))) $param['element'] = 'c0';
	if (mb_strlen($d) < 1) $param['element'] = 'c0';
	if (mb_strlen($d) > 8) $param['element'] = 'c0';
	if (!ctype_digit($d)) $param['element'] = 'c0';
	$a = mb_substr($param['element'], 0, 1);
	$d = mb_substr($param['element'], 1, 99);
	$d = ''.intval($d);
	
	//
	
	if ($a == 'c') {
		$q = " SELECT shipmodelclass_id, parent_id ".
			" FROM shipmodelclass ".
			" WHERE shipmodelclass.shipmodelclass_id = '".$d."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			$param['element'] = 'c0';
		}
		$parent_id = $qr[0]['parent_id'];
	}
	
	if ($a == 'm') {
		$q = " SELECT shipmodel_id, shipmodelclass_id ".
			" FROM shipmodel ".
			" WHERE shipmodel.shipmodel_id = '".$d."' ".
			"";
		$qr = mydb_queryarray($q);
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			$param['element'] = 'c0';
		}
		$parent_id = $qr[0]['shipmodelclass_id'];
	}
	
	$a = mb_substr($param['element'], 0, 1);
	$d = mb_substr($param['element'], 1, 99);
	$d = ''.intval($d);
	$id = ''.intval($d);
	
	//

	if ($param['element'] != 'c0') {
		// $out .= outhtml_cmselector_uplevel_element($parent_id);
		if ($a == 'c') {
			$out .= outhtml_cmselector_uplevel_element($d);
		} else {
			$out .= outhtml_cmselector_uplevel_element($parent_id);
		}
	}
	//print '_'.$param['element'].' ';
	$listresult = calc_cmselector_element_list($param['element'], $param['pn']);
	if ($listresult) {
		$list = $listresult['list'];
		$total = $listresult['total'];
	} else {
		$list = array();
		$total = 0;
	}
	
	for ($i = 0; $i < sizeof($list); $i++) {
		$out .= outhtml_cmselector_list_element($list[$i]['a'], $list[$i]['id']);
	}
	
	
	$out .= outhtml_cmselector_paginator($param, $total);
				
	//
		
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_m_search(&$param) {

	$out = '';

	if (!isset($param['searchstr'])) $param['searchstr'] = '';
	
	//
	
	$color = '#f0f0f0';
	
	$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipmodel_input" id="cmselector_search_input" onchange="cmselector_search_onchange()"   onkeydown="cmselector_search_onchange()" onkeyup="cmselector_search_onchange()" value="'.$str = htmlspecialchars($param['searchstr'], ENT_QUOTES).'" />';
	
	//
	
	$listresult = calc_cmselector_search_list($param['searchstr'], $param['pn']);
	
	if ($listresult) {
		$list = $listresult['list'];
		$total = $listresult['total'];
	} else {
		$list = array();
		$total = 0;
	}
	
	for ($i = 0; $i < sizeof($list); $i++) {
		$out .= outhtml_cmselector_list_element($list[$i]['a'], $list[$i]['id']);
	}
	
	//
	
	$out .= outhtml_cmselector_paginator($param, $total);
	
	//
	
	$param['ajp']['focusinput'] = 'yes';
		
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_content(&$param) {

	// $param['element'] = 'c5';
	
	

	$out = '';
	
	//$out .= $str.'('.$param['e'].')';
	
	$param['element'] = $param['e'];
	
	//print '_'.$param['element'].' ';
	
	// check state
	
	if (!isset($param['state'])) $param['state'] = 'close';
	if ($param['state'] != 'open') $param['state'] = 'close';
	
	// check mode
	
	if (!isset($param['mode'])) $param['mode'] = 'select';
	if ($param['mode'] != 'select') $param['mode'] = 'search';
	
	// check page number
	
	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	$param['pn'] = ''.intval($param['pn']);
	
	//
	
	if ($a == 'c') $type = 'shipmodelclass';
	if ($a == 'm') $type = 'shipmodel';
	
	// Selected element
	
	if (!isset($param['sel'])) $param['sel'] = $param['element'];
	
	$selected_a = mb_substr($param['sel'], 0, 1);
	$selected_d = mb_substr($param['sel'], 1, 99);
	$selected_d = ''.intval($selected_d);
	
	//
	
	$str = get_cmselector_element_text($selected_a, $selected_d);
	if ($param['state'] != 'open') {
		$onselect = ' js_cmselector_open(\''.$param['sel'].'\'); return false; ';
	} else {
		$onselect = '';
	}
	
	//$out .= $str.'('.$selected_a.'-'.$selected_d.')';
	
	$out .= outhtml_cmselector_closed($str, $onselect, ($param['state'] != 'open'));
	
	//
	
	//
	
	if ($param['state'] == 'open') {

		$out .= '<div id="cmselector_popup_div">';
		
			$out .= '<div style=" z-index: 99;  position: absolute; top: 18px; left: -8px; border: solid 1px #ffffff; width: 340px; background-color: #ffffff; border-radius: 3px; -moz-border-radius: 3px; box-shadow: 0px 2px 4px rgba(0,0,0,0.3); padding: 30px 20px 20px 20px; margin-bottom: 20px; ">';
			
				if ($param['mode'] == 'select') {
					$out .= outhtml_cmselector_switch_button('search');
				} else {
					$out .= outhtml_cmselector_switch_button('select');
				}
				
				// $out .= outhtml_cmselector_switch_button('search');
			
				$out .= outhtml_cmselector_reset_button();
				
				$out .= outhtml_cmselector_close_button();
				
				//

				if ($param['mode'] == 'select') {
					$out .= outhtml_cmselector_m_select(&$param);
				} else {
					$out .= outhtml_cmselector_m_search(&$param);
				}
				
				//
			
			$out .= '</div>';
		
		$out .= '</div>';
	
	}
	
	//
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_cmselector_div($param) {

	$out = '';
	
	$out .= '<div id="cmselector_div" style=" position: relative; font-size: 11px; ">';
		$out .= outhtml_cmselector_content($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_cmselector_check_exist(&$param) {

	$param['ajp']['check_exist'] = '1';

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	$param['ajp']['color_input'] = 'yellow';
	
	/*
	if ($param['q'] == '') {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	*/
	
	$param['ajp']['check_exist'] = '2';
	
	//
	
	$originalstr = $param['q'];
	$str = $param['q'];
	
	$str = my_clean_string_ac($str);
	$str = my_clean_string_ac($str);
	
	$param['ajp']['check_exist'] = '3';
	
	if ($str == '') {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '4';
	
	
	// prepared query
	$a = array();
	$a[] = $str;
	$q = "".
		"SELECT natoc.natoc_id, natoc.text ".
		"FROM natoc ".
		"WHERE ( natoc.text = ? ) ".
		";";
	$t = 's';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	$param['ajp']['check_exist'] = '5'.$str;
	
	if (sizeof($qr) == 0) {
		$param['ajp']['show_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '6';
	
	if (sizeof($qr) > 1) {
		$param['ajp']['show_upstore'] = 'no';
		$param['ajp']['color_input'] = 'yellow';
		return true;
	}
	
	$param['ajp']['check_exist'] = '7'.$str;
	
	if ($qr[0]['name'] == $originalstr) {
		$param['ajp']['show_upstore'] = 'yes';
		$param['ajp']['color_input'] = 'purple';
		return true;
	}
	
	$param['ajp']['check_exist'] = '8'.$str;
	
	return true;
}


// =============================================================================
function jqfn_cmselector_update(&$param) {

	if (!isset($param['q'])) return false;

	if (!can_i_edit_item($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.natoc_str, item.natoc_id ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//print 'line1';
	
	$str = trim($param['q']);
	
	//----
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.natoc_id = '0',  ".
		" item.natoc_str = ? ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$a[] = $param['q'];
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//---
	
	$param['ajp']['color_input'] = 'yellow';
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function jqfn_cmselector_search(&$param) {

	$param['i'] = ''.intval($param['i']);

	if (!isset($param['q'])) $param['q'] = '';
	
	$param['q'] = trim($param['q']);
	
	$param['ajp']['elemtoplace'] = 'cmselector_div';
	$param['html'] = '';
	
	jqfn_cmselector_update(&$param);
	
	if ($param['q'] == '') {
		return true;
	}
	
	if (can_i_upstore_modelnatoc($param['i'])) {
		$param['ajp']['show_upstore'] = 'yes';
	}
	
	if (can_i_uplink_modelnatoc($param['i'])) {
		$param['ajp']['show_uplink'] = 'yes';
	}
	
	$param['html'] .= outhtml_cmselector_content(&$param);
	
	jqfn_cmselector_check_exist(&$param);
	
	return true;
}


// =============================================================================
function jqfn_cmselector_select(&$param) {
	
	if (!isset($param['natoc_id'])) return false;
	if (!ctype_digit($param['natoc_id'])) return false;
	$param['natoc_id'] = ''.intval($param['natoc_id']);
	if ($param['natoc_id'] < 1) return false;
	
	if (my_get_natoc_str($param['natoc_id']) === false) return false;
	
	
	// from model_natoc
	
	$q = " SELECT * ".
		" FROM natoc ".
		" WHERE natoc.natoc_id = '".$param['natoc_id']."' ".
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
	
	//
	
	/*
	if ($qr[0]['shipmodel_natocclass_id'] > 0) {
		$class_str = my_get_shipclass_name($qr[0]['shipmodel_natocclass_id']);
	} else {
		$class_str = '';
	}
	*/
	
	$str = my_get_natoc_str($param['natoc_id']);
	
	// prepared query
	$a = array();
	$a[] = $param['natoc_id'];
	$a[] = $str;
	$a[] = $param['i'];
	$q = "".
		" UPDATE item SET ".
		" item.natoc_id = ?, ".
		" item.natoc_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$t = 'isi';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	update_item_searchstring($param['i']);
	
	// return complete model block
	$param['ajp']['elemtoplace'] = 'form_model_div';
	$param['html'] = outhtml_form_model_content(array('i' => $param['i']));
	
	return true;
}


// =============================================================================
function jqfn_cmselector($param) {

	if (!am_i_registered_user()) return false;
	
	$param['ajp'] = array();

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_cmselector_callback';
		
	if (!isset($param['c'])) $param['c'] = '';
	
	if ($param['c'] == 'out') {
		$param['ajp']['elemtoplace'] = 'cmselector_div';
		$param['html'] = outhtml_cmselector_content(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/cmselector.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_cmselector($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>