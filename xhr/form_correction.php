<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/correction_add.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/correction_remove.php');



// =============================================================================
function outhtml_script_form_correction() {

$str = <<<SCRIPTSTRING

function js_form_correction_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}
	
	return true;
}


function form_correction_reload() {
	
	var elem = document.getElementById('form_correction_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/form_correction.php?i=' + item_id + '&h=full' + '';
			return ajax_my_get_query(url);
		}
	}
}


function form_correction_add_display() {
	
	var elem1 = document.getElementById('correctionpreadd');
	var elem2 = document.getElementById('correctionadddiv');
	var elem3 = document.getElementById('correctionaddtext');
	if (elem1 && elem2 && elem3) {
		elem1.style.display = 'none';
		elem2.style.display = 'block';
		elem3.focus();
	}

	return true;
}


function form_correction_add_record(item_id) {

	var str = '';
	var elem = document.getElementById('correctionaddtext');
	if (elem) {
		str = elem.value;
	}

	if ((item_id > 0) && (str.length > 0)) {
		var url = '/xhr/form_correction.php?i=' + item_id + '&c=' + 'add' + '&h=full' + '&str=' + str;
		return ajax_my_get_query(url);
		// alert(url);
	}
	
	var elem1 = document.getElementById('correctionpreadd');
	var elem2 = document.getElementById('correctionadddiv');
	if (elem1 && elem2) {
		elem1.style.display = 'block';
		elem2.style.display = 'none';
	}
	
	return true;
}


