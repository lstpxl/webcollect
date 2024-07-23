<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');



// =============================================================================
function is_captcha_ok($param) {
	
	include_once $_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php';
	
	$securimage = new Securimage();

	if ($securimage->check($param['captcha_code']) == false) return false;
	
	return true;
}


// =============================================================================
function outhtml_restore_password_step1(&$param) {
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; ">';
			
				$out .= '<form method="POST" action="/restore_password.php">';
				
				// $out .= '<input type="hidden" name="action" value="save" />';
			
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Восстановление пароля</h1>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 500px; margin-top: 2px; ">';
					$out .= 'На ваш адрес электронной почты будут отправлены дальнейшие инструкции.';
				$out .= '</p>';
				
				//
		
				$out .= '<div style=" clear: both; margin-top: 25px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">адрес e-mail'.$rqrd.':</div>';
				
				if (isset($param['formfail']['email'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['email'].'</div>';
				}
				
				$v = (isset($param['email']) ? $param['email'] : '');
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="email" value="'.$v.'" /></div>';
				
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
			
				$out .= '<div style=" margin-top: 5px; padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #888888; ">код с картинки'.$rqrd.':</div>';
				
				if (isset($param['formfail']['captcha_code'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['captcha_code'].'</div>';
				}
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 267px; " size="20" type="text" size="10" maxlength="6" name="captcha_code" value="" /></div>';
				
				//
				
				//
				
				$out .= '<div style=" height: 25px; "></div>';
				
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; " type="submit" name="step" value="2" >Отправить</button>';
					
					$out .= '</form>';
					
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/index.php\'; ">Отмена</button>';
				
				$out .= '<div style=" clear: both; height: 10px; "></div>';
				

			$out .= '</div>';
		
		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}




// =============================================================================
function outhtml_restore_password_step2(&$param) {
	
	$out = '';
	
	$param['formfail'] = array();
	
	// captcha
	if (!is_captcha_ok($param)) {
		$param['formfail']['captcha_code'] = 'Код каптча не совпал';
		out_silent_error_userinput('Код каптча не совпал');
	}
	
	// email
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
	
	if (sizeof($param['formfail']) > 0) {
		return outhtml_restore_password_step1(&$param);
	}
	
	//
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; ">';
			
				$out .= '<form method="POST" action="/personal/restore_password.php">';
				
				// $out .= '<input type="hidden" name="action" value="save" />';
			
				$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Восстановление пароля</h1>';
				
				$out .= '<p class="grayeleg" style=" text-align: justify; float: left; width: 500px; margin-top: 2px; ">';
					$out .= 'На ваш адрес электронной почты будут отправлены дальнейшие инструкции.';
				$out .= '</p>';
				
				//
		
				$out .= '<div style=" clear: both; margin-top: 25px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">адрес e-mail'.$rqrd.':</div>';
				
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 240px; " size="20" name="email" value="" /></div>';
				
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
			
				$out .= '<div style=" margin-top: 5px; padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #888888; ">код с картинки'.$rqrd.':</div>';
				
				if (isset($param['formfail']['captcha_code'])) {
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #ff3030; ">'.$param['formfail']['captcha_code'].'</div>';
				}
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 267px; " size="20" type="text" size="10" maxlength="6" name="captcha_code" value="" /></div>';
				
				//
				
				//
				
				$out .= '<div style=" height: 25px; "></div>';
				
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; " type="submit" name="step" value="2" >Отправить</button>';
					
					$out .= '</form>';
					
					$out .= '<button class="lightbluegradient hoverlightblueborder" style=" cursor: pointer; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; float: left; margin-left: 20px; " onclick=" window.location.href = \'/index.php\'; ">Отмена</button>';
				
				$out .= '<div style=" clear: both; height: 10px; "></div>';
				

			$out .= '</div>';
		
		$out .= '</div>';
				
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_restore_password($param) {

	if ($GLOBALS['is_registered_user']) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}

	$GLOBALS['pagetitle'] = 'Восстановление пароля / '.$GLOBALS['pagetitle'];
	
	$out = '';

	if (!isset($param['step'])) $param['step'] = '1';
	if (!ctype_digit($param['step'])) $param['step'] = '1';
	$param['step'] = ''.intval($param['step']);
	
	switch ($param['step']) {

		case '1': $out .= outhtml_restore_password_step1($param);
		break;

		case '2': $out .= outhtml_restore_password_step2($param);
		break;
				
		default: $out .= outhtml_restore_password_step1($param);
	}
	
	return $out.PHP_EOL;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>