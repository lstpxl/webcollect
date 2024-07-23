<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/class_autocomplete.php');

// =============================================================================
function outhtml_script_class_enlist() {

$str = <<<SCRIPTSTRING

function js_class_enlist_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	if (typeof aresp['show'] == 'string') {
		var elem = document.getElementById('class_enlist_div');
		if (elem) {
			if (aresp['show'] == 'show') {
				elem.style.visibility = 'visible';
			}
			if (aresp['show'] == 'hide') {
				elem.style.visibility = 'hidden';
			}
		}
	}

	return true;
}


function js_class_enlist_query(item_id, c) {
	
	var url = '/xhr/class_enlist.php?i=' + item_id + '&c=' + c + '';
	return ajax_my_get_query(url);
}


function class_enlist_click() {
	
	var elem = document.getElementById('class_enlist_item_id');
	if (elem) {
		var item_id = elem.value;

		//js_class_enlist_query(item_id, 'enlist');



		class_autocomplete_enlist('', 0);

		class_enlist_hide();
	}
}


function class_enlist_refresh() {
	
	var elem = document.getElementById('class_autocomplete_div');
	if (elem) {
		if (elem.innerHTML == '') {
			class_enlist_show();
		} else {
			var elem2 = document.getElementById('class_autocomplete_mode');
			if (elem2) {
				if (elem2.value != 'enlist') {
					class_enlist_show();
				}
			}
		}
	}
}

function class_enlist_hide() {
	var elem = document.getElementById('class_enlist_div');
	if (elem) {
		elem.style.visibility = 'hidden';
	}
}

function class_enlist_show() {
	var elem = document.getElementById('class_enlist_div');
	if (elem) {
		elem.style.visibility = 'visible';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_class_enlist_content($param) {

	$out = '';
	
	// 87a3b4
	$out .= '<div class="hoverwhiteborder" style=" vertical-align: top; width: 22px; height: 23px;  border-radius: 3px; -moz-border-radius: 3px; background-color: #e0e0e0;  background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/images/text_align_left.png\'); " onclick=" class_enlist_click(); return false; " title=" выбрать из списка ">';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function my_class_enlist_process(&$param) {

	if (!am_i_admin_or_moderator()) return $out;
	
	//$out .= 'z1';

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'class_enlist_div';
		$param['html'] = outhtml_class_enlist_content($param);
	}
	
	// $param['ajp']['color'] = 'yellow';
	$param['ajp']['show'] = 'hide';
	
	//
	
	//
	
	if (can_i_class_enlist($param)) {
	
		$param['ajp']['show'] = 'show';
	
	}
		
	return true;
}


// =============================================================================
function outhtml_class_enlist_div($param) {

	$out = '';
	
	$out .= '<input type="hidden" id="class_enlist_item_id" value="'.$param['i'].'" />';
	
	if (isset($param['full_enlist'])) {
		if ($param['full_enlist'] == 'yes') {
			$insertstyle = ' visibility: hidden; ';
		}
	}

	$out .= '<div id="class_enlist_div" style="'.$insertstyle.'" >';
	
		$out .= outhtml_class_enlist_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function can_i_class_enlist($param) {

	if (!am_i_admin_or_moderator()) return false;
	
	return true;
}


// =============================================================================
function try_class_enlist(&$param) {

	$param['ajp']['elemtoplace'] = 'class_autocomplete_div';
	$d = array();
	$d['i'] = $param['i'];
	$d['c'] = 'enlist';
	$param['html'] = outhtml_class_autocomplete_content($d);
	
	return true;
}


// =============================================================================
function jqfn_class_enlist($param) {

	if (!am_i_registered_user()) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_class_enlist_callback';
	$param['ajp']['show'] = 'show';

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (!isset($param['c'])) $param['c'] = '';
	if ($param['c'] == 'enlist') {
		try_class_enlist(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	my_class_enlist_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/class_enlist.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_class_enlist($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>