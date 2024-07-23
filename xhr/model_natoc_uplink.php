<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_model_natoc_uplink() {

$str = <<<SCRIPTSTRING

function js_model_natoc_uplink_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('model_natoc_uplink_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	if (typeof aresp['input_color'] == 'string') {
		var elem = document.getElementById('model_natoc_input');
		if (elem) {
			if (aresp['input_color'] == 'red') aresp['input_color'] = 'ffd7d7';
			if (aresp['input_color'] == 'yellow') aresp['input_color'] = 'fff4ae';
			if (aresp['input_color'] == 'green') aresp['input_color'] = 'd6ffd5';
			elem.style.backgroundColor = '#' + aresp['input_color'];
		}
	}
	
	return true;
}


function js_model_natoc_uplink_query(item_id, c) {
	
	var url = '/xhr/model_natoc_uplink.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function model_natoc_uplink_click() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_model_natoc_uplink_query(item_id, 'uplink');
	}
}


function model_natoc_uplink_refresh() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_model_natoc_uplink_query(item_id, '');
	}
}

function model_natoc_uplink_hide() {
	var elem = document.getElementById('model_natoc_uplink_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function model_natoc_uplink_show() {
	var elem = document.getElementById('model_natoc_uplink_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_model_natoc_uplink_result($param) {

	$out = '';
	
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_link.png\'); " onclick=" model_natoc_uplink_click(); return false; " title=" глобально изменить данные о проекте ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_model_natoc_uplink_process(&$param) {

	if (!am_i_admin_or_moderator()) return $out;
	
	//$out .= 'z1';

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'model_natoc_uplink_div';
		$param['html'] = outhtml_model_natoc_uplink_result($param);
	}
	
	//
	
	if (can_i_model_natoc_uplink($param)) {
		$param['ajp']['show'] = 'show';
	} else {
		$param['ajp']['show'] = 'hide';
	}
	
	return true;
}


// =============================================================================
function outhtml_model_natoc_uplink_div($param) {

	$out = '';
	
	// $out .= '<input type="hidden" id="model_natoc_uplink_item_id" value="'.$param['i'].'" />';
	
	if (can_i_model_natoc_uplink($param)) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="model_natoc_uplink_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_model_natoc_uplink_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_beautify_model_natoc_uplink_str($str) {

	$str = mb_ereg_replace('[^а-яa-z1-90]', ' ', $str);

	$str = mb_str_replace($str, '    ', ' ');
	$str = mb_str_replace($str, '  ', ' ');
	$str = mb_str_replace($str, '"', '«');
	$str = mb_str_replace($str, "'", '«');
	$str = mb_str_replace($str, '\'', '«');
	
	return $str;
}


// =============================================================================
function can_i_model_natoc_uplink($param) {

	$param['i'] = ''.intval($param['i']);
	
	//print 'g1';

	if (!can_i_uplink_modelnatoc($param['i'])) return false;
	
	//print 'g2';
	
	//
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodel_id, item.natoc_id, item.natoc_str ".
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
	
	//
	
	if ($qr[0]['shipmodel_id'] < 1) return false;
	
	//print 'g7';
	
	$stored_id = my_get_shipmodel_natoc_id($qr[0]['shipmodel_id']);
	
	if ($stored_id == $qr[0]['natoc_id']) return false;
	
	//
	
	//print 'g3';
	
	return true;
}


// =============================================================================
function try_model_natoc_uplink(&$param) {

	//print 'a';

	// if (!am_i_admin_or_moderator()) return false;
	
	if (!can_i_model_natoc_uplink($param)) return false;
	
	// print 'b';
	
	$param['i'] = ''.intval($param['i']);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.natoc_id, item.shipmodel_id ".
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
	
	//
	
	if ($qr[0]['shipmodel_id'] < 1) return false;
	
	// print 'k';
	
	$q = "".
		" UPDATE shipmodel ".
		" SET shipmodel.natoc_id = '".$qr[0]['natoc_id']."' ".
		" WHERE shipmodel.shipmodel_id = '".$qr[0]['shipmodel_id']."' ". 
		";";
	$qru = mydb_query($q);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$param['ajp']['input_color'] = 'green';
	
	//
	
	update_item_searchstring($param['i']);
	
	//
	
	return true;
}


// =============================================================================
function jqfn_model_natoc_uplink($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	// $param['ajp']['elemtoplace'] = 'model_natoc_uplink_div';
	$param['ajp']['callback'] = 'js_model_natoc_uplink_callback';
	$param['ajp']['show'] = 'show';

	// try_update_model_natoc_uplink(&$param);

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'uplink') {
		try_model_natoc_uplink(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_model_natoc_uplink_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/model_natoc_uplink.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_fsnu');
			jqfn_model_natoc_uplink($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>