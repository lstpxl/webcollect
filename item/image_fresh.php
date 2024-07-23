<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');


// =============================================================================
function can_i_view_item_picture($item_id) {
	return true;
}


// =============================================================================
function pass_denied_image($s) {

	$filename = '/home/lastpx/www/site3/public_html/images/item_picture_denied_200x200.jpg';
	if ($s == 'l') $filename = '/home/lastpx/www/site3/public_html/images/item_picture_denied_500x500.jpg';
	if ($s == 's') $filename = '/home/lastpx/www/site3/public_html/images/item_picture_denied_100x100.jpg';
	
	header('Content-Type: image/jpeg');
	$handle = fopen($filename, "r");
	fpassthru($handle);
	fclose($handle);

	return true;
}


// =============================================================================
function get_item_fresh_index($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		//" SELECT item.item_id, item.time_submit_finish ".
		" SELECT item.item_id, item.time_approved ".
		" FROM item ".
		//" ORDER BY item.time_submit_finish DESC ".
		" ORDER BY item.time_approved DESC ".
		" LIMIT 9 ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	for ($i=0; $i<sizeof($qr); $i++) {
		if ($qr[$i]['item_id'] == $item_id) return $i;
	}

	return 999;
}


// =============================================================================
function localxhr($param) {

	// my_write_log('Image line '.__LINE__.'');

	// i = item_id
	// n = image index
	// s = size (o - original, m - list, l - large)
	
	if (!isset($param['i'])) return pass_denied_image($param['s']);
	if (!ctype_digit($param['i'])) return pass_denied_image($param['s']);
	$param['i'] = ''.intval($param['i']);
	
	$sizelist = array('m', 'l', 's', 'o');
	if (!isset($param['s'])) $param['s'] = $sizelist[0];
	if (!in_array($param['s'], $sizelist)) $param['s'] = $sizelist[0];
	
	if (!am_i_admin()) {
		if (($param['s'] == 'l') || ($param['s'] == 'o')) {
			return pass_denied_image($param['s']);
		}
	}

	$freshindex = get_item_fresh_index($param['i']);
	if (!$GLOBALS['is_registered_user']) {
		if ($freshindex > 6) {
			return pass_denied_image($param['s']);
		}
	}
	
	// my_write_log('Image line '.__LINE__.'');
	
	if (!isset($param['n'])) $param['n'] = '1';
	if (!ctype_digit($param['n'])) $param['n'] = '1';
	$param['n'] = ''.intval($param['n']);
	
	$filename = my_get_item_picture_filepath($param['i'], $param['n'], $param['s']);
	
	// my_write_log('Image line '.__LINE__.'');
	//my_write_log('Image filename '.$filename.'');
	
	if ($filename === false) {
		my_write_log('!!!!');
		return pass_denied_image($param['s']);
	}
	
	//my_write_log('Image line '.__LINE__.'');
	//my_write_log('Image filename '.$filename.'');

	//header('Content-Type: text/html; charset=utf-8');
	//print $filename;

	header('Content-Type: image/jpeg');
	send_headers_2weeks();
	
	$handle = fopen($filename, "r");
	fpassthru($handle);
	fclose($handle);

	return true;
}

?>