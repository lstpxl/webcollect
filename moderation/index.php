<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/item/corrections.php');



// =============================================================================
function outhtml_item_serie($itemset_id) {

	$itemset_id = ''.intval($itemset_id);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id ".
		" FROM item ".
		" WHERE item.itemset_id = '".$itemset_id."' ".
		" AND ( ".
			" ( item.status = 'K' ) ".
			" OR ".
			" ( item.status = 'U' ) ".
			" OR ".
			" ( item.status = 'H' ) ".
		" ) ".
		" ORDER BY time_submit_finish, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) < 1) {
		// my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		// return false;
	}
	
	$z = sizeof($qr);

	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >знаков: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$out .= '<div>';
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		// $out .= outhtml_item_inlist_small_moderation($qr[$i]['item_id']);
		$out .= outhtml_item_inlist_smallth($qr[$i]['item_id'], 'ship model');
	}
	$out .= '<div style=" clear: both; " ></div>';
	$out .= '</div>';
	
	return $out.PHP_EOL;
}





// =============================================================================
function outhtml_item_series_list($param) {

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT itemset.itemset_id, ".
		" itemset.name ".
		" FROM itemset ".
		" ORDER BY itemset.name ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Всего серий: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';

	for ($i = 0; $i < sizeof($qr); $i++) {
	
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Серия: <span style=" color: #66737b; ">'.$qr[$i]['name'].'<span></div>';
	
		$out .= outhtml_item_serie($qr[$i]['itemset_id']);
	}
	
	return $out.PHP_EOL;
}


// =============================================================================
function process_iurel_filename_my1($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.image_filename_original ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$str = $qr[0]['image_filename_original'];
	$process_user_id = 3;
	
	if (iurel_get_value($item_id, $process_user_id, 'storageplace', $str) != '') return true;

	// пользовательская информация для user_id = 3

	$result = iurel_set_value($item_id, $process_user_id, 'storageplace', $str);
	$result = iurel_set_value($item_id, $process_user_id, 'gotit', 'Y');
	update_iurel_searchstring($item_id, $process_user_id);
	
	return true;
}



// =============================================================================
function process_iurel_filename_my2($item_id) {

	$process_user_id = 3;
	
	$v = iurel_get_value($item_id, $process_user_id, 'storageplace');
	
	if (!$v) return true;
	if (mb_strlen($v) < 5) return true;
	
	//
	
	$s = $v;
	$s = mb_str_replace($s, '.jpg', ' ');
	$s = mb_str_replace($s, '.jpeg', ' ');
	$s = mb_str_replace($s, '.JPG', ' ');
	$s = mb_str_replace($s, '.JPEG', ' ');
	$s = mb_str_replace($s, '.', ' ');
	$s = mb_str_replace($s, '-', ' ');
	
	$a = explode(' ', $s);
	$str = '';
	$previsalpha = false;
	for ($i = 0; $i < sizeof($a); $i++) {
		$a[$i] = trim($a[$i]);
		if (ctype_digit($a[$i])) {
			if ($previsalpha) $str .= $a[$i].' ';
			$previsalpha = false;
		} else {
			if ($a[$i] != 'jpg') $str .= $a[$i].' ';
			$previsalpha = true;
		}
	}
	
	//print '«'.$str.'»';
	//return true;

	//

	$result = iurel_set_value($item_id, $process_user_id, 'storageplace', $str);

	update_iurel_searchstring($item_id, $process_user_id);
	
	return true;
}


