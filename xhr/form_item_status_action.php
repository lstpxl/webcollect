<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_status_action_add.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_status_action_remove.php');



// =============================================================================
function outhtml_script_form_item_status_action() {

$str = <<<SCRIPTSTRING

function js_form_item_status_action_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['openeditform_i'] == 'string') {
		if (is_numeric(aresp['openeditform_i'])) {
			var url = '/item/edit.php';
			var params = {i:aresp['openeditform_i']};
			my_get_url(url, params);
		}
	}
	
	return true;
}


function form_item_status_action_reload() {
	
	var elem = document.getElementById('form_item_status_action_item_id');
	if (elem) {
		var item_id = elem.value;
		if (is_numeric(item_id)) {
			var url = '/xhr/form_item_status_action.php?i=' + item_id + '&h=full' + '';
			return ajax_my_get_query(url);
		}
	}
}

function form_item_status_action_click(item_id, c) {

	// alert(c);

	// &h=full

	if ((item_id > 0) && (c != '')) {
	
		if (c == 'wipe') {
			if (!confirm('Уничтожить безвозвратно?')) {
				return true;
			}
		}

		var url = '/xhr/form_item_status_action.php?i=' + item_id + '&c=' + c + '';
		return ajax_my_get_query(url);
	}

	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_item_status_action_button($item_id, $c, $text, $cssclass, $addstyle) {

	$item_id = ''.intval($item_id);

	$out = '';

	$out .= '<div style=" margin-top: 10px; vertical-align: top; ">';
		$out .= '<button class="'.$cssclass.' hoverlightblueborder"  onclick="form_item_status_action_click('.$item_id.', \''.$c.'\');"  style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 100px; '.$addstyle.' ">';
			$out .= $text;
		$out .= '</button>';
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_form_item_status_action_content($param) {

	$out = '';
	
	// if (!can_i_report_item_status_action($param['i'])) return '';

	$status = my_get_item_status($param['i']);
	
	//
	
	$out .= '<input type="hidden" id="form_item_status_action_item_id" value="'.$param['i'].'" />';

	// raised rect

	$out .= '<div id="item_status_actionrect" style=" width: 370px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px;  margin-top: 5px; color: #606060; line-height: 125%; ">';
	
		$out .= '<div style=" padding: 15px 15px 15px 15px; ">';
		
			$out .= '<div style=" margin-top: 0px; font-size: 11pt; color: #909090; ">';
				$out .= 'Действия над знаком';
			$out .= '</div>';
	
		//
			$out .= '<div style=" clear: both; "></div>';
		
			if ($status == 'W') {
				$out .= '<div style=" float: left; width: 130px; ">';
					$can = true;
					if ($can) $out .= outhtml_item_status_action_button($param['i'], 'K', 'Утвердить', 'saladgreengradient', ' width: 110px; ');
				$out .= '</div>';
			
				$out .= '<div style=" float: left; width: 130px; ">';
					$can = true;
					if ($can) $out .= outhtml_item_status_action_button($param['i'], 'R', 'Отклонить', 'darkredgradient', ' width: 110px; ');
				$out .= '</div>';

				$out .= '<div style=" clear: both; "></div>';

				$can = true;
				if ($can) $out .= outhtml_item_status_action_button($param['i'], 'skip', 'Пропустить', 'lightbluegradient', ' width: 240px; ');

			}
			
			if ($status != 'U') {
				$can = true;
				if ($can) $out .= outhtml_item_status_action_button($param['i'], 'U', 'В хранилище', 'blackgradient', ' width: 240px; color: #c0c0c0; ');
			}
			
			if ($status != 'H') {
				$can = true;
				if ($can) $out .= outhtml_item_status_action_button($param['i'], 'H', 'Нужна помощь', 'blackgradient', ' width: 240px; color: #c0c0c0; ');
			}

			if ($status != 'D') {
				$can = true;
				if ($can) $out .= outhtml_item_status_action_button($param['i'], 'D', 'Удалить', 'blackgradient', ' width: 240px; color: #c0c0c0; ');
			}


			if (mb_strpos('_KRUHD', $status) > 0) {
				$can = true;
				if ($can) $out .= outhtml_item_status_action_button($param['i'], 'W', 'Вернуть на модерацию', 'blackgradient', ' width: 240px; color: #c0c0c0; ');
			}
			
			if ($status == 'D') {
				$can = true;
				if (!am_i_superadmin()) $can = false;
				if ($can) $out .= outhtml_item_status_action_button($param['i'], 'wipe', 'Уничтожить', 'blackgradient', ' width: 240px; color: #f03030; ');
			}
			
			
			$out .= '<div style=" clear: both; "></div>';
		
		//
		
		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '</div>';
		
	$out .= '</div>';
		
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_form_item_status_action_div($param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	//

	$out = '';
	
	$out .= outhtml_script_form_item_status_action();
	
	$out .= '<div id="form_item_status_action_div" >';
		$out .= outhtml_form_item_status_action_content($param);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_form_item_status_action_process(&$param) {

	if (!isset($param['h'])) $param['h'] = '';
	if ($param['h'] == 'full') {
		$param['ajp']['elemtoplace'] = 'form_item_status_action_div';
		$param['html'] = outhtml_form_item_status_action_content($param);
	}
		
	return true;
}


// =============================================================================
function jqfn_form_item_status_action_try_modify(&$param) {

	if (!am_i_registered_user()) return false;

	$param['i'] = ''.intval($param['i']);
	if (!isset($param['c'])) return false;
	if ($param['c'] == '') return false;
	
	//  $param['ajp']['openeditform_i'] = '1';

	$status = my_get_item_status($param['i']);

	$newstatus = false;
	
	if ($param['c'] == 'wipe') {
		if ($status == 'D') {
			if (am_i_superadmin()) {
				$result = try_wipe_item($param['i']);
				$param['ajp']['openeditform_i'] = $param['i'];
				return $result;
			}
		}
	}

	if ($status == 'W') {

		
		if ($param['c'] == 'skip') {
			$next_id = get_next_item_to_moderate($param['i']);
			if ($next_id > 0) {
				$param['ajp']['openeditform_i'] = $next_id;
				return true;
			}
		}

		if ($param['c'] == 'K') {
			if (can_i_moderate_item($param['i'])) {
				$next_id = get_next_item_to_moderate($param['i']);
				if ($next_id > 0) {
					$param['ajp']['openeditform_i'] = $next_id;
				}
				$newstatus = 'K';
			}
		}

		if ($param['c'] == 'R') {
			if (can_i_moderate_item($param['i'])) {
				$next_id = get_next_item_to_moderate($param['i']);
				if ($next_id > 0) {
					$param['ajp']['openeditform_i'] = $next_id;
				}
				$newstatus = 'R';
			}
		}

	}

	if ($param['c'] == 'D') {
		if (can_i_delete_item($param['i'])) {
			$newstatus = 'D';
			$param['ajp']['openeditform_i'] = $param['i'];
		}
	}

	if ($param['c'] == 'U') {
		if (am_i_admin()) {
			$newstatus = 'U';
			$param['ajp']['openeditform_i'] = $param['i'];
		}
	}

	if ($param['c'] == 'H') {
		if (am_i_admin() || can_i_moderate_item($param['i'])) {
			$newstatus = 'H';
			$param['ajp']['openeditform_i'] = $param['i'];
		}
	}

	if ($param['c'] == 'W') {
		if (am_i_admin() || can_i_moderate_item($param['i'])) {
			$newstatus = 'W';
			$param['ajp']['openeditform_i'] = $param['i'];
		}
	}

	if ($newstatus) {
		// prepared query
		$a = array();
		
		$a[] = $newstatus;
		$a[] = $param['i'];
		$q = "".
			" UPDATE item ".
			" SET item.status = ? ".
			" WHERE item.item_id = ? ".
			";";
		$t = 'si';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
	}
	
	
	if ($newstatus == 'K') {
		// prepared query
		$a = array();
		
		$a[] = date('Y-m-d H:i:s');
		$a[] = $GLOBALS['user_id'];
		$a[] = $param['i'];
		$q = "".
			" UPDATE item ".
			" SET item.time_approved = ? , ".
			" item.moderator_id = ? ".
			" WHERE item.item_id = ? ".
			";";
		$t = 'sii';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
	}
	
	return true;
}


// =============================================================================
function jqfn_form_item_status_action($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_form_item_status_action_callback';

	if (!isset($param['c'])) $param['c'] = '';
	
	if ($param['c'] != '') {
		jqfn_form_item_status_action_try_modify(&$param);
	}

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_form_item_status_action_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/form_item_status_action.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			// out_silent_error('here_ic');
			jqfn_form_item_status_action($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>