<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_search_result.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/personal/index.php');


// =============================================================================
function outhtml_script_personal_name_modify() {

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
function outhtml_personal_name_modify_save(&$param) {

	if (isset($param['firstname'])) {
		$param['firstname'] = trim($param['firstname']);
		
		// prepared query
		$a = array();
		$a[] = $param['firstname'];
		$a[] = $GLOBALS['user_id'];
		$q = "".
			"UPDATE user ".
			"SET user.firstname = ? ".
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
	
	if (isset($param['lastname'])) {
		$param['lastname'] = trim($param['lastname']);
		
		// prepared query
		$a = array();
		$a[] = $param['lastname'];
		$a[] = $GLOBALS['user_id'];
		$q = "".
			"UPDATE user ".
			"SET user.lastname = ? ".
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
	
	
	if (isset($param['city'])) {
		$param['city'] = trim($param['city']);
		
		// prepared query
		$a = array();
		$a[] = $param['city'];
		$a[] = $GLOBALS['user_id'];
		$q = "".
			"UPDATE user ".
			"SET user.city = ? ".
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
	
	if (isset($param['phone'])) {
		$param['phone'] = trim($param['phone']);
		
		// prepared query
		$a = array();
		$a[] = $param['phone'];
		$a[] = $GLOBALS['user_id'];
		$q = "".
			"UPDATE user ".
			"SET user.phone_number = ?, ".
			" user.phone_verified = 'N' ".
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

}


// =============================================================================
function outhtml_personal_name_modify_form(&$param) {
	
	$GLOBALS['pagetitle'] = 'Мои данные / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	if ($param['action'] == 'save') {
		$out .= outhtml_personal_name_modify_save($param);
		$out .= outhtml_personal_profile($param);
		return $out;
	}
	
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
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; ">';
			
				$out .= '<form method="POST" action="/personal/name_modify.php">';
				
				// $out .= '<input type="hidden" name="action" value="save" />';
			
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Мои данные</h1>';
						
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 180px; margin-top: 2px; ">';
					$out .= 'Фамилия';
				$out .= '</p>';
				
				$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 200px; float: left; margin-left: 20px; " size="40" name="lastname" value="'.htmlspecialchars($qr[0]['lastname'], ENT_QUOTES).'" />';
				
				$out .= '<div style=" clear: both; "></div>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 180px; margin-top: 2px; ">';
					$out .= 'Имя';
				$out .= '</p>';
				
				$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 200px; float: left; margin-left: 20px; " size="40" name="firstname" value="'.htmlspecialchars($qr[0]['firstname'], ENT_QUOTES).'" />';
				
				$out .= '<div style=" clear: both; "></div>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 180px; margin-top: 2px; ">';
					$out .= 'Город';
				$out .= '</p>';
				
				$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 200px; float: left; margin-left: 20px; " size="40" name="city" value="'.htmlspecialchars($qr[0]['city'], ENT_QUOTES).'" />';
				
				$out .= '<div style=" clear: both; "></div>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 180px; margin-top: 2px; ">';
					$out .= 'Номер мобильного телефона';
				$out .= '</p>';
				
				$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 200px; float: left; margin-left: 20px; " onkeyup="js_phonetest();" onkeydown="js_phonetest();" onchange="js_phonetest();" size="40" name="phone" id="phoneinput" value="'.htmlspecialchars($qr[0]['phone_number'], ENT_QUOTES).'" />';
				
				$out .= '<div style=" clear: both; height: 20px; "></div>';
				
				$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; " type="submit" name="action" value="save" >Сохранить</button>';
				
				$out .= '</form>';
				
				$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/index.php?m=p&sm=r\'; ">Отмена</button>';

				$out .= '<div style=" clear: both; height: 10px; "></div>';
				

			$out .= '</div>';
		
		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_personal_name_modify($param) {

	if (!am_i_emailverified_user()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}

	$GLOBALS['pagetitle'] = 'Личный кабинет / '.$GLOBALS['pagetitle'];
	
	$out = '';

	$param['sm'] = 'r';
	if ($GLOBALS['is_registered_user']) {
		$out .= outhtml_personal_submenu($param);
	}

	$out .= outhtml_script_personal_name_modify();
	
	$out .= outhtml_personal_name_modify_form($param);
	
	return $out.PHP_EOL;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>