// =============================================================================
function outhtml_item_to_moderate_list($param) {

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.status = 'W' ".
		" ORDER BY time_submit_finish DESC, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) < 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$z = sizeof($qr);
	$max = (20 * 100);
	
	
	// $out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Элементов для модерации: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	if ($z > $max) {
		$z = $max;
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Показаны: <span style=" color: #66737b; ">'.$z.'<span></div>';
	}
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$day = '';
	
	$out .= '<div>';
	
	$prev_item_upload_time = time();
	
	for ($i = 0; $i < $z; $i++) {
		$cur_item_time = strtotime($qr[$i]['time_submit_finish']);
		$delta = ($prev_item_upload_time - $cur_item_time);
		if ($delta > (60*2)) {
			$prev_item_upload_time = $cur_item_time;
			$out .= '<div style=" clear: both; padding-top: 30px; margin-left: 18px; margin-bottom: 20px; " >';
				$out .= 'Загружено '.date('Y-m-d', $cur_item_time);
			$out .= '</div>';
		}
		
		/*
		$itemday = date('Y-m-d', strtotime($qr[$i]['time_submit_finish']));
		if ($day != $itemday) {
			$day = $itemday;
			$out .= '<div style=" clear: both; padding-top: 30px; margin-left: 18px; margin-bottom: 20px; " >';
				$out .= 'Загружено '.date('Y-m-d', strtotime($day));
			$out .= '</div>';
		}
		*/
		
		/*
		if ($itemday == '2013-11-19') {
			$process_user_id = 3;
			$result = iurel_set_value($qr[$i]['item_id'], $process_user_id, 'gotit', 'Y');
			process_iurel_filename_my1($qr[$i]['item_id']);
		}
		*/
		
		// process_iurel_filename_my2($qr[$i]['item_id']);
		
		
		$out .= outhtml_item_inlist_small_moderation($qr[$i]['item_id']);
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_item_uploading_list($param) {
	
	$out = '';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_start ".
		" FROM item ".
		" WHERE item.status = 'I' ".
		
		" ORDER BY time_submit_finish ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Элементов с незавершенной загрузкой: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$out .= '<table style=" margin-left: 18px; " class="userlist">';
	
	$out .= '<tr class="header">';
	
	$out .= '<td>';
	$out .= '#';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'загрузил';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'время';
	$out .= '</td>';
	$out .= '<td>';
	$out .= '';
	$out .= '</td>';
	
	$out .= '</tr>';
	

	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= '<tr>';
		$out .= '<td>';
		$out .= ''.$qr[$i]['item_id'];
		$out .= '</td>';
		$out .= '<td>';
		$out .= ''.my_get_user_name($qr[$i]['submitter_id']);
		$out .= '</td>';
		$out .= '<td>';
		$t = strtotime($qr[$i]['time_submit_start']);
		if (date('Y', $t) < 2013) {
			$str = '&mdash;';
		} else {
			$str = date('d/m/Y H:i', $t);
		}
		$out .= ''.$str;
		$out .= '</td>';
		$out .= '<td>';
		$out .= '<a href="/item/edit.php?i='.$qr[$i]['item_id'].'">Смотреть</a>';
		$out .= '</td>';
		
		$out .= '</tr>';
		
	}
	
	$out .= '</table>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_item_rejected_list($param) {
	
	$out = '';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_start ".
		" FROM item ".
		" WHERE item.status = 'R' ".
		
		" ORDER BY time_submit_finish ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Элементов: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$z = sizeof($qr);
	$max = (9 * 30);
	
	
	if ($z > $max) {
		$z = $max;
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Показаны: <span style=" color: #66737b; ">'.$z.'<span></div>';
	}
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$day = '';
	
	$out .= '<div>';
	
	for ($i = 0; $i < $z; $i++) {
		$itemday = date('Y-m-d', strtotime($qr[$i]['time_submit_finish']));
		/*
		if ($day != $itemday) {
			$day = $itemday;
			$out .= '<div style=" clear: both; padding-top: 30px; margin-left: 18px; margin-bottom: 20px; " >';
				$out .= 'Загружено '.date('Y-m-d', strtotime($day));
			$out .= '</div>';
		}
		*/
		
		/*
		if ($itemday == '2013-11-19') {
			$process_user_id = 3;
			$result = iurel_set_value($qr[$i]['item_id'], $process_user_id, 'gotit', 'Y');
			process_iurel_filename_my1($qr[$i]['item_id']);
		}
		*/
		
		// process_iurel_filename_my2($qr[$i]['item_id']);
		
		
		$out .= outhtml_item_inlist_small_moderation($qr[$i]['item_id']);
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_unclassified_list($param) {
	
	$out = '';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_start ".
		" FROM item ".
		" WHERE item.status = 'K' ".
		" AND ( ".
		" item.ship_id = '0' ".
		" OR ".
		" item.shipmodel_id = '0' ".
		" OR ".
		" item.shipmodelclass_id = '0' ".
		" ) ".
		" ORDER BY time_submit_finish ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Элементов: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$z = sizeof($qr);
	$max = (9 * 30);
	
	
	if ($z > $max) {
		$z = $max;
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Показаны: <span style=" color: #66737b; ">'.$z.'<span></div>';
	}
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$day = '';
	
	$out .= '<div>';
	
	for ($i = 0; $i < $z; $i++) {
		$itemday = date('Y-m-d', strtotime($qr[$i]['time_submit_finish']));
		if ($day != $itemday) {
			$day = $itemday;
			$out .= '<div style=" clear: both; padding-top: 30px; margin-left: 18px; margin-bottom: 20px; " >';
				$out .= 'Загружено '.date('Y-m-d', strtotime($day));
			$out .= '</div>';
		}
		
		/*
		if ($itemday == '2013-11-19') {
			$process_user_id = 3;
			$result = iurel_set_value($qr[$i]['item_id'], $process_user_id, 'gotit', 'Y');
			process_iurel_filename_my1($qr[$i]['item_id']);
		}
		*/
		
		// process_iurel_filename_my2($qr[$i]['item_id']);
		
		
		$out .= outhtml_item_inlist_small_moderation($qr[$i]['item_id']);
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_moderation_waiting($param) {

	$qr = mydb_queryarray("".
		" SELECT COUNT(item.item_id) AS n ".
		" FROM item ".
		" WHERE item.status = 'W' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$total = $qr[0]['n'];
	
	$GLOBALS['pagetitle'] = 'Модерация знаков / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Модерация знаков <span style=" color: #b0b0b0; ">('.$total.' шт)</span></h1>';
			
			$out .= outhtml_item_to_moderate_list($param);

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_moderation_atinput($param) {
	
	$GLOBALS['pagetitle'] = 'Знаки в процессе загрузки / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Знаки в процессе загрузки</h1>';
			
			$out .= outhtml_item_uploading_list($param);

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_moderation_rejected($param) {
	
	$GLOBALS['pagetitle'] = 'Отклоненные знаки / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Отклоненные знаки</h1>';
			
			$out .= outhtml_item_rejected_list($param);

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_moderation_unclassified($param) {
	
	$GLOBALS['pagetitle'] = 'Неклассифицированные знаки / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Неклассифицированные знаки</h1>';
			
			$out .= outhtml_item_unclassified_list($param);

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_moderation_corrections($param) {
	
	return item_corrections($param).PHP_EOL;
}


// =============================================================================
function outhtml_moderation_series($param) {

	$GLOBALS['pagetitle'] = 'Серии знаков / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Серии знаков</h1>';
			
			$out .= outhtml_item_series_list($param);

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_moderation_submenu(&$param) {

	if (!(am_i_moderator() || am_i_lim_moderator())) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}

	$list = array();
	$list[] = array('code' => 'w', 'text' => 'Знаки на модерации');
	$list[] = array('code' => 'i', 'text' => 'Загружаемые знаки');
	$list[] = array('code' => 'r', 'text' => 'Отклоненные');
	$list[] = array('code' => 'u', 'text' => 'Неклассифицированные');
	$list[] = array('code' => 'c', 'text' => 'Исправления');
	$list[] = array('code' => 's', 'text' => 'Серии');
	$defaultcode = 'w';
	
	foreach ($list as &$e) {
		$e['current'] = false;
		$e['href'] = '/index.php?m=m&sm='.$e['code'];
	}
	
	//
	
	if (!isset($param['sm'])) $param['sm'] = $defaultcode;
	$sm_found = false;
	foreach ($list as &$e) {
		if ($param['sm'] == $e['code']) {
			$sm_found = true;
		}
	}
	if (!$sm_found) $param['sm'] = $defaultcode;
	foreach ($list as &$e) {
		if ($param['sm'] == $e['code']) $e['current'] = true;
	}
	
	//
	
	$GLOBALS['submenu_html'] = outhtml_submenu($list);
	
	return '';
}


// =============================================================================
function outhtml_moderation_index($param) {

	if (!(am_i_moderator() || am_i_lim_moderator())) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}
	
	$GLOBALS['pagetitle'] = 'Модерация / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= outhtml_moderation_submenu(&$param);
	
	if ($param['sm'] == 'w') $out .= outhtml_moderation_waiting($param);
	if ($param['sm'] == 'i') $out .= outhtml_moderation_atinput($param);
	if ($param['sm'] == 'r') $out .= outhtml_moderation_rejected($param);
	if ($param['sm'] == 'u') $out .= outhtml_moderation_unclassified($param);
	if ($param['sm'] == 'c') $out .= outhtml_moderation_corrections($param);
	if ($param['sm'] == 's') $out .= outhtml_moderation_series($param);

	
	return $out.PHP_EOL;
}

?>