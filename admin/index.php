<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/admin_shipmodel_duplicate.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/admin_ship_duplicate.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/admin_item_duplicate.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/admin_shipmodel_orphans.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/admin_ship_orphans.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/user_toggle_submitter.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/user_toggle_moderator.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/user_toggle_lim_moderator.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/user_toggle_admin.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/user_toggle_ban.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/user_toggle_vipcollector.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/shipclass_tree.php');


// =============================================================================
function outhtml_user_list($param) {
	
	$out = '';
	
	$out .= outhtml_script_user_toggle_submitter();
	$out .= outhtml_script_user_toggle_moderator();
	$out .= outhtml_script_user_toggle_lim_moderator();
	$out .= outhtml_script_user_toggle_admin();
	$out .= outhtml_script_user_toggle_ban();
	$out .= outhtml_script_user_toggle_vipcollector();
	
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.email_address, user.username, user.time_registered, ".
		" user.time_last_request, user.login_count, ".
		" user.is_moderator, user.is_lim_moderator, user.is_admin, ".
		" user.can_submit_item, user.is_ban, user.is_vipcollector, ".
		" user.firstname, user.lastname, ".
		" user.phone_number, user.phone_verified ".
		" FROM user ".
		" WHERE user.is_registered_user = 'Y' ".
		" ORDER BY time_registered ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) < 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<table class="userlist">';
	
	$out .= '<tr class="header">';
	
	$out .= '<td>';
	$out .= 'ФИО';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'login';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'e-mail / телефон';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'зарег-н';
	$out .= '</td>';
	$out .= '<td style=" ">';
	$out .= 'входов на сайт';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'последнее посещение';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'админ-р';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'модер-р';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'vip';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'огр. мод.';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'может размещать';
	$out .= '</td>';
	$out .= '<td>';
	$out .= 'бан';
	$out .= '</td>';
	
	$out .= '</tr>';
	

	for ($i = 0; $i < sizeof($qr); $i++) {
		$ins = (($i%2) == 0) ? ' class="even" ':'';
		$out .= '<tr '.$ins.'>';
		$out .= '<td>';
		$out .= ''.$qr[$i]['lastname'];
		$out .= ' '.$qr[$i]['firstname'];
		$out .= '</td>';
		$out .= '<td>';
		$out .= ''.$qr[$i]['username'];
		$out .= '</td>';
		$out .= '<td>';
		$out .= ''.$qr[$i]['email_address'];
		if ($qr[$i]['phone_verified'] == 'Y') $out .= ' ('.$qr[$i]['phone_number'].') ';
		$out .= '</td>';
		$out .= '<td>';
		$out .= '<nobr>'.date('d/m/y', strtotime($qr[$i]['time_registered'])).'</nobr>';
		$out .= '</td>';
		$out .= '<td style=" text-align: right; ">';
		$out .= ''.$qr[$i]['login_count'];
		$out .= '</td>';
		$out .= '<td>';
		$out .= '<nobr>'.$qr[$i]['time_last_request'].'</nobr>';
		$out .= '</td>';
		$out .= '<td>';
		$out .= outhtml_user_toggle_admin_div(array('user_id' => $qr[$i]['user_id']));
		$out .= '</td>';
		$out .= '<td>';
		$out .= outhtml_user_toggle_moderator_div(array('user_id' => $qr[$i]['user_id']));
		$out .= '</td>';
		$out .= '<td>';
		$out .= outhtml_user_toggle_vipcollector_div(array('user_id' => $qr[$i]['user_id']));
		$out .= '</td>';
		$out .= '<td>';
		$out .= outhtml_user_toggle_lim_moderator_div(array('user_id' => $qr[$i]['user_id']));
		$out .= '</td>';
		$out .= '<td>';
		$out .= outhtml_user_toggle_submitter_div(array('user_id' => $qr[$i]['user_id']));
		$out .= '</td>';
		$out .= '<td>';
		$out .= outhtml_user_toggle_ban_div(array('user_id' => $qr[$i]['user_id']));
		$out .= '</td>';
		$out .= '</tr>';
	}
	
	$out .= '</table>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_logs($param) {

	$GLOBALS['pagetitle'] = 'Журнал / '.$GLOBALS['pagetitle'];

	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left; clear: none; width: 625px; padding: 40px 5px 10px 20px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px;  ">Журнал</h1>';
			
			//
			
			$hoursback = (24*7);
			$secondsback = (60*60*$hoursback);
			
			//
			
			$qr = mydb_queryarray("".
				" SELECT log.log_id, ".
				" log.type, log.time, log.remote_ip, ".
				" log.uv, log.user_id, log.message ".
				" FROM log ".
				" WHERE ( log.time > '".date('Y-m-d H:i:s', (time() - $secondsback))."' ) ".
				" AND ( log.type = 'Q' ) ".
				// " AND ( log.user_id = '18' ) ".
				" ORDER BY log.log_id ".
				"");
			if ($qr === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			//
			
			$out .= '<p>Всего событий за '.$hoursback.' часов: '.sizeof($qr).'</p>';
			
			//
			
			$out .= '<table class="userlist">';
				
				$out .= '<tr class="header">';
					
					$out .= '<td>';
					$out .= 'время';
					$out .= '</td>';
					$out .= '<td>';
					$out .= 'тип';
					$out .= '</td>';
					$out .= '<td>';
					$out .= 'ip';
					$out .= '</td>';
					$out .= '<td style=" ">';
					$out .= 'польз';
					$out .= '</td>';
					$out .= '<td>';
					$out .= 'сообщение';
					$out .= '</td>';
				
				$out .= '</tr>';
				

				for ($i = 0; $i < sizeof($qr); $i++) {
				
					$ins = (($i%2) == 0) ? ' class="even" ':'';
					$out .= '<tr '.$ins.'>';
					
					$out .= '<td>';
					$out .= ''.$qr[$i]['time'];
					$out .= '</td>';
					
					$colorins = '';
					if ($qr[$i]['type'] != 'N') $colorins = ' background-color: #a060a0; ';
					if ($qr[$i]['type'] == 'Q') $colorins = ' background-color: #a00000; color: #ffffff; ';
					if ($qr[$i]['type'] == 'E') $colorins = ' background-color: #f03030; ';
					if ($qr[$i]['type'] == 'W') $colorins = ' background-color: #f0c030; ';
					$out .= '<td style=" '.$colorins.' " >';
					$out .= ''.$qr[$i]['type'];
					$out .= '</td>';
					
					$out .= '<td>';
					$out .= ''.$qr[$i]['remote_ip'];
					$out .= '</td>';
					
					$out .= '<td>';
					if ($qr[$i]['uv'] == 'U') {
						$out .= ''.htmlspecialchars(get_username($qr[$i]['user_id']), ENT_QUOTES);
					} else {
						$out .= '&mdash;';
					}
					$out .= '</td>';
					
					$out .= '<td>';
					$out .= ''.htmlspecialchars($qr[$i]['message'], ENT_QUOTES);
					$out .= '</td>';
					
					$out .= '</tr>';
				}
			
			$out .= '</table>';
			
		//
		
		$out .= '</div>';

	$out .= '</div>';
	
	//
	
	$out .= '<div style=" margin-bottom: 50px; " >';
	$out .= '</div>';
	
	//

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_summary($param) {

	$GLOBALS['pagetitle'] = 'Сводка / '.$GLOBALS['pagetitle'];

	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Сводка</h1>';
			
			//
			
			$qr1 = mydb_queryarray("".
				" SELECT count(user.user_id) AS n ".
				" FROM user ".
				" WHERE user.is_registered_user = 'Y' ".
				"");
			if ($qr1 === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($qr1) != 1) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			$visitors = mydb_queryarray("".
				" SELECT count(visitor.visitor_id) AS n ".
				" FROM visitor ".
				" WHERE visitor.time_last_request > '".date('Y-m-d H:i:s', (time() - (60*60*24*7)))."' ".
				"");
			if ($visitors === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($visitors) != 1) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			// Request from user
			$qr3 = mydb_queryarray("".
				" SELECT count(log.log_id) AS n ".
				" FROM log ".
				" WHERE ( LOCATE('Request from user', log.message) > 0 ) ".
				" AND ( log.time > '".date('Y-m-d H:i:s', (time() - (60*60*24*1)))."' ) ".
				"");
			if ($qr3 === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($qr3) != 1) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			// Request from users
			$qr = mydb_queryarray("".
				" SELECT count(log.log_id) AS n ".
				" FROM log ".
				" WHERE ( log.uv = 'U' ) ".
				" AND ( LOCATE('http request', log.message) > 0 ) ".
				" AND ( log.time > '".date('Y-m-d H:i:s', (time() - (60*60*24*1)))."' ) ".
				"");
			if ($qr === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($qr) != 1) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			$num_visitors_users = $qr[0]['n'];
			
			// Request from visitors
			$qr = mydb_queryarray("".
				" SELECT count(log.log_id) AS n ".
				" FROM log ".
				" WHERE ( log.uv = 'V' ) ".
				" AND ( LOCATE('http request', log.message) > 0 ) ".
				" AND ( log.time > '".date('Y-m-d H:i:s', (time() - (60*60*24*1)))."' ) ".
				"");
			if ($qr === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($qr) != 1) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			$num_visitors_requests = $qr[0]['n'];
			
			// online users
			$qr4 = mydb_queryarray("".
				" SELECT user.user_id, user.time_last_request ".
				" FROM user ".
				" WHERE user.is_registered_user = 'Y' ".
				" AND user.time_last_request > '".date('Y-m-d H:i:s', (time() - (60*60*2)))."' ".
				" ORDER BY user.time_last_request DESC ".
				"");
			if ($qr4 === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			
			// items to refresh
			$qrz = mydb_queryarray("".
				" SELECT COUNT(item.item_id) AS n ".
				" FROM item ".
				" WHERE item.refresh = 'Y' ".
				"");
			if ($qrz === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			$items_to_refresh = $qrz[0]['n'];
			
			// classes to refresh
			$qrz = mydb_queryarray("".
				" SELECT COUNT(shipmodelclass.shipmodelclass_id) AS n ".
				" FROM shipmodelclass ".
				" WHERE shipmodelclass.refresh = 'Y' ".
				"");
			if ($qrz === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			$classes_to_refresh = $qrz[0]['n'];
			
			// models to refresh
			$qrz = mydb_queryarray("".
				" SELECT COUNT(shipmodel.shipmodel_id) AS n ".
				" FROM shipmodel ".
				" WHERE shipmodel.refresh = 'Y' ".
				"");
			if ($qrz === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			$models_to_refresh = $qrz[0]['n'];
			
			// ships to refresh
			$qrz = mydb_queryarray("".
				" SELECT COUNT(ship.ship_id) AS n ".
				" FROM ship ".
				" WHERE ship.refresh = 'Y' ".
				"");
			if ($qrz === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			$ships_to_refresh = $qrz[0]['n'];
			
			//
			
			$out .= '<div style=" margin-bottom: 20px; padding-left: 18px; ">';
				$out .= '<p>Всего зарегистрированных пользователей: '.$qr1[0]['n'].'</p>';
				$out .= '<p>Всего незарегистрированных посетителей за неделю: '.$visitors[0]['n'].'</p>';
				$out .= '<p>Запросов от посетителей за последние 24 часа: '.$num_visitors_users.'</p>';
				$out .= '<p>Запросов от зарегистрированных пользователей за последние 24 часа: '.$num_visitors_requests.'</p>';
				$out .= '<p>Ждет пересчета в структуре: '.$classes_to_refresh.' классов, '.$models_to_refresh.' проектов, '.$ships_to_refresh.' кораблей, '.$items_to_refresh.' знаков.</p>';
			$out .= '</div>';
			
			$out .= '<div style=" margin-bottom: 20px; padding-left: 18px; ">';
				$out .= '<p>Пользователи онлайн:</p>';
			for ($i = 0; $i < sizeof($qr4); $i++) {
				$out .= '<p style=" padding-left: 20px; ">';
					$out .= my_get_user_name($qr4[$i]['user_id']);
					$out .= ' &mdash; ';
					$tlr = strtotime($qr4[$i]['time_last_request']);
					$delta = round((double)(time() - $tlr) / (double)60);
					$out .= ''.$delta.' мин назад';
				$out .= '</p>';
			}
			$out .= '</div>';
		
		$out .= '</div>';
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
			//

		$out .= '</div>';

	$out .= '</div>';
	
	$out .= '<div style=" clear: both; "></div>';
	
	$out .= '<div style=" padding-left: 20px; margin-bottom: 30px; " >';
		$out .= '<a href="/admin/batch_upload.php">Пакетная загрузка</a>';
	$out .= '</div>';
	
	$out .= '<div style=" padding-left: 20px; margin-bottom: 30px; " >';
		$out .= '<a href="/admin/downlink_total.php">Обновить данные знаков на основании данных проектов и кораблей</a>';
	$out .= '</div>';
	
	/*
	if ($GLOBALS['user_id'] == 2) {
		$out .= '<div style=" padding-left: 20px; margin-bottom: 30px; " >';
			$out .= '<a href="/admin/manual_sort_item.php">Ручная сортировка знаков</a>';
		$out .= '</div>';
	}
	*/
	
		//if ($GLOBALS['user_id'] == 2) {
			$out .= '<div style=" padding-left: 20px; margin-bottom: 30px; " >';
				$out .= '<a href="/admin/manual_sort.php">Ручная сортировка</a>';
			$out .= '</div>';
		//}
		
		$out .= '<div style=" padding-left: 20px; margin-bottom: 30px; " >';
			$out .= '<a href="/admin/send_robot_message.php">Отправить сообщение от сайта</a>';
		$out .= '</div>';
		
		//
	
		if ($GLOBALS['user_id'] == 2) {
			$out .= '<form action="http://websql.z8.ru" method="post" target="_blank">';
				$out .= '<input type="hidden" name="action" value="login">';
				$out .= '<input type="hidden" name="host" value="wkh.mysql">';
				$out .= '<input type="hidden" name="user" value="dbu_wkh_1">';
				$out .= '<input type="hidden" name="password" value="CdBXOy4xaKB">';
				$out .= '<div style=" padding-top: 8px; vertical-align: top; ">';
					$out .= '<button class="lightbluegradient hoverlightblueborder" type="submit" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; "type="submit"><span>phpMyAdmin</span></button>';
				$out .= '</div>';
			$out .= '</form>';
		}
	
	$out .= '<div style=" padding-left: 20px; margin-bottom: 50px; " >';
	
	$out .= '</div>';
	

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_users($param) {

	$GLOBALS['pagetitle'] = 'Пользователи / '.$GLOBALS['pagetitle'];

	$out = '';
	
	$out .= '<h1 class="grayemb" style=" margin-bottom: 10px; padding-left: 18px; ">Управление пользователями</h1>';
	
	$out .= '<div style=" padding-left: 20px; margin-bottom: 30px; " >';
		
		$out .= '<div style=" color: #888888; line-height: 125%; ">';

			$out .= outhtml_user_list($param);
		
		$out .= '</div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_deleted_items($param) {

	$out = '';
	
	if (!am_i_admin()) return '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.status = 'D' ".
		" ORDER BY time_submit_finish, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$z = sizeof($qr);
	$max = (9 * 80);

	$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; color: #909090; margin-top: 30px; ">Удаленные знаки</h1>';
	
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Удаленных: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	if ($z > $max) {
		$z = $max;
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Показаны: <span style=" color: #66737b; ">'.$z.'<span></div>';
	}
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$out .= '<div>';
	
	for ($i = 0; $i < $z; $i++) {
		$out .= outhtml_item_inlist_small_moderation($qr[$i]['item_id']);
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_classify_help_items($param) {

	$out = '';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.status = 'H' ".
		" ORDER BY time_submit_finish, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$z = sizeof($qr);
	$max = (9 * 20);


	$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; color: #909090; margin-top: 30px; ">Требуется помощь в классификации</h1>';
	
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Всего: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	if ($z > $max) {
		$z = $max;
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Показаны: <span style=" color: #66737b; ">'.$z.'<span></div>';
	}
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$out .= '<div>';
	
	for ($i = 0; $i < $z; $i++) {
		$out .= outhtml_item_inlist_smallth($qr[$i]['item_id'], 'submitter submittime');
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_ustorage_items($param) {

	$out = '';

	if (!am_i_admin()) return '';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.status = 'U' ".
		" ORDER BY time_submit_finish, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$z = sizeof($qr);
	$max = (9 * 20);


	$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; color: #909090; margin-top: 30px; ">Хранилище </h1>';
	
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Всего: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	if ($z > $max) {
		$z = $max;
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Показаны: <span style=" color: #66737b; ">'.$z.'<span></div>';
	}
	
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$out .= '<div>';
	
	for ($i = 0; $i < $z; $i++) {
		$out .= outhtml_item_inlist_smallth($qr[$i]['item_id'], 'submitter submittime');
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_duplicates($param) {

	if (!isset($param['obj'])) $param['obj'] = 'ship';

	$GLOBALS['pagetitle'] = 'Дубликаты / '.$GLOBALS['pagetitle'];

	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			
			if ($param['obj'] == 'ship') $objstr = 'Корабли';
			if ($param['obj'] == 'model') $objstr = 'Проекты';
			if ($param['obj'] == 'modelorphans') $objstr = 'Проекты-сироты';
			if ($param['obj'] == 'shiporphans') $objstr = 'Корабли-сироты';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Дубликаты. '.$objstr.'</h1>';
			
			if ($param['obj'] == 'ship') $out .= outhtml_administration_duplicates_ship($param);
			
			if ($param['obj'] == 'model') $out .= outhtml_administration_duplicates_model($param);
			
			if ($param['obj'] == 'item') $out .= outhtml_administration_duplicates_item($param);
			
			if ($param['obj'] == 'modelorphans') $out .= outhtml_administration_orphans_model($param);
			
			if ($param['obj'] == 'shiporphans') $out .= outhtml_administration_orphans_ship($param);
			
			
		$out .= '</div>';
		
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=ship';
			$out .= '<a href="'.$href.'" style=" display: block; font-size: 11pt; margin-bottom: 20px; padding-left: 18px; ">Корабли</a>';
			
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=model';
			$out .= '<a href="'.$href.'" style=" display: block; font-size: 11pt; margin-bottom: 20px; padding-left: 18px; ">Проекты</a>';
			
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=item';
			$out .= '<a href="'.$href.'" style=" display: block; font-size: 11pt; margin-bottom: 20px; padding-left: 18px; ">Знаки</a>';
			
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=shiporphans';
			$out .= '<a href="'.$href.'" style=" display: block; font-size: 11pt; margin-bottom: 20px; padding-left: 18px; ">Корабли-сироты</a>';
			
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=modelorphans';
			$out .= '<a href="'.$href.'" style=" display: block; font-size: 11pt; margin-bottom: 20px; padding-left: 18px; ">Проекты-сироты</a>';
			
			
			
			

		$out .= '</div>';
		
		$out .= '<div style=" clear: both; " ></div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_class($param) {

	$GLOBALS['pagetitle'] = 'Классификация проектов кораблей / '.$GLOBALS['pagetitle'];

	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8f; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Классификация проектов кораблей</h1>';
			
			$out .= outhtml_shipclass_tree($param);
			
		$out .= '</div>';
		
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
		//

		$out .= '</div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_submenu(&$param) {

	$out = '';

	$list = array();
	$list[] = array('code' => 'a', 'text' => 'Сводка');
	$list[] = array('code' => 'l', 'text' => 'Журнал');
	$list[] = array('code' => 'u', 'text' => 'Пользователи');
	$list[] = array('code' => 'c', 'text' => 'Классификация');
	// $list[] = array('code' => 's', 'text' => 'Классификация кораблей');
	$list[] = array('code' => 'd', 'text' => 'Удаленные');
	$list[] = array('code' => 'h', 'text' => 'Помогите');
	$list[] = array('code' => 'z', 'text' => 'Хранилище');
	$list[] = array('code' => 'dup', 'text' => 'Дубликаты');
	$defaultcode = 'a';
	
	foreach ($list as &$e) {
		$e['current'] = false;
		$e['href'] = '/index.php?m=a&sm='.$e['code'];
	}
	
	//
	
	if (!isset($param['sm'])) $param['sm'] = $defaultcode;
	$sm_found = false;
	foreach ($list as &$e) {
		if ($param['sm'] == $e['code']) $sm_found = true;
	}
	if (!$sm_found) $param['sm'] = $defaultcode;
	foreach ($list as &$e) {
		if ($param['sm'] == $e['code']) $e['current'] = true;
	}
	
	//
	
	$GLOBALS['submenu_html'] = outhtml_submenu($list);
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_index($param) {

	if (!am_i_admin()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}
	
	$GLOBALS['pagetitle'] = 'Администрирование / '.$GLOBALS['pagetitle'];
	
	$out = '';

	$out .= outhtml_administration_submenu(&$param);
	
	if ($param['sm'] == 'a') $out .= outhtml_administration_summary($param);
	if ($param['sm'] == 'l') $out .= outhtml_administration_logs($param);
	if ($param['sm'] == 'u') $out .= outhtml_administration_users($param);
	if ($param['sm'] == 'c') $out .= outhtml_administration_class($param);
	if ($param['sm'] == 'd') $out .= outhtml_administration_deleted_items($param);
	if ($param['sm'] == 'h') $out .= outhtml_classify_help_items($param);
	if ($param['sm'] == 'z') $out .= outhtml_ustorage_items($param);
	if ($param['sm'] == 'dup') $out .= outhtml_administration_duplicates($param);
	
	return $out.PHP_EOL;
}

?>