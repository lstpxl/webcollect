<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function can_i_submit_item() {

	if ($GLOBALS['user_id'] < 1) return false;

	$qr = mydb_queryarray("".
		" SELECT user.user_id ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		" AND ( user.can_submit_item = 'Y' ) ".
		"");
	//print_r($qr);
	if (sizeof($qr) < 1) return false;
	return true;

	return false;
}


// =============================================================================
function can_i_view_item($item_id) {

	$item_id = ''.intval($item_id);

	//if ($GLOBALS['user_id'] < 1) return false;

	if ($qr[0]['is_admin'] == 'Y') return true;
	//if ($qr[0]['is_moderator'] == 'Y') return true;

	if (am_i_submitter_of($item_id)) return true;
	
	$status = my_get_item_status($item_id);
	
	if ($status == 'W') {
		if (can_i_moderate_item($item_id)) return true;
	}

	if ($status == 'K') {
		// if (am_i_submitter_of($item_id)) return true;
		return true;
	}
	
	if ($status == 'H') {
		return true;
	}
	
	return false;
}


// =============================================================================
function can_i_edit_item($item_id) {

	$item_id = ''.intval($item_id);

	if ($GLOBALS['user_id'] < 1) return false;
	
	//my_write_log('uid='.$GLOBALS['user_id']);

	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.can_submit_item, user.is_admin, user.is_moderator, user.is_lim_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	//my_write_log('c1a');
	
	//my_write_log('sof='.sizeof($qr));
	
	if (sizeof($qr) != 1) return false;
	
	
	if ($qr[0]['is_admin'] == 'Y') return true;
	//if ($qr[0]['is_moderator'] == 'Y') return true;
	
	if (my_get_item_status($item_id) == 'W') {
		if (can_i_moderate_item($item_id)) return true;
	}

	if (my_get_item_status($item_id) == 'I') {
		if (am_i_submitter_of($item_id)) return true;
	}
	
	if (my_get_item_status($item_id) == 'K') {
		if (can_i_moderate_item($item_id)) return true;
	}
	
	//my_write_log('c3');

	// $item_id - ???

	return false;
}


