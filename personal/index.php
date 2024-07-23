<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_search_result.php');



// =============================================================================
function outhtml_personal_item_list_input($param) {

	
	$GLOBALS['pagetitle'] = 'Знаки в загрузке / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Знаки в загрузке</h1>';

		$out .= '</div>';

	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.status = 'I' ".
		" AND item.submitter_id = '".$GLOBALS['user_id']."' ".
		" ORDER BY time_submit_start DESC, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$z = sizeof($qr);
	$max = (9 * 30);
		
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Элементов всего: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	
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
			$out .= outhtml_item_inlist_small_moderation($qr[$i]['item_id']);
		}
		$out .= '</div>';
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_personal_item_list_moderated($param) {

	$GLOBALS['pagetitle'] = 'Знаки на модерации / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Знаки на модерации</h1>';

		$out .= '</div>';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.status = 'W' ".
		" AND item.submitter_id = '".$GLOBALS['user_id']."' ".
		" ORDER BY time_submit_finish DESC, item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$z = sizeof($qr);
	$max = (9 * 30);
		
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Элементов всего: <span style=" color: #66737b; ">'.sizeof($qr).'<span></div>';
	
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
		$out .= outhtml_item_inlist_small_moderation($qr[$i]['item_id']);
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_collection($param) {
	
	$GLOBALS['pagetitle'] = 'Моя коллекция / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist.php');
	$out .= outhtml_script_item_inlist();
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		
	
		$out .= '<div style=" float: left;  clear: none; width: 960px; padding: 0px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= outhtml_script_item_search_result();
			
			$out .= '<div id="item_search_result_div">';
					
				// $d = array('my' => '2', 'sort' => 'c', 'mode' => 'browse');
				if (!isset($param['my'])) $param['my'] = '2';
				//if (!isset($param['my'])) $param['my'] = '2';
				$out .= outhtml_item_search_result($param);
			
			$out .= '</div>';

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_passwordchange_action($param) {

	$out = '';
	
	//print 'z1';
	
	if (!isset($param['action'])) return '';
	if ($param['action'] != 'changepass')  return '';
	
	//print 'z2';
	
	if (!isset($param['curpass'])) return '';
	if (!isset($param['newpass1'])) return '';
	if (!isset($param['newpass2'])) return '';
	
	//print 'z3';
	
	$password_hash = hash('sha256', 'SaLt'.$param['curpass']);
	
	$qr = mydb_queryarray("".
		" SELECT user_id ".
		" FROM user ".
		" WHERE user.user_id = '".$GLOBALS['user_id']."' ".
		" AND user.password_hash = '".$password_hash."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; color: #b52121; ">Ошибка! Указан неверный текущий пароль.</h1>';

		return $out.PHP_EOL;
	}
	
	//print 'z4';
	
	if ($param['newpass1'] != $param['newpass2']) {
		$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; color: #b52121; ">Ошибка! Не совпадают указанные новые пароли.</h1>';

		return $out.PHP_EOL;
	}
	
	//print 'z5';
	
	if (!is_valid_password($param['newpass1'])) {
		$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; color: #b52121; ">Ошибка! Указанный пароль слишком короткий или слишком длинный.</h1>';

		return $out.PHP_EOL;
	}
	
	print 'z6';
	
	// изменяем
	
	$password_hash = hash('sha256', 'SaLt'.$param['newpass1']);
	
	// prepared query
	$a = array();
	$a[] = $password_hash;
	$a[] = $param['newpass1'];
	$a[] = $GLOBALS['user_id'];
	$q = "".
		"UPDATE user ".
		"SET user.password_hash = ?, ".
		" user.password = ? ".
		"WHERE user.user_id = ? ".
		";";
	$t = 'ssi';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	print 'z7';
	
	$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; color: #378a37; ">Пароль изменен успешно.</h1>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_passwordchange($param) {
	
	$GLOBALS['pagetitle'] = 'Смена пароля / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$changed = false;
		
			if (isset($param['action'])) {
				if ($param['action'] == 'changepass') {
					$r = outhtml_personal_passwordchange_action($param);
					$out .= $r;
					if (mb_strpos($r, 'успешно')) {
						$changed = true;
					}
				}
			}
			
			if (!$changed) {
				
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Смена пароля</h1>';
				
				$out .= '<div style=" padding-left: 20px; padding-bottom: 30px; " >';
				
				//
				
				$out .= '<form method="POST" action="/index.php">';
				
					$out .= '<input type="hidden" name="m" value="p" />';
					$out .= '<input type="hidden" name="sm" value="p" />';
				
					$out .= '<table><tr><td style=" vertical-align: top; font-size: 9pt; padding: 0px 10px 0px 0px; text-align: right; ">';
				
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #808080; ">текущий пароль:</div>';
					
					$out .= '</td><td style="  padding: 0px 0px 0px 0px;  " >';
					
					$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="curpass" type="password" value="" /></div>';
					
					$out .= '</td></tr><tr><td style=" padding: 0px 10px 0px 0px; text-align: right;   " >';
					
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #808080; ">новый пароль:</div>';
					
					$out .= '</td><td style="  padding: 0px 0px 0px 0px;  " >';
					
					$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" type="password" name="newpass1" value="" /></div>';
					
					$out .= '</td></tr><tr><td style="  padding: 0px 10px 0px 0px; text-align: right;  " >';
					
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #808080; ">новый пароль еще раз:</div>';
					
					$out .= '</td><td style="  padding: 0px 0px 0px 0px; " >';
					
					$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" type="password" name="newpass2" value="" /></div>';
					
					$out .= '</td></tr><tr><td></td><td  style=" padding: 0px 0px 0px 0px; " >';
					
					$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="action" value="changepass" style="background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; ">Сменить</button></div>';
					
					$out .= '</td></tr></table>';
					
				$out .= '</form>';
				
				//
				
				$out .= '</div>';
			
			}

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_personal_profile($param) {
	
	$GLOBALS['pagetitle'] = 'Мой профиль / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; ">';
			
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Мой профиль</h1>';
				
				$qr = mydb_queryarray("".
					" SELECT user.user_id, ".
					" user.firstname, user.lastname, user.city, ".
					" user.phone_number, user.phone_verified ".
					" FROM user ".
					" WHERE user.user_id = '".$GLOBALS['user_id']."' ".
					"");
				if ($qr === false) {
					my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
				if (sizeof($qr) != 1) {
					my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
				
				$out .= '<h1 class="grayemb" style=" margin-bottom: 20px; ">';
					$out .= 'Пароль';
				$out .= '</h1>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify;  ">';
					$out .= 'Пароль установлен.';
				$out .= '</p>';
				
				$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; " onclick=" window.location.href = \'/index.php?m=p&sm=p\'; ">Сменить пароль</button>';
				
				
				$out .= '<h1 class="grayemb" style=" margin-bottom: 20px; ">';
					$out .= 'Мои данные';
				$out .= '</h1>';
				
				//
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 120px; margin: 0px; ">';
					$out .= 'Фамилия';
				$out .= '</p>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 250px; margin: 0px; color: black; ">';
					$out .= htmlspecialchars($qr[0]['lastname'], ENT_QUOTES);
				$out .= '</p>';
				
				$out .= '<div style=" clear: both; height: 10px;  "></div>';
				
				//
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 120px; margin: 0px; ">';
					$out .= 'Имя';
				$out .= '</p>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 250px; margin: 0px; color: black; ">';
					$out .= htmlspecialchars($qr[0]['firstname'], ENT_QUOTES);
				$out .= '</p>';
				
				$out .= '<div style=" clear: both; height: 10px;  "></div>';
				
				//
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 120px; margin: 0px; ">';
					$out .= 'Город';
				$out .= '</p>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 250px; margin: 0px; color: black; ">';
					$out .= htmlspecialchars($qr[0]['city'], ENT_QUOTES);
				$out .= '</p>';
				
				$out .= '<div style=" clear: both; height: 10px;  "></div>';
				
				//
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 120px; margin: 0px; ">';
					$out .= 'Номер телефона';
				$out .= '</p>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left;  margin: 0px; color: black; ">';
					$out .= htmlspecialchars($qr[0]['phone_number'], ENT_QUOTES);
				$out .= '</p>';
				
				if ($qr[0]['phone_verified'] != 'Y') {
					$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 200px; margin-left: 20px; color: red; margin-top: 0px; ">';
						$out .= 'не подтвержден';
					$out .= '</p>';
				}
				
				$out .= '<div style=" clear: both; height: 20px;  "></div>';
				
				//
								
				$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; " onclick=" window.location.href = \'/personal/name_modify.php\'; ">Изменить мои данные</button>';
				
				if ($qr[0]['phone_verified'] != 'Y') {
				
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/personal/phone_verify.php\'; ">Подтвердить номер телефона</button>';
					
				}
				
				$out .= '<div style=" clear: both; "></div>';
				
				/*
				$out .= '<p class="grayeleg" style=" text-align: justify;  ">';
					$out .= 'user.
						firstname 	varchar(120);
						lastname 	varchar(120);
						phone_number 	varchar(30);
						phone_check 	decimal(6,0);
						phone_verified 	enum(N, Y);';
				$out .= '</p>';
				*/
				

			$out .= '</div>';
		
		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_messages($param) {
	
	$GLOBALS['pagetitle'] = 'Сообщения / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Сообщения</h1>';

		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_submenu(&$param) {

	$out = '';

	$list = array();
	$list[] = array('code' => 'c', 'text' => 'Моя коллекция');
	$list[] = array('code' => 'ii', 'text' => 'Знаки в загрузке');
	$list[] = array('code' => 'iw', 'text' => 'Знаки на модерации');
	$list[] = array('code' => 'r', 'text' => 'Мой профиль');
	// $list[] = array('code' => 'p', 'text' => 'Пароль и настройки');
	// $list[] = array('code' => 'r', 'text' => 'Мои реквизиты');
	// $list[] = array('code' => 'o', 'text' => 'Мои операции');
	// $list[] = array('code' => 'm', 'text' => 'Сообщения');
	$defaultcode = 'c';
	
	foreach ($list as &$e) {
		$e['current'] = false;
		$e['href'] = '/index.php?m=p&sm='.$e['code'];
	}
	
	//
	
	if (!isset($param['sm'])) $param['sm'] = $defaultcode;
	$sm_found = false;
	foreach ($list as &$e) {
		if ($param['sm'] == $e['code']) $sm_found = true;
	}
	if (!$sm_found && ($param['sm'] != 'p')) $param['sm'] = $defaultcode;
	foreach ($list as &$e) {
		if ($param['sm'] == $e['code']) $e['current'] = true;
	}
	
	//
	
	$GLOBALS['submenu_html'] = outhtml_submenu($list);
	// $out .= outhtml_submenu($list);
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_index($param) {

	if (!$GLOBALS['is_registered_user']) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}

	$GLOBALS['pagetitle'] = 'Личный кабинет / '.$GLOBALS['pagetitle'];
	
	$out = '';

	$out .= outhtml_personal_submenu(&$param);
	
	if ($param['sm'] == 'c') $out .= outhtml_personal_collection($param);
	if ($param['sm'] == 'p') $out .= outhtml_personal_passwordchange($param);
	if ($param['sm'] == 'm') $out .= outhtml_personal_messages($param);
	
	if ($param['sm'] == 'r') $out .= outhtml_personal_profile($param);
	
	if ($param['sm'] == 'ii') $out .= outhtml_personal_item_list_input($param);
	if ($param['sm'] == 'iw') $out .= outhtml_personal_item_list_moderated($param);
	

	return $out.PHP_EOL;
}

?>