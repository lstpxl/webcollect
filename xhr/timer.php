<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/refresh_downlink.php');

// =============================================================================
function outhtml_image_crc_rebuild_part() {

	print 'Call outhtml_image_crc_rebuild_part(); ';

	$q = " SELECT item.item_id ".
		" FROM item ".
		" WHERE item.imgcrc = '0' ".
		" ORDER BY item.item_id ".
		" LIMIT 80 ".
		" ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	my_write_log('CRC queue size = '.sizeof($qr).';', 'S');
	
	for ($i = 0; $i < sizeof($qr); $i++) {
	
		// my_write_log('CRC image ='.$param['key']);
	
		$crc = calc_item_image_crc($qr[$i]['item_id']);
		
		if ($crc !== false) {
			my_write_log('CRC item #'.$qr[$i]['item_id'].' = '.$crc.';');
		} else {
			my_write_log('CRC item #'.$qr[$i]['item_id'].' calc problem;');
		}
		
		if ($crc !== false) {
			$qru = mydb_query("".
				" UPDATE item ".
				" SET item.imgcrc = '".$crc."' ".
				" WHERE item.item_id = '".$qr[$i]['item_id']."' ".
				"");
			if (!$qru) {
				my_write_log('ERROR;');
				return false;
			}
		}
		
	}

	return true;
}


// =============================================================================
function timer_cache($param) {

	print 'Call timer_cache(); ';

	$param = array();
	
	treeindex_rebuld_group_recursive_ztop();
	print '<p>treeindex_rebuld_group_recursive_ztop() finished.</p>';
	
	calc_downlink_n_items(10);
	print '<p>calc_downlink_n_items() finished.</p>';
	
	// treeindex_rebuld_group_recursive_z('shipmodelclass', 0);

	// outhtml_shipclass_tree_result_rebuild_random($param);
	
	// outhtml_image_crc_rebuild_part();
	
	// treeindex_rebuld_group_recursive_z($type, $id);

	return true;
}




	
// =============================================================================
function nouser_request() {
	
	$_SERVER['REMOTE_ADDR'] = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') { $param = $_POST; }
	elseif ($_SERVER['REQUEST_METHOD'] == 'GET') { $param = $_GET; }
	else { $param = array(); }
	
	if ($param['key'] != 'C3mT6PqZ') return false;
	
	// http://navy.webcollect.ru/xhr/timer.php?key=C3mT6PqZ
	
	$GLOBALS['user_id'] = false;
	$GLOBALS['visitor_id'] = false;
	$GLOBALS['is_registered_user'] = false;
	$GLOBALS['head_scripts'] = array();
	$GLOBALS['body_script_str'] = '';
	$GLOBALS['submenu_html'] = '';
	
	header('Content-Type: text/html; charset=utf-8');
	
	$start_time = microtime(true);
	
	//
	
	timer_cache($param);
	
	//
	
	$end_time = microtime(true);
	// $end_array = explode(" ",$end_time);
	// $end_time = (float)$end_array[1] + (float)$end_array[0];
	
	$timeconsumed = ($end_time - $start_time);
	$microtimestr = number_format($timeconsumed, 3, '.', '');
	my_write_log('System timer request. Consumed '.$microtimestr.' seconds.', 'S');
	
	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/timer.php') > 0) {
	return nouser_request();
}

?>