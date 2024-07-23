<?php

if (!mb_internal_encoding('UTF-8')) die('mb_internal_encoding failed!');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/db.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basic.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/settings.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/ajax.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/dbget.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/perm.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/snippet.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/iurel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/mail.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist_label.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/refresh_downlink.php');


// =============================================================================
function update_item_searchstring($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.notes, item.lettering, ".
		" item.ship_id, item.ship_str, ". 
		" item.shipmodel_id, item.shipmodel_str, ". 
		" item.shipmodelclass_id, item.shipmodelclass_str, ". 
		" item.ship_factoryserialnum_str, item.natoc_str, ". 
		" item.itemset_id, item.itemset_str, ". 
		" item.shipyard_id, item.shipyard_str ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$s = '';
	$s .= ' '.$qr[0]['ship_str'];
	$s .= ' '.$qr[0]['ship_factoryserialnum_str'];
	$s .= ' '.$qr[0]['shipyard_str'];
	$s .= ' '.$qr[0]['shipmodel_str'];
	$s .= ' '.$qr[0]['shipmodelclass_str'];
	$s .= ' '.$qr[0]['natoc_str'];
	$s .= ' '.$qr[0]['lettering'];
	$s .= ' '.$qr[0]['itemset_str'];
	$s .= ' '.$qr[0]['notes'];

	$s = prep_string_for_search($s);
	
	/*
	$ship = my_get_ship_name($qr[0]['ship_id']);
	$ship = my_get_ship_name($qr[0]['ship_id']);
	*/
	
	$sortfield_a = '';
	if (trim($qr[0]['ship_str']) != '') {
		$sortfield_a .= ' a';
	} else {
		$sortfield_a .= ' b';
	}
	$sortfield_a .= ' '.$qr[0]['ship_str'];
	$sortfield_a .= ' '.$qr[0]['ship_factoryserialnum_str'];
	$sortfield_a .= ' '.$qr[0]['shipmodel_str'];
	$sortfield_a .= ' '.$qr[0]['shipyard_str'];
	$sortfield_a .= ' '.$item_id;
	
	// $sortfield_c = ' '.$qr[0]['shipmodelclass_str'].' '.$qr[0]['shipmodel_str'].' '.$qr[0]['ship_str'].' '.$qr[0]['item_id'].' ';
	
	
	if ($qr[0]['shipmodelclass_id'] > 0) {
		$top_shipmodelclass_id = my_get_top_shipmodelclass_id($qr[0]['shipmodelclass_id']);
	} else {
		$top_shipmodelclass_id = 0;
	}
	
	// prepared query
	$a = array();
	$q = "".
		" UPDATE item ".
		" SET item.searchstring = ?, ".
		" item.sortfield_a = ?, ".
		" item.top_shipmodelclass_id = ? ".
		" WHERE item.item_id = ? ". 
		";";
	$a[] = $s;
	$a[] = $sortfield_a;
	$a[] = $top_shipmodelclass_id;
	$a[] = $item_id;
	$t = 'ssii';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	// my_elemtree_rebuild_treeindex_local('item', $item_id);
	// my_calc_item_treeindex_v($item_id);
	force_downlink_item($item_id);
	my_mark_element_fresh('item', $item_id, true);
		
	return true;
}



// =============================================================================
function is_valid_username($str) {
	//if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
	return (preg_match('/\A[a-zA-Zа-яА-Я0-9_]+[a-zA-Zа-яА-Я0-9_\s]+[a-zA-Zа-яА-Я0-9_]+\z/', $str) > 0);
}


// =============================================================================
function is_username_exist($str) {

	// prepared query
	$a = array();
	$a[] = $str;
	$q = "".
		" SELECT user.user_id, ".
		" user.username, user.email_address ".
		" FROM user ".
		" WHERE user.username = ? ";
		";";
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	return (sizeof($qres) >= 1);
}


// =============================================================================
function my_get_user_name($user_id, $short=false) {

	$user_id = ''.intval($user_id);
	
	$q = "SELECT user.user_id, ".
		" user.username, user.email_address ".
		"FROM user ".
		"WHERE user.user_id = '".$user_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ($short) return $qr[0]['username'];
	
	return $qr[0]['username'].' ('.$qr[0]['email_address'].')';
}


// =============================================================================
function my_get_user_email($user_id) {

	$user_id = ''.intval($user_id);
	
	$q = "SELECT user.user_id, ".
		" user.username, user.email_address ".
		"FROM user ".
		"WHERE user.user_id = '".$user_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return ''.$qr[0]['email_address'].'';
}


// =============================================================================
function my_purge_old_sessions() {

	$time_threshold = date('Y-m-d H:i:s', (time() - (60*60*24*7)));
	
	$qr = mydb_query("".
		"DELETE FROM user ".
		" WHERE user.is_registered_user = 'N' ".
		" AND user.time_last_request < '".$time_threshold."' ".
		" AND user.email_code = '' ".
		"");
		
	$time_threshold = date('Y-m-d H:i:s', (time() - (60*60*24*7*8)));
	
	$qr = mydb_query("".
		"DELETE FROM user ".
		" WHERE user.is_registered_user = 'N' ".
		" AND user.time_last_request < '".$time_threshold."' ".
		" AND user.email_code != '' ".
		"");
		
	// 
		
	

	return true;
}

