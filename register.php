<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/smsc/smsc_api.php');



// =============================================================================
function outhtml_script_register() {

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
function is_captcha_ok($param) {
	
	include_once $_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php';
	
	$securimage = new Securimage();

	if ($securimage->check($param['captcha_code']) == false) return false;
	
	return true;
}


// =============================================================================
function outhtml_register_form($param) {

	$GLOBALS['pagetitle'] = 'Регистрация. Шаг 1 / '.$GLOBALS['pagetitle'];
	
	$retry = (sizeof($param['formfail']) > 0);
	
	$out = '';

	my_write_log('Регистрация Шаг 1');
	
	$out .= outhtml_script_register();
	
	$rqrd = '<sup style=" color: #800000; font-size: 12px; ">*</sup>';

	$out .= '<div style=" margin-left: 140px; " >';
	
		$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; ">Регистрация, шаг 1</h1>';
		
		
		if ($retry) {
			
			if (isset($param['formfail']['problem'])) {
				
				// техническая проблем
				$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; ">';
					$out .= '<h1 style=" font-size: 13pt; margin-top: 20px; margin-bottom: 20px; color: #ff3030; ">'.$param['formfail']['problem'].'</h1>';
				$out .= '</div>';
				
			} else {
			
				// проблема заполнения
				$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; ">';
					$out .= '<h1 style=" font-size: 13pt; margin-top: 20px; margin-bottom: 20px; color: #ff3030; ">Обнаружены ошибки при заполнении формы регистрации</h1>';
				$out .= '</div>';
				
			}
		}
		
		$link = $_SERVER['PHP_SELF'];
		$out .= '<form method="POST" action="'.$link.'">';
		
		// слева
		
		// width: 400px;
		
		$out .= '<div style=" float: left; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
		
				//
				
				$out .= '<div style=" margin-top: 9px; padding-bottom: 14px; padding-left: 4px; font-size: 10pt; color: #888888; ">Как к вам обращаться</div>';

				$out .= '<div style=" margin-top: 9px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">имя'.$rqrd.':</div>';
				
				if (isset($param['formfail']['firstname'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['firstname'].'</div>';
				}

				$v = ($retry ? $param['firstname'] : '');
		
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="firstname" value="'.$v.'" /></div>';
		
				//
		
				$out .= '<div style=" margin-top: 5px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">отчество:</div>';
				
				if (isset($param['formfail']['middlename'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['middlename'].'</div>';
				}

				$v = ($retry ? $param['middlename'] : '');
		
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="middlename" value="'.$v.'" /></div>';
		
				//
		
				$out .= '<div style=" margin-top: 5px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">фамилия'.$rqrd.':</div>';
				
				if (isset($param['formfail']['lastname'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['lastname'].'</div>';
				}

				$v = ($retry ? $param['lastname'] : '');
		
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="lastname" value="'.$v.'" /></div>';
		
				//
		
				$out .= '<div style=" margin-top: 45px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">город'.':</div>';
				// $rqrd.
				
				if (isset($param['formfail']['city'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['city'].'</div>';
				}

				$v = ($retry ? $param['city'] : '');
		
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="city" value="'.$v.'" /></div>';
		
		
				//
		
				$out .= '<div style=" margin-top: 25px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">e-mail'.$rqrd.':</div>';
				
				if (isset($param['formfail']['email'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['email'].'</div>';
				}

				$v = ($retry ? $param['email'] : '');
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="email" value="'.$v.'" /></div>';
				
				//
				
				/*
		
				$out .= '<div style=" margin-top: 25px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">мобильный телефон'.$rqrd.':</div>';
				
				if (isset($param['formfail']['phone'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['phone'].'</div>';
				}

				$v = ($retry ? $param['phone'] : '');
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" onkeyup="js_phonetest();" onkeydown="js_phonetest();" onchange="js_phonetest();" size="40" id="phoneinput" name="phone" value="'.$v.'" /></div>';
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">включая код страны, +7 для РФ'.'</div>';
				

				
				//
		
				/*
				$out .= '<div style=" margin-top: 20px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">псевдоним:</div>';
				
				if (isset($param['formfail']['username'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['username'].'</div>';
				}

				$v = ($retry ? $param['username'] : '');
		
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="username" value="'.$v.'" /></div>';
				*/
				
			$out .= '</div>';
			
			// справа
				
			// width: 300px;
			$out .= '<div style=" float: left; margin-left: 50px; width: 300px; padding: 0px 20px 10px 0px; color: #888888; line-height: 125%; ">';
			
					//
		
				$out .= '<div style=" margin-top: 5px; padding-bottom: 1px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #888888; ">пароль'.$rqrd.':</div>';
				
				if (isset($param['formfail']['password'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['password'].'</div>';
				}
		
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 1px; font-size: 8pt; color: #888888; ">(длина не менее 5 символов)</div>';
		
				//
		
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 267px; " size="20" type="password" name="password" value="" /></div>';
		
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #888888; ">пароль еще раз'.$rqrd.':</div>';
				
				if (isset($param['formfail']['password2'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['password2'].'</div>';
				}

				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 267px; " size="20" type="password" name="password2" value="" /></div>';
				
				//
		

				//
				
				$out .= '<div style=" float: left; width: 251px; margin-top: 23px; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; border: solid 1px #b0b0b0; background-color: #ffffff; ">';
			
					include_once $_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php';
					$securimage = new Securimage();
					//$securimage -> code_length = 4;
					//$securimage -> image_signature = 'webcollect.ru';
					//$securimage -> signature_color = new Securimage_Color('#000000');
					
					$out .= '<img id="captcha" src="/xhr/captcha_image.php" style= " margin-top: 10px; margin-bottom: 10px; display: block; float: left; border-radius: 3px; -moz-border-radius: 3px; " title="Securimage Captcha Script. Copyright &copy; 2011 Drew Phillips" />';
				
					// $out .= '<img id="captcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" style= " margin-top: 10px; margin-bottom: 10px; display: block; float: left; border-radius: 3px; -moz-border-radius: 3px; " title="Securimage Captcha Script. Copyright &copy; 2011 Drew Phillips" />';
				
					$out .= '<a href="#" title="показать другой код" onclick=" document.getElementById(\'captcha\').src = \'/xhr/captcha_image.php?\' + Math.random(); return false" style=" display: block; width: 16px; height: 16px; float: left; margin: 10px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/arrow_refresh.png\'); " ></a>';
				
					$out .= '<div style=" clear: both; "></div>';
			
				$out .= '</div>';
		
				$out .= '<div style=" clear: both; "></div>';
			
				$out .= '<div style=" margin-top: 25px; padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #888888; ">код с картинки'.$rqrd.':</div>';
				
				if (isset($param['formfail']['captcha_code'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['captcha_code'].'</div>';
				}
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 267px; " size="20" type="text" size="10" maxlength="6" name="captcha_code" value="" /></div>';
				
				//
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 18px; font-size: 10pt; color: #888888; ">';
		
					$out .= '<div style=" float: left; ">';
						$out .= '<input class="hoverwhiteborder" style="  display: block; text-align: left; font-size: 12px; background-color: #dadada; padding: 4px 4px 4px 0px; border-radius: 3px; -moz-border-radius: 3px; " type="checkbox" name="agree" />';
					$out .= '</div>';
				
					$out .= '<div style=" float: left; width: 200px; margin-left: 10px; ">';
					
						$out .= 'Я согласен с условиями'.$rqrd.' ';
						$hrefagreement = '/agreement.php';
						$out .= '<a href="'.$hrefagreement.'" target="_new">';
						$out .= 'Соглашения об использовании';
						$out .= '</a>';
						
					$out .= '</div>';
					
					if (isset($param['formfail']['agree'])) {
						$out .= '<div style=" clear: left; "></div>';
						$out .= '<div style=" float: left; width: 200px; margin-left: 26px; margin-top: 4px; ">';
							$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['agree'].'</div>';
						$out .= '</div>';
					}
					
					$out .= '<div style=" clear: left; "></div>';
				
				$out .= '</div>';
				
				//
			
				$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="a" value="r" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 276px; ">Зарегистрируйте меня</button></div>';
		
			$out .= '</form>';
		
			
		
		$out .= '</div>';
		
		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '<div style=" width: 600px; padding-bottom: 4px; padding-left: 4px; padding-top: 38px; font-size: 10pt; color: #888888; ">';
			$out .= ''.$rqrd.' Обязательно для заполнения';
		$out .= '</div>';

		// $out .= '<div style=" clear: both; "></div>';
		
		$out .= '<div style=" width: 600px; padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #888888; ">';
			$out .= 'Вы получите сообщение с адреса noreply@'.my_get_http_domain().'. Перейдите по ссылке, укзанной в этом сообщении для завершения регистрации.';
		$out .= '</div>';
	
	$out .= '</div>';
			
	$out .= '<div style=" clear: both; min-height: 100px; "></div>';

	return $out;
}


// =============================================================================
function is_username_correct($str) {

	$x = $str;
	mb_regex_encoding('UTF-8');
	$x = mb_ereg_replace('[^А-ЯA-Zа-яa-z1-90 ]', '', $x);
	$x = str_replace('    ', ' ', $x);
	$x = str_replace('  ', ' ', $x);
	$x = trim($x);
	return ($x == $str);
}



// =============================================================================
function is_name_correct($str) {

	$x = $str;
	mb_regex_encoding('UTF-8');
	$x = mb_ereg_replace('[^-А-ЯA-Zа-яa-z1-90 ]', '', $x);
	$x = str_replace('    ', ' ', $x);
	$x = str_replace('  ', ' ', $x);
	$x = trim($x);
	return ($x == $str);
}


// =============================================================================
function is_password_correct($str) {
	return true;
}


// =============================================================================
function outhtml_register_checkform(&$param) {
	
	$out = '';
	
	$param['formfail'] = array();
	
	//
	
	$param['lastname'] = trim($param['lastname']);
	
	// lastname correct
	if (!isset($param['lastname'])) {
		$param['formfail']['lastname'] = 'Укажите имя';
		out_silent_error_userinput('Укажите имя');
	} else {
		if (mb_strlen($param['lastname']) < 1) {
			$param['formfail']['lastname'] = 'Укажите имя длиной от 1 символов';
			out_silent_error_userinput('Укажите имя длиной от 1 символов');
		} else {
			if (!is_name_correct($param['lastname'])) {
				$param['formfail']['lastname'] = 'Укажите корректное имя';
				out_silent_error_userinput('Не указан корректное имя');
			}
		}
	}
	
	//
	
	$param['firstname'] = trim($param['firstname']);
	
	// firstname correct
	if (!isset($param['firstname'])) {
		$param['formfail']['firstname'] = 'Укажите имя';
		out_silent_error_userinput('Укажите имя');
	} else {
		if (mb_strlen($param['firstname']) < 1) {
			$param['formfail']['firstname'] = 'Укажите имя длиной от 1 символов';
			out_silent_error_userinput('Укажите имя длиной от 1 символов');
		} else {
			if (!is_name_correct($param['firstname'])) {
				$param['formfail']['firstname'] = 'Укажите корректное имя';
				out_silent_error_userinput('Не указан корректное имя');
			}
		}
	}
	
	
	//
	
	$param['middlename'] = trim($param['middlename']);
	
	// middlename correct
	/*
	if (!isset($param['middlename'])) {
		$param['formfail']['middlename'] = 'Укажите имя';
		out_silent_error_userinput('Укажите имя');
	} else {
		if (mb_strlen($param['middlename']) < 1) {
			$param['formfail']['middlename'] = 'Укажите имя длиной от 1 символов';
			out_silent_error_userinput('Укажите имя длиной от 1 символов');
		} else {
			if (!is_name_correct($param['middlename'])) {
				$param['formfail']['middlename'] = 'Укажите корректное имя';
				out_silent_error_userinput('Не указан корректное имя');
			}
		}
	}
	*/
	
		
	//
	
	$param['city'] = trim($param['city']);
	
	// city correct
	/*
	if (!isset($param['city'])) {
		$param['formfail']['city'] = 'Укажите город';
		out_silent_error_userinput('Укажите город');
	} else {
		if (mb_strlen($param['city']) < 1) {
			$param['formfail']['city'] = 'Укажите город длиной от 1 символов';
			out_silent_error_userinput('Укажите город длиной от 1 символов');
		} else {
			if (!is_name_correct($param['city'])) {
				$param['formfail']['city'] = 'Укажите корректный город';
				out_silent_error_userinput('Не указан корректный город');
			}
		}
	}
	*/
		
	//
	
	$param['email'] = trim($param['email']);
	
	// email correct
	if (!isset($param['email'])) {
		$param['formfail']['email'] = 'Укажите адрес электронной почты';
		out_silent_error_userinput('Не указан адрес электронной почты');
	} else {
		if (mb_strlen($param['email']) < 3) {
			$param['formfail']['email'] = 'Укажите адрес электронной почты';
			out_silent_error_userinput('Не указан адрес электронной почты');
		} else {
			if (!validEmail($param['email'])) {
				$param['formfail']['email'] = 'Укажите корректный адрес электронной почты';
				out_silent_error_userinput('Не указан корректный адрес электронной почты');
			}
		}
	}
	
	// email exist
	if (!isset($param['formfail']['email'])) {
		if (is_email_exist_in_db($param['email'])) {
			$param['formfail']['email'] = 'Пользователь с таким адресом уже зарегистрирован';
			out_silent_error_userinput('Пользователь с таким адресом уже зарегистрирован');
		}
	}
	
	//
	
	$param['username'] = $param['firstname'].' '.$param['lastname'];
	
	/*
	$param['username'] = trim($param['username']);
	
	// username correct
	if (!isset($param['username'])) {
		$param['formfail']['username'] = 'Укажите псевдоним';
		out_silent_error_userinput('Укажите псевдоним');
	} else {
		if (mb_strlen($param['username']) < 3) {
			$param['formfail']['username'] = 'Укажите псевдоним длиной от 3 символов';
			out_silent_error_userinput('Укажите псевдоним длиной от 3 символов');
		} else {
			if (!is_username_correct($param['username'])) {
				$param['formfail']['username'] = 'Укажите корректный псевдоним';
				out_silent_error_userinput('Не указан корректный псевдоним');
			}
		}
	}
	
	// username exists
	if (!isset($param['formfail']['username'])) {
		if (is_username_exist_in_db($param['username'])) {
			$param['formfail']['username'] = 'Пользователь с таким псевдонимом уже зарегистрирован';
			out_silent_error_userinput('Пользователь с таким псевдонимом уже зарегистрирован');
		}
	}
	*/
	
	//
	
	// password
	if (!isset($param['password'])) {
		$param['formfail']['password'] = 'Укажите пароль';
		out_silent_error_userinput('Укажите пароль');
	} else {
		if (mb_strlen($param['password']) < 5) {
			$param['formfail']['password'] = 'Укажите пароль длиной от 5 символов';
			out_silent_error_userinput('Укажите пароль длиной от 5 символов');
		} else {
			if (mb_strlen($param['password']) > 80) {
				$param['formfail']['password'] = 'Укажите пароль длиной менее 80 символов';
				out_silent_error_userinput('Укажите пароль длиной менее 80 символов');
			} else {
				if (!is_password_correct($param['password'])) {
					$param['formfail']['password'] = 'Укажите корректный пароль';
					out_silent_error_userinput('Не указан корректный пароль');
				}
			}
		}
	}
	
	// password2
	if (!isset($param['password2'])) {
		$param['formfail']['password'] = 'Укажите пароль повторно';
		out_silent_error_userinput('Укажите пароль повторно');
	} else {
		if ($param['password2'] != $param['password']) {
			$param['formfail']['password2'] = 'Указанные пароли не совпадают';
			out_silent_error_userinput('Указанные пароли не совпадают');
		}
	}
	
	
	// agree
	if (!isset($param['agree'])) {
		$param['formfail']['agree'] = 'Мы регистрируем пользователей только при их согласии';
		out_silent_error_userinput('Мы регистрируем пользователей только при их согласии');
	} else {
		if ($param['agree'] != 'on') {
			$param['formfail']['agree'] = 'Мы регистрируем пользователей только при их согласии';
			out_silent_error_userinput('Мы регистрируем пользователей только при их согласии');
		}
	}
	
	// captcha
	if (!is_captcha_ok($param)) {
		$param['formfail']['captcha_code'] = 'Код каптча не совпал';
		out_silent_error_userinput('Код каптча не совпал');
	}
	
	return $out;
}


// =============================================================================
function add_new_user(&$param) {

	$password_hash = hash('sha256', 'SaLt'.$param['password']);
	
	$s = 'SALT'.intval(time()).$_SERVER['REMOTE_ADDR'].$param['password'];
	$emailcode = hash('sha256', $s);
	
	//
	
	if (!isset($param['middlename'])) $param['middlename'] = '';
	if (!isset($param['city'])) $param['city'] = '';
	if (!isset($param['phone'])) $param['phone'] = '';
	
	//
	
	// prepared query
	$a = array();
	$q = "".
		" INSERT INTO user ".
		" SET user.email_verified = 'N', ".
		" user.is_registered_user = 'N', ".
		" user.email_code = ?, ".
		" user.password_hash = ?, ".
		" user.ip_address = ?, ".
		" user.time_last_request = ?, ".
		" user.email_address = ?, ".
		" user.username = ?, ".
		" user.password = ?, ".
		" user.firstname = ?, ".
		" user.lastname = ?, ".
		" user.middlename = ?, ".
		" user.city = ?, ".
		" user.phone_number = ?, ".
		" user.phone_verified = 'N' ".
		";";
		
	// out_silent_error("q=(".$q.")");
		
	$a[] = $emailcode;
	$a[] = $password_hash;
	$a[] = $_SERVER['REMOTE_ADDR'];
	$a[] = date('Y-m-d H:i:s');
	$a[] = $param['email'];
	$a[] = $param['username'];
	$a[] = $param['password'];
	
	$a[] = $param['firstname'];
	$a[] = $param['lastname'];
	
	$a[] = $param['middlename'];
	$a[] = $param['city'];
	$a[] = $param['phone'];
	
	$t = 'ssssssssssss';
	$qres = mydb_prepquery($q, $t, $a);
	
	/*
	print_r($qres);
	return false;
	*/
	
	if ($qres === false) {
		die('add_new_user() fatal error');
	}
	
	$user_id = mydb_insert_id();
	if ($user_id < 1) {
		die('add_new_user() fatal error 2');
	}
	$param['user_id'] = $user_id;
	
	//
	
	my_write_log('Добавлен пользователь. Ожидание подтверждения.', 'N');
	
	$param['codestr'] = $emailcode;
	
	return true;
}


// =============================================================================
function outhtml_register_sendmail_form($param) {

	$out = '';
	
	$retry = (sizeof($param['formfail']) > 0);

	$out .= '<div style=" margin-left: 300px; " >';
	
		$out .= '<div style=" clear: both; height: 5px; "></div>';
	
		$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; color: #888888; ">Регистрация, шаг 2</h1>';
		
		if ($retry) {
			
			if (isset($param['formfail']['problem'])) {
				
				//  проблема
				$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; ">';
					$out .= '<h1 style=" font-size: 13pt; margin-top: 20px; margin-bottom: 20px; color: #ff3030; ">'.$param['formfail']['problem'].'</h1>';
				$out .= '</div>';
				
			} 
		}
		
		$link = $_SERVER['PHP_SELF'];
		$out .= '<form method="POST" action="'.$link.'">';
		
			$out .= '<div style=" float: left; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
			
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">На ваш адрес было отправлено письмо с кодом.</div>';
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Если вы не видите письма от нас, пожалуйста проверьте папку СПАМ.</div>';
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Перейдите по ссылке, указанной в письме</div>';
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">или</div>';
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Введите полученный код:</div>';
								
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 290px; " size="40" name="code" value="" /></div>';
				
				$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="a" value="v" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; width: 300px; ">ОК</button></div>';
						
			$out .= '</div>';
			
		$out .= '</form>';
		
	$out .= '</div>';
			
	$out .= '<div style=" clear: both; min-height: 100px; "></div>';

	return $out;
}



// =============================================================================
function outhtml_register_sendsms_form($param) {

	$out = '';
	
	$retry = (sizeof($param['formfail']) > 0);

	$out .= '<div style=" margin-left: 300px; " >';
	
		$out .= '<div style=" clear: both; height: 5px; "></div>';
	
		$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; color: #888888; ">Регистрация, шаг 3</h1>';
		
		if ($retry) {
			
			if (isset($param['formfail']['problem'])) {
				
				//  проблема
				$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; ">';
					$out .= '<h1 style=" font-size: 13pt; margin-top: 20px; margin-bottom: 20px; color: #ff3030; ">'.$param['formfail']['problem'].'</h1>';
				$out .= '</div>';
				
			} 
		}
		
		$link = $_SERVER['PHP_SELF'];
		$out .= '<form method="POST" action="'.$link.'">';
		
			$out .= '<div style=" float: left; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
			
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">На ваш телефон было отправлено сообщение с кодом.</div>';
				
				// $out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Если вы не видите сообщения от нас, пожалуйста проверьте папку СПАМ.</div>';
				
				// $out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Перейдите по ссылке, указанной в письме</div>';
				
				// $out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">или</div>';
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Введите полученный код (шесть цифр):</div>';
								
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 290px; " size="40" name="code" value="" /></div>';
				
				$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="a" value="p" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; width: 300px; ">ОК</button></div>';
						
			$out .= '</div>';
			
		$out .= '</form>';
		
	$out .= '</div>';
			
	$out .= '<div style=" clear: both; min-height: 100px; "></div>';

	return $out;
}


// =============================================================================
function outhtml_register_sendmail($param) {

	$out = '';
	
	$param['user_id'] = '0';
	
	//

	$result = outhtml_register_checkform(&$param);
	
	if (sizeof($param['formfail']) > 0) {
		// problems
		return outhtml_register_form($param);
	}
	
	//

	$result = add_new_user(&$param);
	if (!$result) {
		$param['formfail']['problem'] = 'Произошла ошибка при добавлении пользователя';
		return outhtml_register_form($param);
	}
	
	$mailparam['email'] = $param['email'];
	$mailparam['codestr'] = $param['codestr'];
	$result = myemail_send_registration($param);

	if (!result) {
		
		$qr = mydb_query("".
		" DELETE FROM user ".
		" WHERE user.user_id = '".$param['user_id']."'; ".
		"");
		if ($qr === false) {
		}
		
		$param['formfail']['problem'] = 'Произошла ошибка при отправке сообщения';
		return outhtml_register_form($param);
	}
	
	// 
	
	$GLOBALS['pagetitle'] = 'Регистрация. Шаг 2 / '.$GLOBALS['pagetitle'];

	my_write_log('Регистрация Шаг 2');
	
	//
	
	$out .= outhtml_register_sendmail_form($param);

	return $out.PHP_EOL;
}


// =============================================================================
function process_email_code_verification(&$param) {

	// prepared query
	$a = array();
	$q = "".
		" SELECT user.user_id ".
		" FROM user ".
		" WHERE ( user.email_verified = 'N' ) ".
		" AND ( user.is_registered_user = 'N' ) ".
		" AND ( user.email_code = ? ) ".
		"";
	$a[] = $param['code'];
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) {
		die('process_email_code_verification() fatal error 1');
	}
	
	// return (sizeof($qres) > 0);
	
	if (sizeof($qres) < 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Неверный код';
		return false;
	}
	
	if (sizeof($qres) > 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Техническая проблема сравнения кода';
		return false;
	}
	
	if ($qres[0]['user_id'] < 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Техническая проблема поиска кода';
		return false;
	}
	
	//
	
	$user_id = $qres[0]['user_id'];
	
	out_silent_error("new user_id=".$user_id);
	
	if ($user_id < 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Техническая проблема поиска кода #2';
		return false;
	}
	
	//
	
	$qr = mydb_query("".
		" UPDATE user ".
		" SET user.email_verified = 'Y', ".
		" user.time_last_request = '".date('Y-m-d H:i:s')."', ".
		" user.email_code = '' ".
		" WHERE user.user_id = '".$user_id."'; ".
		"");
	if ($qr === false) {
		die('process_email_code_verification() fatal error 2a');
	}
	
	//
	
	$GLOBALS['visitor_id'] = false;
	$GLOBALS['user_id'] = $user_id;
	$GLOBALS['is_registered_user'] = true;
	
	$s = 'SALT'.intval(time()).$_SERVER['REMOTE_ADDR'].$GLOBALS['user_id'];
	$hash = hash('sha256', $s);
	
	setcookie('ch', $hash, (time() + my_get_session_lifetime()), '/');

	$qr = mydb_query("".
		" UPDATE user ".
		" SET user.cookie_hash = '".$hash."', ".
		" user.ip_address = '".$_SERVER['REMOTE_ADDR']."' ".
		" WHERE user.user_id = '".$user_id."'; ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	process_set_user_registered($user_id);
	
	//
	
	return true;
}


// =============================================================================
function process_sms_code_verification(&$param) {

	// prepared query
	$a = array();
	$q = "".
		" SELECT user.user_id ".
		" FROM user ".
		" WHERE ( user.phone_verified = 'N' ) ".
		" AND ( user.is_registered_user = 'N' ) ".
		" AND ( user.phone_check = ? ) ".
		"";
	$a[] = $param['code'];
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) {
		die('process_sms_code_verification() fatal error 1');
	}
	
	// return (sizeof($qres) > 0);
	
	if (sizeof($qres) < 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Неверный код';
		return false;
	}
	
	if (sizeof($qres) > 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Техническая проблема сравнения кода';
		return false;
	}
	
	if ($qres[0]['user_id'] < 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Техническая проблема поиска кода';
		return false;
	}
	
	//
	
	$user_id = $qres[0]['user_id'];
	
	out_silent_error("new user_id=".$user_id);
	
	if ($user_id < 1) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Техническая проблема поиска кода #2';
		return false;
	}
	
	//
	
	$qr = mydb_query("".
		" UPDATE user ".
		" SET user.phone_verified = 'Y', ".
		" user.time_registered = '".date('Y-m-d H:i:s')."', ".
		" user.time_last_request = '".date('Y-m-d H:i:s')."', ".
		" user.is_registered_user = 'Y', ".
		" user.can_submit_item = 'Y' ".
		" WHERE user.user_id = '".$user_id."'; ".
		"");
	if ($qr === false) {
		die('process_email_code_verification() fatal error 2b');
	}
	
	//
	
	$GLOBALS['visitor_id'] = false;
	$GLOBALS['user_id'] = $user_id;
	$GLOBALS['is_registered_user'] = true;
	
	$s = 'SALT'.intval(time()).$_SERVER['REMOTE_ADDR'].$GLOBALS['user_id'];
	$hash = hash('sha256', $s);
	
	setcookie('ch', $hash, (time() + my_get_session_lifetime()), '/');

	$qr = mydb_query("".
		" UPDATE user ".
		" SET user.cookie_hash = '".$hash."', ".
		" user.ip_address = '".$_SERVER['REMOTE_ADDR']."' ".
		" WHERE user.user_id = '".$user_id."'; ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return true;
}


// =============================================================================
function process_set_user_registered($user_id) {

	$user_id = ''.intval($user_id);
	if ($user_id < 1) return false;

	$qr = mydb_query("".
		" UPDATE user ".
		" SET user.phone_verified = 'N', ".
		" user.time_registered = '".date('Y-m-d H:i:s')."', ".
		" user.time_last_request = '".date('Y-m-d H:i:s')."', ".
		" user.is_registered_user = 'Y', ".
		" user.can_submit_item = 'Y' ".
		" WHERE user.user_id = '".$user_id."'; ".
		"");
	if ($qr === false) {
		die('process_email_code_verification() fatal error 2b');
	}
	
	//
	
	$GLOBALS['visitor_id'] = false;
	$GLOBALS['user_id'] = $user_id;
	$GLOBALS['is_registered_user'] = true;
	
	$s = 'SALT'.intval(time()).$_SERVER['REMOTE_ADDR'].$GLOBALS['user_id'];
	$hash = hash('sha256', $s);
	
	setcookie('ch', $hash, (time() + my_get_session_lifetime()), '/');

	$qr = mydb_query("".
		" UPDATE user ".
		" SET user.cookie_hash = '".$hash."', ".
		" user.ip_address = '".$_SERVER['REMOTE_ADDR']."' ".
		" WHERE user.user_id = '".$user_id."'; ".
		"");
	if ($qr === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return true;
}


// =============================================================================
function outhtml_register_verifycode($param) {

	$param['formfail'] = array();
	
	if (!isset($param['code'])) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Некорректный код';
		return outhtml_register_sendmail_form($param);
	}

	if (!is_valid_hash($param['code'])) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Некорректный код';
		return outhtml_register_sendmail_form($param);
	}
	
	process_email_code_verification(&$param);
	if ((sizeof($param['formfail']) > 0)) {
		return outhtml_register_sendmail_form($param);
	}

	$out = '';
	
	//
	
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
	
	$message = 'Код для webcollect.ru '.$code.' Введите его на сайте.';
	
	/*
	list($sms_id, $sms_cnt, $cost, $balance) = send_sms($qr[0]['phone_number'], $message, 0);
	
	if ($sms_cnt > 0) {
		// $out .= "Сообщение отправлено успешно. ID: ".$sms_id.", всего SMS: ".$sms_cnt.", стоимость: ".$cost.", баланс: ".$balance.".\n";
		// $out .= "Сообщение отправлено успешно. ID: ".$sms_id."";
	} else  {
		$out .= "Ошибка №".(-$sms_cnt).", ".($sms_id ? ", ID: ".$sms_id : "")."";
	}
	*/
	
	// $out .= outhtml_register_sendsms_form($param);

	// return $out;
	
	process_set_user_registered($GLOBALS['user_id']);
	
	$GLOBALS['pagetitle'] = 'Регистрация завершена / '.$GLOBALS['pagetitle'];
	
	$out .= outhtml_register_congratulation($param);
	
	return $out;
}



// =============================================================================
function outhtml_register_verifyphone($param) {

	$param['formfail'] = array();
	
	$param['code'] = trim($param['code']);
	
	if (!isset($param['code'])) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Некорректный код';
		return outhtml_register_sendsms_form($param);
	}

	if (!ctype_digit($param['code']) || (mb_strlen($param['code']) != 6)) {
		$param['formfail'] = array();
		$param['formfail']['problem'] = 'Некорректный код';
		return outhtml_register_sendsms_form($param);
	}
	
	process_sms_code_verification(&$param);
	if ((sizeof($param['formfail']) > 0)) {
		return outhtml_register_sendsms_form($param);
	}

	$out = '';
	
	$GLOBALS['pagetitle'] = 'Регистрация завершена / '.$GLOBALS['pagetitle'];
	
	$out .= outhtml_register_congratulation($param);

	return $out;
}


// =============================================================================
function outhtml_register_congratulation($param) {

	$out = '';

	$out .= '<div style=" margin-left: 300px; " >';
	
		$out .= '<div style=" clear: both; height: 5px; "></div>';
	
		$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; color: #888888; ">Поздравляем!</h1>';
	
		$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; color: #888888; ">Регистрация завершена</h1>';
		
		$out .= '<form method="GET" action="/index.php">';
		
			$out .= '<div style=" float: left; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
			
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Теперь у вас есть больше возможностей.</div>';
				
				$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="a" value="v" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; width: 300px; ">ОК</button></div>';
						
			$out .= '</div>';
			
		$out .= '</form>';
		
	$out .= '</div>';
			
	$out .= '<div style=" clear: both; min-height: 100px; "></div>';

	return $out;
}


// =============================================================================
function outhtml_register($param) {

	if (false) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}

	// return outhtml_register_sendmail_form($param);

	if ($GLOBALS['user_id']) {
	
		// prepared query
		$a = array();
		$q = "".
			" SELECT user.user_id, ".
			" user.is_registered_user, ".
			" user.email_verified ".
			" FROM user ".
			" WHERE ( user.user_id = ? ) ".
			"";
		$a[] = $GLOBALS['user_id'];
		$t = 'i';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			die('process_email_code_verification() fatal error 0a');
		}
		if (sizeof($qres) != 1) {
			// print $GLOBALS['user_id'];
			die('process_email_code_verification() fatal error 0b');
		}
		
		if ($qres[0]['is_registered_user'] == 'Y') {
			return outhtml_welcome_screen($param).PHP_EOL;
		}
	}
	
	$out = '';
	
	$step = 'fillform';
	if (isset($param['a'])) {
		if ($param['a'] == 'r') $step = 'sendmail';
		if ($param['a'] == 'v') $step = 'verifyemail';
		// if ($param['a'] == 'p') $step = 'verifyphone';
	}
	
	$out .= '<div style=" padding-top: 20px; background-color: #f8f8f8; padding-left: 18px; color: #888888; line-height: 125%; " >';
	
		if ($step == 'fillform') {
			$out .= outhtml_register_form($param);
		}
		if ($step == 'sendmail') {
			$out .= outhtml_register_sendmail($param);
		}
		if ($step == 'verifyemail') {
			$out .= outhtml_register_verifycode($param);
		}
		/*
		if ($step == 'verifyphone') {
			$out .= outhtml_register_verifyphone($param);
		}
		*/
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>