// =============================================================================
function can_i_moderate_item($item_id) {

	$item_id = ''.intval($item_id);

	if ($GLOBALS['user_id'] < 1) return false;
	
	//my_write_log('uid='.$GLOBALS['user_id']);

	$qr = mydb_queryarray("".
		" SELECT user.user_id, user.username, ".
		" user.can_submit_item, user.is_admin, user.is_moderator, user.is_lim_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	//my_write_log('c1a');
	
	//my_write_log('sof='.sizeof($qr));
	
	if (sizeof($qr) != 1) return false;
	
	
	if ($qr[0]['is_admin'] == 'Y') return true;
	if ($qr[0]['is_moderator'] == 'Y') return true;
	
	if ($qr[0]['is_lim_moderator'] == 'Y') {
		if ($qr[0]['username'] == 'Dark') {
			$top_id = get_item_topshipclass_id($item_id);
			if ($top_id == 3) return true;
		}
	}
	
	//my_write_log('c3');

	// $item_id - ???

	return false;
}


// =============================================================================
function get_my_username() {
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.username ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	return $qr[0]['username'];
}


// =============================================================================
function get_username($user_id) {

	$user_id = intval($user_id);
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.username ".
		" FROM user ".
		" WHERE ( user.user_id = '".$user_id."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	return $qr[0]['username'];
}


// =============================================================================
function can_i_limmoderateclass($class_id) {

	$class_id = intval($class_id);
	
	if (am_i_lim_moderator()) {
		if (get_my_username() == 'Dark') {
			if ($class_id == 3) return true;
		}
	}

	return false;
}


// =============================================================================
function am_i_submitter_of($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT submitter_id ".
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
	
	if ($qr[0]['submitter_id'] == $GLOBALS['user_id']) return true;

	return false;
}


// =============================================================================
function can_i_saveinput_item($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.status ".
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
	
	if ($qr[0]['status'] == 'I') {

		if (am_i_admin()) return true;
		
		if (am_i_submitter_of($item_id)) return true;
		
	}

	return false;
}


// =============================================================================
function can_i_approve_item($item_id) {

	$item_id = ''.intval($item_id);

	if (!can_i_moderate_item($item_id)) return false;
	
	//print '('.$item_id.')';
	
	$qr = mydb_queryarray("".
		" SELECT item.status ".
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
	
	//print $qr[0]['status'];
	
	if ($qr[0]['status'] == 'W') return true;

	return false;
}


// =============================================================================
function can_i_report_correction($item_id) {

	$item_id = ''.intval($item_id);

	if (!am_i_registered_user()) return false;
	
	$status = my_get_item_status($item_id);
	if ($status === false) return false;
	if (mb_strpos('_WKH', $status)) return true;

	return false;
}


// =============================================================================
function can_i_remove_correction($correction_id) {

	if (!am_i_registered_user()) return false;
	
	if (am_i_admin()) return true;

	$correction_id = ''.intval($correction_id);
	
	$qr = mydb_queryarray("".
		" SELECT correction.item_id, ".
		" correction.user_id, correction.added ".
		" FROM correction ".
		" WHERE correction.correction_id = '".$correction_id."' ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		out_silent_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ($qr[0]['user_id'] == $GLOBALS['user_id']) return true;

	$status = my_get_item_status($qr[0]['item_id']);
	if ($status === false) return false;
	
	if (mb_strpos('_WK', $status)) return true;

	return false;
}


// =============================================================================
function can_i_reject_item($item_id) {
	return can_i_approve_item($item_id);
}


// =============================================================================
function can_i_delete_item($item_id) {

	$item_id = ''.intval($item_id);
	$status = my_get_item_status($item_id);
	if ($status === false) return false;
	
	if (am_i_admin()) {
		if ($status != 'D') return true;
	}
	
	if (am_i_submitter_of($item_id)) {
		if ($status == 'I') return true;
	}
	
	return false;
}


// =============================================================================
function can_i_undelete_item($item_id) {

	$item_id = ''.intval($item_id);
	$status = my_get_item_status($item_id);
	if ($status === false) return false;
	
	if (!(($status == 'D') || ($status == 'R'))) return false;
	
	if (am_i_admin()) {
		return true;
	}
	
	if (am_i_submitter_of($item_id)) {
		return true;
	}
		
	return false;
}


// =============================================================================
function am_i_registered_user() {
	// return ($GLOBALS['user_id'] > 0);
	return ($GLOBALS['is_registered_user']);
}


// =============================================================================
function am_i_emailverified_user() {
	
	if ($GLOBALS['user_id'] > 0) {
	
		// prepared query
		$a = array();
		$q = "".
			" SELECT user.email_verified, user.phone_verified, ".
			" user.firstname, user.lastname ".
			" FROM user ".
			" WHERE ( user.user_id = ? ) ".
			"";
		$a[] = $GLOBALS['user_id'];
		$t = 'i';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			die('mydb_prepquery() fatal error');
		}
		//
		
		return ($qres[0]['email_verified'] == 'Y');

	}
	
	return false;
	
}


// =============================================================================
function am_i_superadmin() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.can_submit_item, user.is_superadmin, user.is_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	if ($qr[0]['is_superadmin'] == 'Y') return true;
	// if ($qr[0]['is_moderator'] == 'Y') return true;
	
	return false;
}


// =============================================================================
function am_i_banned() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.is_ban ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	if ($qr[0]['is_ban'] == 'Y') return true;
	// if ($qr[0]['is_moderator'] == 'Y') return true;
	
	return false;
}


// =============================================================================
function am_i_admin() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.can_submit_item, user.is_admin, user.is_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	if ($qr[0]['is_admin'] == 'Y') return true;
	// if ($qr[0]['is_moderator'] == 'Y') return true;
	
	return false;
}



// =============================================================================
function am_i_vipcollector() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.is_vipcollector ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	if ($qr[0]['is_vipcollector'] == 'Y') return true;
	
	return false;
}