function form_correction_remove(item_id, correction_id) {

	if ((item_id > 0) && (correction_id > 0)) {
		var url = '/xhr/form_correction.php?i=' + item_id + '&c=' + 'remove' + '&correction_id=' + correction_id + '&h=full';
		return ajax_my_get_query(url);
	}

	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_correction_list($item_id) {

	$out = '';

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT correction.correction_id, ".
		" correction.user_id, correction.added, ".
		" correction.text ".
		" FROM correction ".
		" WHERE correction.item_id = '".$item_id."' ".
		" ORDER BY correction.added ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<div id="correctionlistrect" style=" ">';
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= '<div id="correction'.$qr[$i]['correction_id'].'" style=" margin-top: 4px; ">';
			
			$out .= '<div style=" ">';
				$out .= htmlspecialchars($qr[$i]['text'], ENT_QUOTES);
			$out .= '</div>';
			
			$out .= '<div style=" text-align: right; ">';
				$out .= '<span style=" color: #303030; ">'.htmlspecialchars(my_get_user_name($qr[$i]['user_id']), ENT_QUOTES).'</span>';
				$out .= ', ';
				$out .= '<span style=" color: #808080; ">'.date('d/m/Y', strtotime($qr[$i]['added'])).'</span>';
				
				if (can_i_remove_correction($qr[$i]['correction_id'])) {
					$out .= ', ';
					$out .= '<span class="jsspan" style=" color: #a00000; " onclick="form_correction_remove('.$item_id.', '.$qr[$i]['correction_id'].');" >'.'Убрать'.'</span>';
				}	
				
			$out .= '</div>';
			
		$out .= '</div>';
		
		if ($i < (sizeof($qr) - 1)) {
			$out .= '<div style=" margin-top: 4px; margin-bottom: 2px; height: 0px; border-top: 1px solid #828b94;  "></div>';
			// border-bottom: 1px solid #d3d6d7;
		}
	}
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_form_correction_content($param) {

	$out = '';
	
	if (!can_i_report_correction($param['i'])) return '';
	
	//
	
	$out .= '<input type="hidden" id="form_correction_item_id" value="'.$param['i'].'" />';

	// raised rect

	$out .= '<div id="correctionrect" style=" width: 340px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px;  margin-top: 5px; padding: 15px 15px 15px 15px; color: #606060; line-height: 125%; ">';
	
		// top row
		$out .= '<div style="  ">';
		
			
				$out .= '<div style=" float: left; margin-left: 0px; font-size: 12px; padding: 0px 0px 0px 0px; ">';
				
					$out .= '<div style=" margin-top: 0px; vertical-align: top; "><button id="correctionpreadd" class="lightbluegradient hoverlightblueborder"  onclick="form_correction_add_display();" value="'.$param['i'].'" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; ">Сообщить об ошибке в описании</button></div>';
					
					$out .= '<div id="correctionadddiv" style=" display: none; margin-top: 0px; vertical-align: top; ">';
					
						$out .= '<div style=" margin-top: 0px; vertical-align: top; ">';
						$out .= 'Сообщить об ошибке в описании';
						$out .= '</div>';
					
						$color = '#d9e1e7'; // light blue
						$out .= '<textarea cols="30" rows="2" class="hoverlightblueborder" style=" display: block; text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 335px; " id="correctionaddtext" onkeypress=" if (event.keyCode == 13) { form_correction_add_record(\''.$param['i'].'\'); return false; } " ></textarea>';
						
						$out .= '<button class="lightbluegradient hoverlightblueborder"  onclick="form_correction_add_record('.$param['i'].');" value="'.$param['i'].'" style=" margin-top: 10px; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; ">Добавить</button>';
					
					$out .= '</div>';
					
				$out .= '</div>';
				
				$out .= '<div style=" clear: both; "></div>';
				
				$out .= outhtml_correction_list($param['i']);
				
			
			
			// sellit
			
			
			
			$out .= '<div style=" clear: both; "></div>';
			
		$out .= '</div>';
		
		// $out .= outhtml_correction_list($param['i']);
		
		$out .= '<div style=" clear: both; "></div>';
		
	$out .= '</div>';
		
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_form_correction_div($param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	//

	$out = '';
	
	$out .= outhtml_script_form_correction();
	//$out .= outhtml_script_correction_add();
	//$out .= outhtml_script_correction_remove();

	
	$out .= '<div id="form_correction_div" >';
		$out .= outhtml_form_correction_content($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_form_correction_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'form_correction_div';
		$param['html'] = outhtml_form_correction_content($param);
	}
		
	return true;
}


// =============================================================================
function jqfn_form_correction_try_add(&$param) {

	if (!isset($param['str'])) return false;
	
	if (!can_i_report_correction($param['i'])) return false;
	
	$param['str'] = trim($param['str']);
	$param['str'] = strip_tags($param['str']);
	
	 // ----- remove control characters -----
	$param['str'] = str_replace("\r", '', $param['str']);    // --- replace with empty space
    $param['str'] = str_replace("\n", ' ', $param['str']);   // --- replace with space
    $param['str'] = str_replace("\t", ' ', $param['str']);   // --- replace with space
   
    // ----- remove multiple spaces -----
    $param['str'] = trim(preg_replace('/ {2,}/', ' ', $param['str']));
	//
	
	// prepared query
	$a = array();
	
	$a[] = $GLOBALS['user_id'];
	$a[] = $param['i'];
	$a[] = date('Y-m-d H:i:s');
	$a[] = $param['str'];
	$q = "".
		" INSERT INTO correction ".
		" SET correction.user_id = ? ".
		" , correction.item_id = ? ".
		" , correction.added = ? ".
		" , correction.text = ? ".
		";";
	$t = 'iiss';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	return true;
}



// =============================================================================
function jqfn_form_correction_try_remove(&$param) {

	if (!isset($param['correction_id'])) return false;
	
	$param['correction_id'] = ''.intval($param['correction_id']);
	
	if (!can_i_remove_correction($param['correction_id'])) return false;
		
	// prepared query
	$a = array();
	$a[] = $param['correction_id'];
	$q = "".
		" DELETE FROM correction ".
		" WHERE correction.correction_id = ? ".
		";";
	$t = 'i';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	return true;
}


// =============================================================================
function jqfn_form_correction($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_form_correction_callback';

	if (!isset($param['c'])) $param['c'] = '';
	
	if ($param['c'] == 'add') {
		jqfn_form_correction_try_add(&$param);
	}
	if ($param['c'] == 'remove') {
		jqfn_form_correction_try_remove(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_form_correction_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/form_correction.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			// out_silent_error('here_ic');
			jqfn_form_correction($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>