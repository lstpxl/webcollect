<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/model_natoc_upstore.php');


// =============================================================================
function outhtml_script_model_natoc_input() {

$str = <<<SCRIPTSTRING

var model_natoc_input_str = '';
var TimeToUpdate = 0;

function js_model_natoc_input_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById(aresp['model_natoc_upstore_div']);
		if (elem) {
			if (aresp['display'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['display'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	if (typeof aresp['color'] == 'string') {
		var elem = document.getElementById('model_natoc_input');
		if (elem) {
			if (aresp['color'] == 'red') aresp['color'] = 'ffd7d7';
			if (aresp['color'] == 'yellow') aresp['color'] = 'fff4ae';
			if (aresp['color'] == 'green') aresp['color'] = 'd6ffd5';
			elem.style.backgroundColor = '#' + aresp['color'];
		}
	}
	
	if (typeof aresp['hide_upstore'] == 'string') {
		var elem = document.getElementById('model_natoc_upstore_div');
		if (elem) {
			if (aresp['hide_upstore'] == 'yes') {
				elem.style.visibility = 'hidden';
			}
			if (aresp['hide_upstore'] == 'no') {
				elem.style.visibility = 'visible';
			}
		}
	}
	
	return true;
}



function js_model_natoc_input_query(c) {
	
	if (typeof c == 'undefined') c = '';

	var elem = document.getElementById('model_natoc_input');
	if (elem) {
		var str = elem.value;
	} else {
		var str = '';
	}
	
	var elem = document.getElementById('item_classification_item_id');
	if (!elem) {
		alert('Problem 63');
		return false;
	} else {
		item_id = elem.value;
	}

	if (item_id < 1) {
		alert('Problem 81');
		return false;
	}
	
	var url = '/xhr/model_natoc_input.php?i=' + item_id + '&c=' + c + '&str=' + str + '';
	
	return ajax_my_get_query(url);
}


function model_natoc_input_test_sub() {
	var TimeNow = new Date().getTime();
	if (TimeNow < TimeToUpdate) return true;

	var str = document.getElementById('model_natoc_input').value;
	if (model_natoc_input_str != str) {
		model_natoc_input_str = str;
		//js_model_natoc_input_query('save');
		model_natoc_autocomplete_search(model_natoc_input_str, 0);
	}
}

function model_natoc_input_test() {
	
	var elem = document.getElementById('model_natoc_input');
	if (!elem) return false;
	var str = elem.value;

	if (model_natoc_input_str != str) {
		//
		//elem.style.backgroundColor = '#fff4ae';
		model_natoc_input_setstatus('red', true);
		
		//

		//
		if (str != '') {
			htmlclean = 'запрос... ';
			//document.getElementById('model_natoc_input_suggest_div').innerHTML = htmlclean;
			//document.getElementById('model_natoc_input_suggest_div').style.visibility = 'visible';
			//alert('a');
		} else {
			// document.getElementById('model_natoc_input_suggest_div').style.visibility = 'hidden';
			// document.getElementById('model_natoc_input_suggest_div').innerHTML = '';
			
		}
		TimeToUpdate = new Date().getTime() + 650;
		setTimeout('model_natoc_input_test_sub()', 800);
	}
	
	// model_natoc_upstore_show();
}


function model_natoc_input_store() {
	js_model_natoc_input_query('save');
}


function model_natoc_input_hide() {
	var elem = document.getElementById('model_natoc_input_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function model_natoc_input_show() {
	var elem = document.getElementById('model_natoc_input_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}

function model_natoc_input_setstatus(color, enabled) {
	var elem = document.getElementById('model_natoc_input');
	if (!elem) return false;
	
	// if (!enabled) alert('zzzz!');
	
	elem.readonly = (!enabled);
	elem.disabled = (!enabled);
	
	if (color == 'red')	elem.style.backgroundColor = '#ffd7d7';
	if (color == 'yellow') elem.style.backgroundColor = '#fff4ae';
	if (color == 'green') elem.style.backgroundColor = '#d6ffd5';
	
	return true;
}

SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_model_natoc_input_content($param) {

	$param['i'] = ''.intval($param['i']);

	$out = '';
	

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodel_id, ".
		" item.natoc_id, item.natoc_str ".
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


	$str = $qr[0]['natoc_str'];

	$color = '#fff4ae'; // yellow

	if ($qr[0]['shipmodel_id'] > 0) {
	
		if ($qr[0]['natoc_id'] > 0) {
		
			$color = '#d6ffd5'; // green
			
			/*
			$stored_natoc_id = my_get_model_natoc_id($qr[0]['shipmodel_id']);
			if ($stored_natoc_id == $qr[0]['natoc_id']) {
				$color = '#d6ffd5'; // green
			}
			*/
		}
	}

	//
	
	$out .= '<div id="model_natoc_input_input_div">';
		
		// $out .= '<input type="hidden" name="model_natoc_input_item_id" id="model_natoc_input_item_id" value="'.$item_id.'" />';
		
		//$out .= '<input type="hidden" name="model_natoc_inputected_id" id="model_natoc_inputected_id" value="'.$id.'" />';
		
		$out .= '<div style=" vertical-align: top; padding-right: 5px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 110px; " size="10" name="model_natoc_input" id="model_natoc_input"  onchange="model_natoc_input_test();" onkeydown="model_natoc_input_test();" onkeyup="model_natoc_input_test();" value="'.$str.'" /></div>';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_model_natoc_input_div($param) {

	$out = '';
	
	$out .= '<div id="model_natoc_input_div">';
	
		$out .= outhtml_model_natoc_input_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_beautify_model_natoc_input_str($str) {

	$str = trim($str);
	$str = mb_str_replace($str, '    ', ' ');
	$str = mb_str_replace($str, '  ', ' ');
	$str = mb_str_replace($str, '"', '«');
	$str = mb_str_replace($str, '\'', '«');
	$str = trim($str);
	
	return $str;
}


// =============================================================================
function try_update_model_natoc_input(&$param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['str'])) return false;
	
	if (!can_i_edit_item($param['i'])) return false;

	//
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.natoc_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		$param['ajp']['color'] = 'red';
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		$param['ajp']['color'] = 'red';
		return false;
	}
	
	//

	if ($param['str'] == $qr[0]['natoc_str']) return false;
	
	// $param['ajp']['preparedq'] = $param['str'];
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.natoc_str = ? ".
		" WHERE item.item_id = '".$param['i']."' ". 
		";";
	$a[] = $param['str'];
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	
	//
	
	/*
	
	$param['str'] = my_beautify_model_natoc_input_str($param['str']);
	
	if ($param['str'] == $qr[0]['model_natoc_str']) return false;
	
	$qru = mydb_query("".
		" UPDATE item ".
		" SET item.model_natoc_str = '".$param['str']."' ".
		" WHERE item.item_id = '".$param['i']."' ". 
		"");
	if (!$qru) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		$param['ajp']['color'] = 'red';
		return false;
	}
	
	*/
	
	update_item_searchstring($param['i']);
	
	return true;
}


// =============================================================================
function my_model_natoc_input_process(&$param) {

	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'model_natoc_input_div';
		$param['html'] = outhtml_model_natoc_input_content($param);
	}
	
	$param['ajp']['color'] = 'yellow';
	
	//
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id, item.natoc_str ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		$param['ajp']['color'] = 'red';
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		$param['ajp']['color'] = 'red';
		return false;
	}
	
	//
	
	//$param['ajp']['itemz'] = $qr[0]['model_natoc_str'];
	
	$param['ajp']['hide_upstore'] = 'no';
	
	if ($qr[0]['ship_id'] > 0) {
	
		$qr2 = mydb_queryarray("".
			" SELECT ship.ship_id, ".
			" ship.factoryserialnum ".
			" FROM ship ".
			" WHERE ship.ship_id = '".$qr[0]['ship_id']."' ".
			"");
		if ($qr2 === false) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr2) != 1) {
			out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		//$param['ajp']['shipz'] = $qr2[0]['factoryserialnum'];
		
		if ($qr[0]['model_natoc_str'] == $qr2[0]['factoryserialnum']) {
			$param['ajp']['color'] = 'green';
			$param['ajp']['hide_upstore'] = 'yes';
		}
	
	}
}


// =============================================================================
function jqfn_model_natoc_input($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_model_natoc_input_callback';
	$param['ajp']['color'] = 'yellow';
	// $param['ajp']['hide_upstore'] = 'no';
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'save') {
		try_update_model_natoc_input($param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_model_natoc_input_process($param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/model_natoc_input.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_model_natoc_input($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>