// =============================================================================
function am_i_moderator() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.can_submit_item, user.is_admin, user.is_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	// if ($qr[0]['is_admin'] == 'Y') return true;
	if ($qr[0]['is_moderator'] == 'Y') return true;
	
	return false;
}


// =============================================================================
function am_i_lim_moderator() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.can_submit_item, user.is_admin, user.is_lim_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	// if ($qr[0]['is_admin'] == 'Y') return true;
	if ($qr[0]['is_lim_moderator'] == 'Y') return true;
	
	return false;
}


// =============================================================================
function am_i_admin_or_moderator() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.can_submit_item, user.is_admin, user.is_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	if ($qr[0]['is_admin'] == 'Y') return true;
	if ($qr[0]['is_moderator'] == 'Y') return true;
	
	return false;
}


// =============================================================================
function am_i_admin_or_moderator_or_lim() {

	if ($GLOBALS['user_id'] < 1) return false;
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.can_submit_item, user.is_admin, user.is_moderator, user.is_lim_moderator ".
		" FROM user ".
		" WHERE ( user.user_id = '".$GLOBALS['user_id']."' ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) != 1) return false;
	
	if ($qr[0]['is_admin'] == 'Y') return true;
	if ($qr[0]['is_moderator'] == 'Y') return true;
	if ($qr[0]['is_lim_moderator'] == 'Y') return true;
	
	return false;
}


// =============================================================================
function can_i_upstore_ship($item_id) {

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	return true;
	
	// $item_id = ''.intval($item_id);
	// return false;
}



// =============================================================================
function can_i_ship_shipyard_upstore($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.shipmodel_id, item.shipyard_id, item.shipyard_str ".
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
	
	//
	
	
	if ($qr[0]['shipyard_id'] > 0) return false;
	
	if ($qr[0]['shipyard_str'] == '') return false;
	
	//

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	return true;
	
	// $item_id = ''.intval($item_id);
	// return false;
}


// =============================================================================
function can_i_ship_shipyard_uplink($item_id) {

	$item_id = ''.intval($item_id);
	
	//print '['.$item_id.']';
	
	//
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.ship_id, item.shipyard_id, item.shipyard_str ".
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
	
	//
	
	if ($qr[0]['ship_id'] < 1) return false;
	
	// print 'g7';
	
	$stored_id = my_get_ship_shipyard_id($qr[0]['ship_id']);
	
	//print '('.$stored_id.')';
	//print '('.$qr[0]['shipyard_id'].')';
	//print '('.$qr[0]['shipyard_str'].')';
	
	if ($stored_id == $qr[0]['shipyard_id']) return false;
	
	// print 'g8';

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	return true;
	
	// $item_id = ''.intval($item_id);
	// return false;
}


// =============================================================================
function can_i_uplink_ship_factoryserialnum($item_id) {

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	$item_id = ''.intval($item_id);
	
	$ship_id = get_item_ship_id($item_id);
	if ($ship_id < 1) return false;
	
	$model_id = my_get_ship_model_id($ship_id);
	if ($model_id < 1) {
		$topshipclass_id = get_item_topshipclass_id($item_id);
		if (can_i_limmoderateclass($topshipclass_id)) return true;
	} else {
		$class_id = my_get_shipmodel_class($model_id);
		if ($class_id < 1) {
			return true;
		} else {
			$top_id = my_get_top_shipmodelclass_id($class_id);
			if (can_i_limmoderateclass($top_id)) return true;
		}
	}
	
	return false;
}


// =============================================================================
function can_i_uplink_ship_hasmodel($item_id) {

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	$item_id = ''.intval($item_id);
	
	$ship_id = get_item_ship_id($item_id);
	if ($ship_id < 1) return false;
	
	$model_id = my_get_ship_model_id($ship_id);
	if ($model_id < 1) {
		$topshipclass_id = get_item_topshipclass_id($item_id);
		if (can_i_limmoderateclass($topshipclass_id)) return true;
	} else {
		$class_id = my_get_shipmodel_class($model_id);
		if ($class_id < 1) {
			return true;
		} else {
			$top_id = my_get_top_shipmodelclass_id($class_id);
			if (can_i_limmoderateclass($top_id)) return true;
		}
	}
	
	return false;
}


