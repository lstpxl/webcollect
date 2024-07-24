<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_model_natoc_upstore() {

$str = <<<SCRIPTSTRING

function js_model_natoc_upstore_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('model_natoc_upstore_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}
	
	if (typeof aresp['hide_uplink'] == 'string') {
		var elem = document.getElementById('model_natoc_uplink_div');
		if (elem) {
			if (aresp['hide_uplink'] == 'no') {
				elem.style.visibility = 'visible';
			}
			if (aresp['hide_uplink'] == 'yes') {
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


function js_model_natoc_upstore_query(item_id, c) {
	
	var url = '/xhr/model_natoc_upstore.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function model_natoc_upstore_click() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_model_natoc_upstore_query(item_id, 'upstore');
	}
}


function model_natoc_upstore_refresh() {
	
	var elem = document.getElementById('item_classification_item_id');
	if (elem) {
		var item_id = elem.value;
		js_model_natoc_upstore_query(item_id, '');
	}
}

function model_natoc_upstore_hide() {
	var elem = document.getElementById('model_natoc_upstore_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function model_natoc_upstore_show() {
	var elem = document.getElementById('model_natoc_upstore_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_model_natoc_upstore_result($param) {

	$out = '';
	
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #87a3b4;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/database_add.png\'); " onclick=" model_natoc_upstore_click(); return false; " title=" сохранить такую классификацию в базу ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_model_natoc_upstore_process(&$param) {

	if (!can_i_model_natoc_upstore($param)) return '';
	
	//$out .= 'z1';

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'model_natoc_upstore_div';
		$param['html'] = outhtml_model_natoc_upstore_result($param);
	}
	
	//
	
	if (can_i_model_natoc_upstore($param)) {
		$param['ajp']['show'] = 'show';
	} else {
		$param['ajp']['show'] = 'hide';
	}
	
	//
	
	$param['i'] = ''.intval($param['i']);
	
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
	
	if ($qr[0]['shipmodel_id'] > 0) {
		
		$stored_id = my_get_shipmodel_natoc_id($qr[0]['shipmodel_id']);
		
		if ($stored_id != $qr[0]['natoc_id']) {
			$param['ajp']['hide_uplink'] = 'no';
		}
	}
	
	//
	
	return true;
}


// =============================================================================
function outhtml_model_natoc_upstore_div($param) {

	$out = '';
	
	// $out .= '<input type="hidden" id="model_natoc_upstore_item_id" value="'.$param['i'].'" />';
	
	if (can_i_model_natoc_upstore($param)) {
		$insertstyle = ' visibility: visible; ';
	} else {
		$insertstyle = ' visibility: hidden; ';
	}
	
	$out .= '<div id="model_natoc_upstore_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_model_natoc_upstore_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_beautify_model_natoc_upstore_str($str) {

	$str = mb_ereg_replace('[^а-яa-z1-90]', ' ', $str);

	$str = mb_str_replace($str, '    ', ' ');
	$str = mb_str_replace($str, '  ', ' ');
	$str = mb_str_replace($str, '"', '«');
	$str = mb_str_replace($str, "'", '«');
	$str = mb_str_replace($str, '\'', '«');
	
	return $str;
}


// =============================================================================
function can_i_model_natoc_upstore($param) {

	$param['i'] = ''.intval($param['i']);
	
	// print 'a';

	if (!can_i_upstore_modelnatoc($param['i'])) return false;
	
	//
	
	
	// print 't';
	
	
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
	
	
	if ($qr[0]['natoc_id'] > 0) return false;
	
	if ($qr[0]['natoc_str'] == '') return false;
	
	//
	
	return true;
}


// =============================================================================
function try_model_natoc_upstore(&$param) {

	// if (!am_i_admin_or_moderator()) return false;
	
	
	
	if (!can_i_model_natoc_upstore($param)) return false;
	
	$param['i'] = ''.intval($param['i']);
	
	// print 'k';

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.natoc_id, item.natoc_str ".
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
	
	// prepared query
	$a = array();
	$q = "".
		" INSERT INTO natoc SET ".
		" natoc.text = ? ".
		";";
	$a[] = $qr[0]['natoc_str'];
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$new_id = mydb_insert_id();
	if (!($new_id > 0)) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$q = "".
		" UPDATE item ".
		" SET item.natoc_id = '".$new_id."' ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$qru = mydb_query($q);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$param['ajp']['input_color'] = 'ffffff';
	
	if (can_i_uplink_modelnatoc($param['i'])) $param['ajp']['hide_uplink'] = 'no';
	
	//
	
	update_item_searchstring($param['i']);
	
	//
	
	return true;
}


// =============================================================================
function jqfn_model_natoc_upstore($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	// $param['ajp']['elemtoplace'] = 'model_natoc_upstore_div';
	$param['ajp']['callback'] = 'js_model_natoc_upstore_callback';
	$param['ajp']['show'] = 'show';

	// try_update_model_natoc_upstore($param);

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'upstore') {
		try_model_natoc_upstore($param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_model_natoc_upstore_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/model_natoc_upstore.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_fsnu');
			jqfn_model_natoc_upstore($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>