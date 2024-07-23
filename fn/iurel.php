<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function iurel_item_get_number_owners($item_id) {

	$item_id = ''.intval($item_id);

	// есть ли такие
	if (my_get_item_status($item_id) === false) return false;

	//
	$q = " SELECT COUNT( DISTINCT iurel.user_id ) AS n ".
		" FROM iurel ".
		" WHERE iurel.gotit = 'Y' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['n'];
}


// =============================================================================
function iurel_item_get_list_owners($item_id) {

	$item_id = ''.intval($item_id);

	// есть ли такие
	if (my_get_item_status($item_id) === false) return false;

	//
	$q = " SELECT DISTINCT iurel.user_id ".
		" FROM iurel ".
		" WHERE iurel.gotit = 'Y' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr;
}


// =============================================================================
function iurel_item_get_number_wanting($item_id) {

	$item_id = ''.intval($item_id);

	// есть ли такие
	if (my_get_item_status($item_id) === false) return false;

	//
	$q = " SELECT COUNT( DISTINCT iurel.user_id ) AS n ".
		" FROM iurel ".
		" WHERE iurel.wantit = 'Y' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr[0]['n'];
}


// =============================================================================
function iurel_check_exist($item_id, $user_id) {

	$item_id = ''.intval($item_id);
	$user_id = ''.intval($user_id);

	// есть ли такие
	if (my_get_item_status($item_id) === false) return false;
	if (my_get_user_name($user_id) === false) return false;

	//
	$q = " SELECT iurel.iurel_id ".
		" FROM iurel ".
		" WHERE iurel.user_id = '".$user_id."' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) > 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (sizeof($qr) > 0) return true;
	
	$qr = mydb_query("".
		" INSERT INTO iurel ".
		" SET iurel.user_id = '".$user_id."', ".
		" iurel.item_id = '".$item_id."' ".
		"");

	return true;
}


// =============================================================================
function iurel_get_defaults() {

	$r = array(
		'gotit' => 'N',
		'wantit' => 'N',
		'sellit' => 'N',
		'sellprice' => '0.00',
		'initialprice' => '0.00',
		'storageplace' => '',
		'personalnote' => '');

	return $r;
}


// =============================================================================
function iurel_get_value($item_id, $user_id, $v) {

	$item_id = ''.intval($item_id);
	$user_id = ''.intval($user_id);

	// есть ли такие
	if (my_get_item_status($item_id) === false) return false;
	if (my_get_user_name($user_id) === false) return false;
	if (!is_string($v)) return false;
	$defaults = iurel_get_defaults();
	if (!array_key_exists($v, $defaults)) return false;

	//
	$q = " SELECT iurel.* ".
		" FROM iurel ".
		" WHERE iurel.user_id = '".$user_id."' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) > 1) {
		// print ' '.$item_id.'-'.$user_id;
		//
		for ($i = 1; $i < sizeof($qr); $i++) {
			$qr = mydb_query("".
				" DELETE FROM iurel ".
				" WHERE iurel.iurel_id = '".$qr[$i]['iurel_id']."'; ".
				"");
		}
		//
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (sizeof($qr) == 1) {
	
		// берем из базы
		if (!isset($qr[0][$v])) return false;
		return $qr[0][$v];
		
	} else {
	
		// берем дефолтное
		return $defaults[$v];
		
	}
	
	return false;
}


// =============================================================================
function my_iurel_get($item_id, $v) {

	if (!$GLOBALS['is_registered_user']) return false;

	$item_id = ''.intval($item_id);
	$user_id = $GLOBALS['user_id'];

	if (!is_string($v)) return false;
	$defaults = iurel_get_defaults();
	if (!array_key_exists($v, $defaults)) return false;

	//
	$q = " SELECT iurel.".$v." ".
		" FROM iurel ".
		" WHERE iurel.user_id = '".$user_id."' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) > 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (sizeof($qr) == 1) {
	
		// берем из базы
		if (!isset($qr[0][$v])) return false;
		return $qr[0][$v];
		
	} else {
	
		// берем дефолтное
		return $defaults[$v];
		
	}
	
	return false;
}


// =============================================================================
function iurel_write_value($item_id, $user_id, $v, $newvalue) {

	$item_id = ''.intval($item_id);
	$user_id = ''.intval($user_id);
	
	if (!is_string($v)) return false;
	$list = iurel_get_defaults();
	if (!array_key_exists($v, $list)) return false;
	
	// prepared query
	$a = array();
	$a[] = $newvalue;
	$a[] = $user_id;
	$a[] = $item_id;
	$q = "".
		" UPDATE iurel ".
		" SET iurel.".$v." = ? ".
		" WHERE iurel.user_id = ? ".
		" AND iurel.item_id = ? ".
		";";
	$t = 'sii';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	return true;
}



// =============================================================================
function iurel_set_value($item_id, $user_id, $v, $newvalue) {

	$item_id = ''.intval($item_id);
	$user_id = ''.intval($user_id);

	// есть ли такие
	if (my_get_item_status($item_id) === false) return false;
	if (my_get_user_name($user_id) === false) return false;
	if (!is_string($v)) return false;
	$defaults = iurel_get_defaults();
	if (!array_key_exists($v, $defaults)) return false;

	//
	$q = " SELECT iurel.iurel_id ".
		" FROM iurel ".
		" WHERE iurel.user_id = '".$user_id."' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) > 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (sizeof($qr) > 0) {
	
		// есть в базе, пишем в базу
		return iurel_write_value($item_id, $user_id, $v, $newvalue);
		
	} else {
	
		if ($defaults[$v] != $newvalue) {
		
			// добавляем и пишем в базу
			if (!iurel_check_exist($item_id, $user_id)) return false;
			return iurel_write_value($item_id, $user_id, $v, $newvalue);
			
		} else {
			// пропускаем
		}
		
	}
	
	return true;
}


// =============================================================================
function update_iurel_searchstring($item_id, $user_id) {

	$item_id = ''.intval($item_id);
	$user_id = ''.intval($user_id);

	// есть ли такие
	if (my_get_item_status($item_id) === false) return false;
	if (my_get_user_name($user_id) === false) return false;

	//
	$q = " SELECT iurel.* ".
		" FROM iurel ".
		" WHERE iurel.user_id = '".$user_id."' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) > 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) < 1) {
		return false;
	}
	
	//
	
	$s = '';
	if ($qr[0]['sellprice'] > 0) $s .= ' '.$qr[0]['sellprice'];
	if ($qr[0]['initialprice'] > 0) $s .= ' '.$qr[0]['initialprice'];
	$s .= ' '.$qr[0]['storageplace'];
	$s .= ' '.$qr[0]['personalnote'];
	
	//
	
	// prepared query
	$a = array();
	$a[] = $s;
	$a[] = $user_id;
	$a[] = $item_id;
	$q = "".
		" UPDATE iurel ".
		" SET iurel.iusearchstring = ? ".
		" WHERE iurel.user_id = ? ".
		" AND iurel.item_id = ? ".
		";";
	$t = 'sii';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
		
	return true;
}


?>