<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_search_result.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/sms4b/CSms4bBase.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/smsc/smsc_api.php');


// =============================================================================
function outhtml_script_personal_phone_verify() {

$str = <<<SCRIPTSTRING


function js_phonetest() {
	var elem = document.getElementById('phoneinput');
	if (!elem) return false;
	
	var length = elem.value.length;
	var str = elem.value;
	
	if (length < 1) return false;
	
	var res = '';
	var resstrict = '';
	// var numbers = '_0123456789';
	var allowed = '_ +-0123456789()';
	var allowedstrict = '_+0123456789';
	var arr = str.split('');
	var l = arr.length;
	var passedplus = false;
	for (var i=0; i < l; i++) {
		if (arr[i] == '+') {
			if (passedplus) {
				arr[i] = '';
			} else {
				passedplus = true;
			}
			if (resstrict != '') arr[i] = '';
		}
		if (String(allowed).indexOf(arr[i]) > 0) res += arr[i];
		if (String(allowedstrict).indexOf(arr[i]) > 0) resstrict += arr[i];
	}
	
	elem.value = res;
	
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_personal_submenu(&$param) {

	$out = '';

	$list = array();
	$list[] = array('code' => 'c', 'text' => 'Моя коллекция');
	$list[] = array('code' => 'ii', 'text' => 'Знаки в загрузке');
	$list[] = array('code' => 'iw', 'text' => 'Знаки на модерации');
	$list[] = array('code' => 'r', 'text' => 'Мой профиль');
	$list[] = array('code' => 'p', 'text' => 'Пароль и настройки');
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
	if (!$sm_found) $param['sm'] = $defaultcode;
	foreach ($list as &$e) {
		if ($param['sm'] == $e['code']) $e['current'] = true;
	}
	
	//
	
	$GLOBALS['submenu_html'] = outhtml_submenu($list);
	// $out .= outhtml_submenu($list);
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_phone_verify_step2(&$param) {
	
	$GLOBALS['pagetitle'] = 'Подтверждение номера телефона / '.$GLOBALS['pagetitle'];
	
	$out = '';
		
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.phone_number, user.phone_verified, user.phone_check ".
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
	
	//
	
	$code = str_pad(''.rand(0, 999999), 6, '0', STR_PAD_LEFT);
	
	// print ' '.$code;
	
	// prepared query
	$a = array();
	$a[] = $code;
	$a[] = $GLOBALS['user_id'];
	$q = "".
		"UPDATE user ".
		"SET user.phone_check = ? ".
		"WHERE user.user_id = ? ".
		";";
	$t = 'si';
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	//
	
	$message = 'Код для webcollect.ru '.$code.' Введите его на сайте.';

	$filteredNumber = preg_replace('/[^0-9]/', '', $qr[0]['phone_number']);
	
	// var_dump($filteredNumber);
	list($sms_id, $sms_cnt, $cost, $balance) = send_sms($filteredNumber, $message, 0);
	

	if ($sms_cnt > 0) {
		// $out .= "Сообщение отправлено успешно. ID: ".$sms_id.", всего SMS: ".$sms_cnt.", стоимость: ".$cost.", баланс: ".$balance.".\n";
		// $out .= "Сообщение отправлено успешно. ID: ".$sms_id."";
		$error_message = '';
	} else  {
		$error_message = "Ошибка №".(-$sms_cnt)." ".($sms_id ? ", ID: ".$sms_id : "")." отправки на номер ".$filteredNumber;
	}
	
	// myemail_send_phone_check(array('code' => $code, 'phone' => $qr[0]['phone_number']));
	
	/*
	
	$SMS4B = new Csms4bBase();
	
	if (!is_object($SMS4B)) {
		my_print_error("Ошибка 0! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ( $SMS4B->LastError != '') {
		my_print_error("Ошибка f! (".__FILE__." Line ".__LINE__.")");
		my_print_error("Ошибка f! (".$SMS4B->LastError.")");
		return false;
	}
	
	if ( $SMS4B->GetSOAP("AccountParams",array("SessionID" => $SMS4B->GetSID())) !== true) {
		my_print_error("Ошибка d! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (true) {
		
		$destination = $SMS4B->parse_numbers($qr[0]['phone_number']);
		
		if (count($destination) == 0) {
			my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// $sender = stripslashes('webcollect.ru');
		
		$result = $SMS4B->SendSms($message, $destination, '');

		if ($result) {
			print 'Ok';
		} else {
			$error = $SMS4B->LastError;
			my_print_error("Ошибка! (".$error.")");
			my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
	
	}
	
	*/
	
	//
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; ">';
			
				$out .= '<form method="POST" action="/personal/phone_verify.php">';
				
				// $out .= '<input type="hidden" name="action" value="save" />';
			
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Подтверждение номера телефона</h1>';

				if ($error_message !== '') {
					$out .= '<p class="grayeleg" style=" text-align: justify;  width: 400px; margin-top: 2px; color: red; ">';
						$out .= $error_message;
					$out .= '</p>';
				}
				
				$out .= '<p class="grayeleg" style=" text-align: left; width: 400px; margin-top: 2px; ">';
					$out .= 'Номер мобильного телефона';
				$out .= '</p>';
				
				$out .= '<p class="grayeleg" style=" text-align: left;  width: 400px; margin-top: 2px; color: black; ">';
					$out .= htmlspecialchars($qr[0]['phone_number'], ENT_QUOTES);
					$out .= ' <span style=" color: blue; ">('.$filteredNumber.')</span>';
				$out .= '</p>';

				
				$out .= '<div style=" clear: both; height: 20px; "></div>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 180px; margin-top: 2px; ">';
					$out .= 'Код (6 цифр)';
				$out .= '</p>';
				
				$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 200px; float: left; margin-left: 20px; " size="7" name="code" value="" />';

				
				$out .= '<div style=" clear: both; height: 20px; "></div>';
				
				/*
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 500px; margin-top: 2px; ">';
					$out .= 'На ваш номер будет отправлено сообщение SMS с кодом.';
				$out .= '</p>';
				*/
				
				$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; " type="submit" name="step" value="3" >Подтвердить</button>';
				
				$out .= '</form>';
				
				$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/index.php?m=p&sm=r\'; ">Отмена</button>';

				$out .= '<div style=" clear: both; height: 10px; "></div>';
				

			$out .= '</div>';
		
		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}




// =============================================================================
function outhtml_personal_phone_verify_step3(&$param) {
	
	$GLOBALS['pagetitle'] = 'Подтверждение номера телефона / '.$GLOBALS['pagetitle'];
	
	$out = '';
		
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.phone_number, user.phone_verified, user.phone_check ".
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
	
	//
	
	$success = false;

	if ($param['code'] == $qr[0]['phone_check']) {
	
		// prepared query
		$a = array();
		$a[] = 'Y';
		$a[] = $GLOBALS['user_id'];
		$q = "".
			"UPDATE user ".
			"SET user.phone_verified = ? ".
			"WHERE user.user_id = ? ".
			";";
		$t = 'si';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
		
		$success = true;
		
	} else {
	
		// prepared query
		$a = array();
		$a[] = '';
		$a[] = $GLOBALS['user_id'];
		$q = "".
			"UPDATE user ".
			"SET user.phone_check = ? ".
			"WHERE user.user_id = ? ".
			";";
		$t = 'si';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
	
	}
	
	//
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; ">';
			
				$out .= '<form method="POST" action="/personal/phone_verify.php">';
				
				// $out .= '<input type="hidden" name="action" value="save" />';
			
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Подтверждение номера телефона</h1>';
				
				
				if ($success) {
				
					$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 180px; margin-top: 2px; color: #6cb51e; ">';
						$out .= 'Успешно';
					$out .= '</p>';
				
				} else {
				
					$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 180px; margin-top: 2px; color: #a23232; ">';
						$out .= 'Неудачно';
					$out .= '</p>';
				
				}
				
				
				$out .= '<div style=" clear: both; height: 20px; "></div>';
				
				/*
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 500px; margin-top: 2px; ">';
					$out .= 'На ваш номер будет отправлено сообщение SMS с кодом.';
				$out .= '</p>';
				*/
				
				if ($success) {
				
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/index.php?m=p&sm=r\'; ">ОК</button>';
				
					$out .= '</form>';
				
				} else {
				
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; " type="submit" name="step" value="1" >Повторить</button>';
				
					$out .= '</form>';
					
				}
				
				$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/index.php?m=p&sm=r\'; ">Отмена</button>';

				$out .= '<div style=" clear: both; height: 10px; "></div>';
				

			$out .= '</div>';
		
		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_personal_phone_verify_form(&$param) {
	
	$GLOBALS['pagetitle'] = 'Подтверждение номера телефона / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	// if ($param['action'] == 'save') $out .= outhtml_personal_phone_verify_save($param);
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, ".
		" user.phone_number, user.phone_verified, user.phone_check ".
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
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; ">';
			
				$out .= '<form method="POST" action="/personal/phone_verify.php">';
				
				// $out .= '<input type="hidden" name="action" value="save" />';
			
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; margin-top: 10px; ">Подтверждение номера телефона</h1>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify;  width: 400px; margin-top: 2px; ">';
					$out .= 'Номер мобильного телефона ';
					$out .= '<span style=" color: black; ">'.htmlspecialchars($qr[0]['phone_number'], ENT_QUOTES).'</span>';
				$out .= '</p>';

				$out .= '<div style=" clear: both; height: 20px; "></div>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify;  width: 500px; margin-top: 2px; ">';
					$out .= 'На ваш номер будет отправлено сообщение SMS с кодом.';
				$out .= '</p>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; width: 500px; margin-top: 2px; ">';
					$out .= 'Мы не занимаемся рассылкой рекламных сообщений и не передаем ваш номер третьим лицам.';
				$out .= '</p>';

				$out .= '<p class="grayeleg" style=" clear: both; ">';
				
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; " type="submit" name="step" value="2" >Подтвердить</button>';

					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/index.php?m=p&sm=r\'; ">Отмена</button>';
					
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/personal/name_modify.php\'; ">Изменить номер</button>';

				$out .= '</p>';
				
				$out .= '</form>';

				

				$out .= '<div style=" clear: both; height: 10px; "></div>';
				

			$out .= '</div>';
		
		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_personal_phone_verify($param) {

	if (!am_i_emailverified_user()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}

	$GLOBALS['pagetitle'] = 'Личный кабинет / '.$GLOBALS['pagetitle'];
	
	$out = '';

	$param['sm'] = 'r';
	
	if (!isset($param['step'])) $param['step'] = '1';
	if (!ctype_digit($param['step'])) $param['step'] = '1';
	$param['step'] = ''.intval($param['step']);
	
	if ($GLOBALS['is_registered_user']) {
		$out .= outhtml_personal_submenu($param);
	}
	
	$out .= outhtml_script_personal_phone_verify();

	switch ($param['step']) {

		case '1': $out .= outhtml_personal_phone_verify_form($param);
		break;

		case '2': $out .= outhtml_personal_phone_verify_step2($param);
		break;
		
		case '3': $out .= outhtml_personal_phone_verify_step3($param);
		break;
		
		default: $out .= outhtml_personal_phone_verify_form($param);
	}
	
	return $out.PHP_EOL;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>