// =============================================================================
function get_item_count_str_case($n) {
	
	$r = ($n % 10);
	$rh = ($n % 100);
	
	$b = 'знаков';
	if ($r == 1) $b = 'знак';
	if (($r >= 2) && ($r <= 4)) $b = 'знака';
	if (($rh >= 11) && ($rh <= 14)) $b = 'знаков';
	
	return $b;
}


// =============================================================================
function get_item_count_unid_str_case($n) {
	
	$r = ($n % 10);
	$rh = ($n % 100);
	
	$b = 'неидентифицированных';
	if ($r == 1) $b = 'неидентифицированный';
	if (($r >= 2) && ($r <= 4)) $b = 'неидентифицированных';
	if (($rh >= 11) && ($rh <= 14)) $b = 'неидентифицированных';
	
	return $b;
}


// =============================================================================
function my_beautify_item_shipmodel_str($str) {

	$str = mb_str_replace($str, '    ', ' ');
	$str = mb_str_replace($str, '  ', ' ');
	//$str = mb_str_replace($str, '"', '«');
	//$str = mb_str_replace($str, '\'', '«');
	
	return $str;
}


// =============================================================================
function eyo_str($str) {
	$str = mb_str_replace($str, 'ё', 'е');
	$str = mb_str_replace($str, 'Ё', 'Е');
	return $str;
}


// =============================================================================
function my_break_item_shipmodel_str($str) {

	$str = trim($str);

	$a = array();
	$a['nick'] = '';
	$a['numcode'] = '';
	
	$r = explode(' ', $str);
	
	$pspace = mb_strpos($str, ' ');
	$first = mb_substr($str, 0, 1);
	
	if (ctype_digit($first)) {
		
		if ($pspace > 0) {
			$a['numcode'] = trim(mb_substr($str, 0, $pspace));
			$a['nick'] = trim(mb_substr($str, ($pspace + 1)));
			$a['name'] = $a['numcode'].' «'.$a['nick'].'»';
			return $a;
		} else {
			$a['numcode'] = $str;
			$a['name'] = $a['numcode'];
			return $a;
		}
	
	} else {

		$a['nick'] = $str;
		$a['name'] .= '«'.$a['nick'].'»';
		return $a;
	}
	
	return $a;
}


// =============================================================================
// Проверяет денежную сумму на орфографию, возвращает причесанную
function my_parse_currency($s) {

	if (!isset($s)) return false;
	if (mb_strlen($s) < 1) return false;

	$delim = '.';

	$s = trim($s);
	$s = mb_str_replace($s, chr(194), ""); // неразрывный пробел ???
	$s = mb_str_replace($s, " ", "");
	$s = mb_str_replace($s, ",", ".");
	$s = mb_str_replace($s, "ю", ".");

	$test = explode($delim, $s);
	if ($test === false) return false;

	if (sizeof($test) > 2) return false;
	if (sizeof($test) < 1) return false;
	if (sizeof($test) == 1) $test[] = '00';

	$w = $test[0];
	$d = $test[1];
	if (ctype_digit($w) === false) return false;
	if (ctype_digit($d) === false) return false;

	// убираем нули в начале целой части
	do {
		$ask = (mb_strlen($w) > 1);
		if ($ask) if (mb_substr($w, 0, 1) != '0') $ask = false;
		if ($ask) $w = mb_substr($w, 1, 100);
	} while ($ask);

	// убираем знаки в конце дробной части
	if (mb_strlen($d) > 2) $d = mb_substr($d, 0, 2);
	if (mb_strlen($d) == 1) $d .= '0';

	$s = $w.'.'.$d;
	if (!is_numeric($s)) return false;
	return $s;
}


// =============================================================================
function try_wipe_item($item_id) {

	if (!am_i_superadmin()) return false;

	if (!isset($item_id)) return false;
	$item_id = ''.intval($item_id);
	if (my_get_item_status($item_id) === false) return false;
	
	$result = try_remove_all_item_images($item_id);
	if ($result === false) {
		out_silent_error("Отсутствует папка с изображениями item #".$item_id." ! (".__FILE__." Line ".__LINE__.")");
	}
	
	my_write_log('Уничтожены изображения item #'.$item_id);
	
	//
	
	$qr = mydb_query("".
		" DELETE FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
		
	my_write_log('Уничтожен item #'.$item_id);
	
	return true;
}



// =============================================================================
function my_currency_text($c) {
	if ($c === false) return false;
	$c = (double)$c;
	$c = (round(100 * $c) / 100);
	$s = number_format($c, 2, '.', ' ');
	return $s;
}

?>