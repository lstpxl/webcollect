<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_classification.php');


// =============================================================================
function outhtml_script_shipmodel_upstore() {

$str = <<<SCRIPTSTRING

function js_shipmodel_upstore_callback(aresp) {

	// alert('a');

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('shipmodel_upstore_div');
		if (elem) {
			if (aresp['display'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['display_autocomplete'] == 'string') {
		var elem = document.getElementById('model_autocomplete_div');
		if (elem) {
			if (aresp['display_autocomplete'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display_autocomplete'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['display_uplink'] == 'string') {
		var elem = document.getElementById('shipmodel_uplink_div');
		if (elem) {
			if (aresp['display_uplink'] == 'yes') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display_uplink'] == 'no') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	if (typeof aresp['color'] == 'string') {
		var elem = document.getElementById('shipmodel_input');
		if (elem) {
			elem.style.backgroundColor = '#' + aresp['color'];
		}
	}
	
	if (typeof aresp['reload_model_form'] == 'string') {
		if (aresp['reload_model_form'] == 'yes') {
			var func = window['item_classification_reload'];
			if (func) {
				if (typeof func == 'function') {
					func();
				}
			}
		}
	}
	
	if (typeof aresp['focusinput'] == 'string') {
		if (aresp['focusinput'] == 'yes') {
			var elem = document.getElementById('shipmodel_textsep_code');
			if (elem) {
				elem.focus();
				var length = elem.value.length;  
				elem.setSelectionRange(length, length);  
			}
		}
	}
	
	if (typeof aresp['shipmodel_upstore_after_sep'] == 'string') {
		if (aresp['shipmodel_upstore_after_sep'] == 'yes') {
			shipmodel_upstore_click();
		}
	}
	
	
	
	return true;
}


function js_shipmodel_upstore_query(item_id, c) {
	
	var url = '/xhr/shipmodel_upstore.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function shipmodel_upstore_click() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_shipmodel_upstore_query(item_id, 'upstore');
	}
}


function shipmodel_upstore_refresh() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_shipmodel_upstore_query(item_id, '');
	}
}

function shipmodel_upstore_hide() {
	var elem = document.getElementById('shipmodel_upstore_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function shipmodel_upstore_show() {
	var elem = document.getElementById('shipmodel_upstore_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}



function js_shipmodel_text_sep_open(item_id) {

	var elem = document.getElementById('shipmodel_text_sep_popup_div');
	if (elem) {
		elem.style.visibility = 'visible';
	} 

	var url = '/xhr/shipmodel_upstore.php?i=' + item_id + '&state=open' + '';
	return ajax_my_get_query(url);
	
	return false;
}


function js_shipmodel_text_sep_close() {

	var elem = document.getElementById('shipmodel_text_sep_popup_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	} else {
		return false;
	}
}

function js_shipmodel_text_sep_ok(item_id) {
	
	var elem = document.getElementById('shipmodel_textsep_code');
	if (elem) {
		var str_code = elem.value;
	} else {
		return false;
	}
	
	var elem = document.getElementById('shipmodel_textsep_nick');
	if (elem) {
		var str_nick = elem.value;
	} else {
		return false;
	}
	
	var elem = document.getElementById('shipmodel_textsep_type');
	if (elem) {
		var str_type = elem.value;
	} else {
		return false;
	}
	
	var url = '/xhr/shipmodel_upstore.php';
	var params = new Array();
	params['i'] = item_id;
	params['state'] = 'close';
	params['c'] = 'saveandclose';
	params['str_code'] = str_code;
	params['str_nick'] = str_nick;
	params['str_type'] = str_type;
	return ajax_my_post_query(url, params);
			
	return false;
}



SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}



// =============================================================================
function outhtml_shipmodel_text_sep_ok_button($item_id) {

	$out = '';
	
	//$out .= '<div style=" width: 20px; height: 20px; float: left; ">';
	
		$onclick = ' js_shipmodel_text_sep_ok('.$item_id.'); return false; ';
		
		$out .= '<div class="hoverbluelightbluebg" style=" float: left; color: #f0f0f0; border-radius: 2px; -moz-border-radius: 2px;  margin: 2px; cursor: pointer; box-shadow: 0 1px 1px rgba(0,0,0,0.6); background-repeat: no-repeat; width: 140px; height: 19px; text-align: center; padding-top: 5px;  " onclick=" '.$onclick.' ">';
			$out .= 'Сохранить и добавить';
		$out .= '</div>';
		
	//$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipmodel_text_sep_close_button() {
	
	$out = '';
	
	//$out .= '<div style=" width: 20px; height: 20px; float: left; ">';
	
		$onclick = ' js_shipmodel_text_sep_close(); return false; ';
		
		$out .= '<div class="hoverbluelightbluebg" style=" float: left; color: #f0f0f0; border-radius: 2px; -moz-border-radius: 2px;  margin: 2px; cursor: pointer; box-shadow: 0 1px 1px rgba(0,0,0,0.6); background-repeat: no-repeat; width: 140px; height: 19px; text-align: center; padding-top: 5px; " onclick=" '.$onclick.' ">';
			$out .= 'Отмена';
		$out .= '</div>';
		
	//$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipmodel_text_sep_popup(&$param) {

	$out = '';

	//

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, ".
		" item.shipmodel_code_str, ".
		" item.shipmodel_nick_str, ".
		" item.shipmodel_type_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$str_code = $qr[0]['shipmodel_code_str'];
	$str_nick = $qr[0]['shipmodel_nick_str'];
	$str_type = $qr[0]['shipmodel_type_str'];
	
	if ($str_code == '') $str_code = $qr[0]['shipmodel_str'];
	
	//

	$out .= '<div id="shipmodel_text_sep_popup_div">';
	
		$out .= '<div style=" z-index: 98;  position: absolute; top: 18px; left: -8px; border: solid 1px #ffffff; width: 360px; background-color: #ffffff; border-radius: 3px; -moz-border-radius: 3px; box-shadow: 0px 2px 4px rgba(0,0,0,0.3); padding: 20px 20px 20px 20px; margin-bottom: 20px; ">';
		
			$out .= '<div style=" color: #606060; margin: 2px; text-align: left; font-size: 14px; margin-bottom: 10px; margin-left: 60px; " >';
				$out .= 'Проверьте написание';
			$out .= '</div>';
			
			$out .= '<div style=" color: #606060; margin: 2px; text-align: left; font-size: 12px; margin-bottom: 10px; margin-left: 60px; " >';
				$out .= 'Шифр и тип указывайте без кавычек';
			$out .= '</div>';
			
			$out .= '<div style=" clear: both; margin-bottom: 12px; "></div>';

			/*
			$str_code = '1';
			$str_nick = '2';
			$str_type = '3';
			*/
			
			$out .= '<div style=" float: left; width: 50px; color: #606060; margin: 6px 10px 2px 2px; text-align: right; font-size: 12px; " >';
				$out .= 'Проект:';
			$out .= '</div>';

			$out .= '<input class="hoverwhiteborder" style=" float: left; text-align: left; padding-right: 10px; font-size: 12px; background-color: #f0f0f0; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipmodel_textsep_code" id="shipmodel_textsep_code" value="'. htmlspecialchars($str_code, ENT_QUOTES).'" />';
			
			$out .= '<div style=" clear: both; margin-bottom: 6px; "></div>';
			
			$out .= '<div style=" float: left; width: 50px; color: #606060; margin: 6px 10px 2px 2px; text-align: right; font-size: 12px; " >';
				$out .= 'Шифр:';
			$out .= '</div>';
			
			$out .= '<input class="hoverwhiteborder" style=" float: left; text-align: left; padding-right: 10px; font-size: 12px; background-color: #f0f0f0; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipmodel_textsep_nick" id="shipmodel_textsep_nick" value="'.htmlspecialchars($str_nick, ENT_QUOTES).'" />';
			
			$out .= '<div style=" clear: both; margin-bottom: 6px; "></div>';
			
			$out .= '<div style=" float: left; width: 50px; color: #606060; margin: 6px 10px 2px 2px; text-align: right; font-size: 12px; " >';
				$out .= 'Тип:';
			$out .= '</div>';
			
			$out .= '<input class="hoverwhiteborder" style=" float: left; text-align: left; padding-right: 10px; font-size: 12px; background-color: #f0f0f0; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipmodel_textsep_type" id="shipmodel_textsep_type" value="'.htmlspecialchars($str_type, ENT_QUOTES).'" />';
			
			$out .= '<div style=" clear: both; margin-bottom: 12px; "></div>';
		
			/*
			if ($param['mode'] == 'select') {
				$out .= outhtml_shipmodel_text_sep_switch_button('search');
			} else {
				$out .= outhtml_shipmodel_text_sep_switch_button('select');
			}
			*/
			
			// $out .= outhtml_shipmodel_text_sep_switch_button('search');
			
			
			$out .= '<div style=" float: left; width: 60px; height: 10px; "></div>';
		
			$out .= outhtml_shipmodel_text_sep_ok_button($param['i']);
			
			$out .= outhtml_shipmodel_text_sep_close_button();
			
			//

			/*
			if ($param['mode'] == 'select') {
				$out .= outhtml_shipmodel_text_sep_m_select(&$param);
			} else {
				$out .= outhtml_shipmodel_text_sep_m_search(&$param);
			}
			*/
			
			//
		
		$out .= '</div>';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipmodel_upstore_result($param) {

	$out = '';
	
	// if (!am_i_admin_or_moderator()) return '';
	//if (!can_i_shipmodel_upstore($param)) return '';
	
	
	if (can_i_shipmodel_upstore($param)) {
		$param['ajp']['display'] = 'yes';
	} else {
		$param['ajp']['display'] = 'no';
	}
	
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_str, ".
		" item.shipmodel_id, item.shipmodel_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	//
	
	// beautify here
	// $qr[0]['shipmodel_str'] = trim($qr[0]['shipmodel_str']);
	// if ($qr[0]['shipmodel_str'] == '') return '';
	
	//
	
	if ($qr[0]['ship_id'] > 0) {
	
		$qrs = mydb_queryarray("".
			" SELECT ship.ship_id, ".
			" ship.shipmodel_id ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ".
			"");
		if ($qrs === false) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qrs) != 1) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
	
	}
	
	//
	
	
	//
	$onselect = ' js_shipmodel_text_sep_open('.$param['i'].'); return false; ';
	
	/* shipmodel_upstore_click(); return false; */ 
	
	// 3f6b86
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_add.png\'); " onclick=" '.$onselect.' " title=" добавить проект с таким кодом, шифром и классификацией">';
	$out .= '</div>';
	
	if ($param['state'] == 'open') {
		$out .= outhtml_shipmodel_text_sep_popup(&$param);
		$param['ajp']['focusinput'] = 'yes';
		
	}
	
		
	/*
	if ($s == '') {
		$param['ajp']['display'] = 'no';
	} else {
		$param['ajp']['display'] = 'yes';
	}
	*/

	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_shipmodel_upstore_div($param) {

	$out = '';
	
	// $out .= outhtml_script_shipmodel_upstore();
	
	// $out .= '<input type="hidden" id="shipmodel_upstore_item_id" value="'.$param['i'].'" />';
	
	if (can_i_shipmodel_upstore($param)) {
		$insertstyle = ' visibility: visible; ';
		// $out .= 'z';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="shipmodel_upstore_div" style="  position: relative; '.$insertstyle.'" >';
	
		$out .= outhtml_shipmodel_upstore_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function check_shipmodel_exists($str) {
	
	// prepared query
	$a = array();
	$a[] = $str;
	$t = 's';
	$q = "".
		" SELECT ".
		" shipmodel.shipmodel_id ".
		" FROM shipmodel ".
		" WHERE shipmodel.name = ? ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return (sizeof($qres) > 0);
}


// =============================================================================
function can_i_shipmodel_upstore($param) {

	$param['i'] = ''.intval($param['i']);

	if (!can_i_upstore_shipmodel($param['i'])) return false;
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_str, ".
		" item.shipmodel_id, item.shipmodel_str, ".
		" item.natoc_id, item.natoc_str, ".
		" item.shipmodelclass_id, item.shipmodelclass_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//

	if ($qr[0]['shipmodel_str'] == '') return false;
	
	if ($qr[0]['shipmodel_id'] != 0) return false;
	
	return true;
	
	
	// if ($qr[0]['shipmodel_id'] > 0) return false;


	// return check_shipmodel_exists($str);
}


// =============================================================================
function try_shipmodel_upstore(&$param) {

	// if (!am_i_admin_or_moderator()) return false;
	//if ($param['c'] != 'upstore') return false;
	if (!can_i_upstore_shipmodel($param['i'])) return false;
	
	// print 'k';
	
	$param['i'] = ''.intval($param['i']);
	
	//$param['ajp']['stop'] = '1';

	//

	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.ship_id, item.ship_str, ".
		" item.shipmodel_id, ".
		" item.shipmodel_str, ".
		" item.shipmodel_code_str, ".
		" item.shipmodel_nick_str, ".
		" item.shipmodel_type_str, ".
		" item.natoc_id, item.natoc_str, ".
		" item.shipmodelclass_id, item.shipmodelclass_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	
	
	//
	
	//$param['ajp']['stop'] = '2';

	if ($qr[0]['shipmodel_str'] == '') return false;
	
	//$param['ajp']['stop'] = '3';

	//$str = my_beautify_item_shipmodel_str($qr[0]['shipmodel_str']);
	
	// print_r($str);
	
	//$strarr = my_break_item_shipmodel_str($str);
	
	//
	
	
	//
	
	$str = $qr[0]['shipmodel_code_str'];
	if ($qr[0]['shipmodel_nick_str'] != '') $str .= ' шифр «'.$qr[0]['shipmodel_nick_str'].'»';
	if ($qr[0]['shipmodel_type_str'] != '') $str .= ' тип «'.$qr[0]['shipmodel_type_str'].'»';
	
	
	//$param['ajp']['stop'] = '3';
	
	// prepared query
	$a = array();
	$a[] = $qr[0]['shipmodelclass_id'];
	$a[] = $str;
	$a[] = $qr[0]['shipmodel_code_str'];
	$a[] = $qr[0]['shipmodel_nick_str'];
	$a[] = $qr[0]['shipmodel_type_str'];
	$a[] = $qr[0]['natoc_id'];
	//print_r($strarr);
	$t = 'issssi';
	$q = "".
		" INSERT INTO shipmodel SET ".
		" shipmodel.refresh = 'Y', ".
		" shipmodel.shipmodelclass_id = ?, ".
		" shipmodel.name = ?, ".
		" shipmodel.numcode = ?, ".
		" shipmodel.nick = ?, ".
		" shipmodel.type = ?, ".
		" shipmodel.natoc_id = ? ".
		";";
	$qru = mydb_prepquery($q, $t, $a);
	if ($qru === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		//out_silent_error("Aargh!");
		//$param['ajp']['stop'] = 'epq';
		return false;
	}
	// end of prepared query
	
	//$param['ajp']['stop'] = '4';

	
	$new_id = mydb_insert_id();
	if (!($new_id > 0)) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//$param['ajp']['stop'] = '5';
	
	my_elemtree_rebuild_treeindex_local('shipmodel', $new_id);

	// prepared query
	$a = array();
	$a[] = $new_id;
	$a[] = $str;
	$a[] = $param['i'];
	//print_r($strarr);
	$t = 'isi';
	$q = "".
		" UPDATE item SET ".
		" item.shipmodel_id = ?, ".
		" item.shipmodel_str = ? ".
		" WHERE item.item_id = ? ".
		";";
	$qru = mydb_prepquery($q, $t, $a);
	if ($qru === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		//out_silent_error("Aargh!");
		return false;
	}
	
	//
	
	//$param['ajp']['color'] = 'd6ffd5';
	//$param['ajp']['display'] = 'no';
	//$param['ajp']['display_uplink'] = 'yes';
	
	// print 'w';
	
	update_item_searchstring($param['i']);
	
	//$param['ajp']['elemtoplace'] = 'item_classification_div';
	//$param['html'] = outhtml_item_classification_content(array('i' => $param['i']));
	$param['ajp']['reload_model_form'] = 'yes';
	
	return true;
}


// =============================================================================
function jqfn_shipmodel_text_sep_saveandclose(&$param) {
	
	if (!isset($param['str_code'])) return false;
	if (!isset($param['str_nick'])) return false;
	if (!isset($param['str_type'])) return false;
	
	$param['str_code'] = trim($param['str_code']);
	$param['str_nick'] = trim($param['str_nick']);
	$param['str_type'] = trim($param['str_type']);
	
	$str = $param['str_code'];
	if ($param['str_nick'] != '') $str .= ' шифр «'.$param['str_nick'].'»';
	if ($param['str_type'] != '') $str .= ' тип «'.$param['str_type'].'»';
	
	// prepared query
	$a = array();
	$a[] = $param['str_code'];
	$a[] = $param['str_nick'];
	$a[] = $param['str_type'];
	$a[] = $str;
	$a[] = $param['i'];
	$q = "".
		" UPDATE item SET ".
		" item.shipmodel_code_str = ?, ".
		" item.shipmodel_nick_str = ?, ".
		" item.shipmodel_type_str = ?, ".
		" item.shipmodel_str = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$t = 'ssssi';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	update_item_searchstring($param['i']);
	
	$param['ajp']['reload_model_form'] = 'yes';
	// $param['ajp']['shipmodel_upstore_after_sep'] = 'yes';
	
	return true;
}


// =============================================================================
function jqfn_shipmodel_upstore($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['ajp'] = array();
	$param['html'] = '';
	$param['ajp']['elemtoplace'] = 'shipmodel_upstore_div';
	$param['ajp']['callback'] = 'js_shipmodel_upstore_callback';
	$param['ajp']['color'] = 'fff4ae';
	$param['ajp']['display'] = 'yes';
	// $param['ajp']['enable'] = 'enabled';

	// try_update_shipmodel_upstore(&$param);

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	//
	
	if (!isset($param['state'])) $param['state'] = 'close';
	if ($param['state'] != 'open') $param['state'] = 'close';
	
	//
	
	if (!isset($param['c'])) $param['c'] = '';
	
	if ($param['c'] == 'saveandclose') {
		jqfn_shipmodel_text_sep_saveandclose(&$param);
		try_shipmodel_upstore(&$param);
	}
	
	/*
	if ($param['c'] == 'upstore') {
		try_shipmodel_upstore(&$param);
	}
	*/
	
	//

	header('Content-Type: text/html; charset=utf-8');
	
	if ($param['html'] == '') {
		$param['html'] = outhtml_shipmodel_upstore_result(&$param);
	}
	
	$prefixarr = array();

	$out .= ajax_encode_prefix($param['ajp']);
	
	$out .= $param['html'];

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/shipmodel_upstore.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_shipmodel_upstore($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>