// =============================================================================
function can_i_uplink_shipmodelclass($item_id) {

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	$item_id = ''.intval($item_id);
	
	$item_class_id = get_item_shipclass_id($item_id);
	
	$shiphasmodel = get_item_is_ship_has_model($item_id);
	if ($shiphasmodel) {
		$model_id = get_item_shipmodel_id($item_id);
		if ($model_id < 1) {
			return false;
		} else {
			$class_id = my_get_shipmodel_class($model_id);
			if ($item_class_id == $class_id) return false;
			if ($class_id < 1) {
				//return true;
				if (am_i_admin_or_moderator()) return true;
			} else {
				$top_id = my_get_top_shipmodelclass_id($class_id);
				if (can_i_limmoderateclass($top_id)) return true;
				if (am_i_admin_or_moderator()) return true;
			}
		}
	} else {
		$ship_id = get_item_ship_id($item_id);
		if ($ship_id < 1) {
			return false;
		} else {
			$class_id = my_get_ship_class($ship_id);
			if ($item_class_id == $class_id) return false;
			if ($class_id < 1) {
				if (am_i_admin_or_moderator()) return true;
				//return true;
			} else {
				$top_id = my_get_top_shipmodelclass_id($class_id);
				if (can_i_limmoderateclass($top_id)) return true;
				if (am_i_admin_or_moderator()) return true;
			}
		}
	}
	
	return false;
}


// =============================================================================
function can_i_uplink_modelnatoc($item_id) {

	$item_id = ''.intval($item_id);
	
	//print 'w0';
	
	$model_id = get_item_shipmodel_id($item_id);
	if ($model_id < 1) return false;

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;

	$class_id = my_get_shipmodel_class($model_id);
	
	//print 'w1';
	
	if ($class_id < 1) {
		return true;
	} else {
		$top_id = my_get_top_shipmodelclass_id($class_id);
		if (can_i_limmoderateclass($top_id)) {
			//print 'w8';
			return true;
		}
	}

	return false;
}


// =============================================================================
function can_i_uplink_shipmodel($item_id) {

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	$item_id = ''.intval($item_id);
	
	$ship_id = get_item_ship_id($item_id);
	if ($ship_id < 1) return false;
	
	$model_id = my_get_ship_model_id($ship_id);
	if ($model_id < 1) {
		// return false;
		return true;
	} else {
		$class_id = my_get_shipmodel_class($model_id);
		if ($class_id < 1) {
			return true;
		} else {
			$top_id = my_get_top_shipmodelclass_id($class_id);
			if (can_i_limmoderateclass($top_id)) return true;
		}
	}
	
	return false;
}


// =============================================================================
function can_i_upstore_modelnatoc($item_id) {

	$item_id = ''.intval($item_id);
	
	//print 'p1';
	
	$model_id = get_item_shipmodel_id($item_id);
	if ($model_id < 1) return false;
	
	//print 'p2';

	if (am_i_admin_or_moderator()) return true;
	
	//print 'p3';

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	//print 'p4';
	

	$class_id = my_get_shipmodel_class($model_id);
	if ($class_id < 1) {
		return true;
	} else {
		$top_id = my_get_top_shipmodelclass_id($class_id);
		//print 'pc'.$top_id;
		if (can_i_limmoderateclass($top_id)) {
			//print 'y';
			return true;
		}
	}

	//print 'p5';
	
	return false;
}


// =============================================================================
function can_i_upstore_shipmodel($item_id) {

	if (am_i_admin_or_moderator()) return true;

	if (!am_i_admin_or_moderator_or_lim()) return false;
	
	print 'z';
	
	return true;
	
	/*
	$item_id = ''.intval($item_id);
	
	$model_id = get_item_shipmodel_id($item_id);
	if ($model_id < 1) {
		return false;
	} else {
		$class_id = my_get_shipmodel_class($model_id);
		if ($class_id < 1) {
			return true;
		} else {
			$top_id = my_get_top_shipmodelclass_id($class_id);
			if (can_i_limmoderateclass($top_id)) return true;
		}
	}
	
	return false;
	*/
}


?>