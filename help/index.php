<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');



// =============================================================================
function outhtml_help_request_form_response_error(&$param) {

	$out = '';
	
	$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; padding-left: 18px; ">';
		
		$out .= '<h1 style=" font-size: 15pt; margin-top: 20px; margin-bottom: 20px; color: #900000; ">Запрос не отправлен. '.$param['error_text'].'</h1>';

	$out .= '</div>';

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_help_request_form_response(&$param) {

	$out = '';
	
	include_once $_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php';
	$securimage = new Securimage();
	$securimage -> code_length = 4;
	$img -> image_signature = 'webcollect.ru';
	$img -> signature_color = new Securimage_Color('#000000');

	if ($securimage->check($param['captcha_code']) == false) {
		$param['c'] = '';
		$param['error_text'] = 'Ошибка кода captcha';
		return outhtml_help_request_form_response_error(&$param);
	}
	
	$param['question'] = trim($param['question']);
	if ($param['question'] == '') {
		$param['c'] = '';
		$param['error_text'] = 'Пустое сообщение';
		return outhtml_help_request_form_response_error(&$param);
	}

	$a = array();
	$a['email'] = 'admin@webcollect.ru';
	// $a['email'] = 'ip@lastpixel.ru';
	$a['text'] = $param['question'];
	$result = myemail_send_faq_message($a);
	
	if (!$result) {
		$param['c'] = '';
		$param['error_text'] = 'Техническая проблема отправки.';
		return outhtml_help_request_form_response_error(&$param);
	}
		
	$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; padding-left: 18px; ">';
		
		$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; color: #87a3b4; ">Запрос отправлен</h1>';
		
		//
		
		$out .= '<p style=" font-size: 10pt; margin-bottom: 3px;  ">';
		$out .= 'Мы пришлем ответ на ваш адрес электронной почты '.my_get_user_email($GLOBALS['user_id']).'.';
		$out .= '</p>';
		
		
		//

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_help_request_form($param) {
	
	$out = '';
		
	$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; padding-left: 18px; ">';
		
		$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; ">Задайте вопрос</h1>';
		
		//
		
		$out .= '<p style=" font-size: 10pt; margin-bottom: 3px;  ">';
		$out .= 'Ваш вопрос:';
		$out .= '</p>';
		
		$link = '/index.php';
		$out .= '<form method="POST" action="'.$link.'">';
		
		$out .= '<input type="hidden" name="m" value="h" />';
		
		$out .= '<textarea cols="40" rows="4" class="hoverwhiteborder" style=" width: 552px; text-align: left; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; " name="question" />'.''.'</textarea>';
		
		$out .= '<p style=" font-size: 10pt; margin-bottom: 3px;  ">';
		$out .= 'Мы пришлем ответ на ваш адрес электронной почты.';
		$out .= '</p>';
		
		//
		
		$out .= '<div style=" width: 536px; border: solid 1px #b0b0b0; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; ">';
		
			include_once $_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php';
			$securimage = new Securimage();
			$securimage -> code_length = 4;
			$securimage -> image_signature = 'webcollect.ru';
			$securimage -> signature_color = new Securimage_Color('#000000');
			
			$out .= '<img id="captcha" src="/xhr/captcha_image.php" alt="CAPTCHA Image" style= " margin-top: 10px; margin-bottom: 10px; display: block; float: left; " title="Securimage Captcha Script. Copyright &copy; 2011 Drew Phillips" />';
			
			$out .= '<a href="#" title="показать другой код" onclick="document.getElementById(\'captcha\').src = \'/xhr/captcha_image.php?\' + Math.random(); return false" style=" display: block; width: 16px; height: 16px; float: left; margin: 10px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/arrow_refresh.png\'); " ></a>';
			
			$out .= '<input type="text" class="hoverwhiteborder"  name="captcha_code" size="10" maxlength="6" style=" float: right; margin: 10px;  border-radius: 3px; -moz-border-radius: 3px; padding: 4px 12px 5px 12px;  min-width: 104px;  " />';
			
			$out .= '<p style=" float: right; margin-top: 10px; padding: 1px 2px 3px 12px; ">введите код: </p>';
			
			$out .= '<div style=" clear: right; "></div>';
			
			
			$out .= '<button class="hoverwhiteborder" type="submit" name="c" value="sent" style=" float: right; margin-top: 18px; margin-right: 10px; background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #ffffff; padding: 2px 12px 3px 12px; min-width: 130px; ">Отправить</button>';
			
			$out .= '<div style=" clear: both; "></div>';
		
		$out .= '</div>';
		
		
		
		/*
		if ($securimage->check($_POST['captcha_code']) == false) {
  // the code was incorrect
  // you should handle the error so that the form processor doesn't continue

  // or you can use the following code if there is no validation or you do not know how
  echo "The security code entered was incorrect.<br /><br />";
  echo "Please go <a href='javascript:history.go(-1)'>back</a> and try again.";
  exit;
}

		*/
		
		$out .= '</form>';
		//

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_help_index($param) {

	$GLOBALS['pagetitle'] = 'Справка / '.$GLOBALS['pagetitle'];
	
	if (!isset($param['c'])) $param['c'] = '';
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8f; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			if (($GLOBALS['is_registered_user']) && ($param['c'] == 'sent')) {
				$out .= outhtml_help_request_form_response($param);
			}
			
			$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; padding-left: 18px; ">Вопросы и ответы</h1>';
			
			//
			
			$out .= '<p style=" font-size: 10pt; margin-bottom: 15px; padding-left: 18px; font-weight: bold; color: #505050; ">';
			$out .= 'Для кого наш сервис?';
			$out .= '</p>';

			$out .= '<p style=" font-size: 10pt; margin-bottom: 20px; padding-left: 18px; ">';
			$out .= 'Для людей, которые так же, как и мы, интересуются коллекционированием знаков по теме кораблей и судов России и СССР.';
			$out .= '</p>';
			
			$out .= '<p style=" font-size: 10pt; margin-bottom: 15px; padding-left: 18px; font-weight: bold; color: #505050; ">';
			$out .= 'Зачем он нам и вам?';
			$out .= '</p>';

			$out .= '<p style=" font-size: 10pt; margin-bottom: 20px; padding-left: 18px; ">';
			$out .= 'Это удобно, универсально,';
			$out .= '</p>';
			
			$out .= '<p style=" font-size: 10pt; margin-bottom: 15px; padding-left: 18px; font-weight: bold; color: #505050; ">';
			$out .= 'Как появился данный сайт?';
			$out .= '</p>';

			$out .= '<p style=" font-size: 10pt; margin-bottom: 20px; padding-left: 18px; ">';
			$out .= 'Нам было неудобно вести учет наших коллекций. Мы не помнили все знаки наизусть и не могли быстро ответить что у нас есть, а чего нет. Мы общались между собой и нам было трудно объяснить, какой предмет мы обсуждаем. И мы решили сделать этот инструмент.';
			$out .= '</p>';

			$out .= '<p style=" font-size: 10pt; margin-bottom: 15px; padding-left: 18px; font-weight: bold; color: #505050; ">';
			$out .= 'Какие возможности?';
			$out .= '</p>';

			$out .= '<p style=" font-size: 10pt; margin-bottom: 20px; padding-left: 18px; ">';
			$out .= 'Просмотр всех известных знаков нашей тематики, поиск в структурированном каталоге, учет собственной коллекции.';
			$out .= '</p>';
			
			
			if (($GLOBALS['is_registered_user']) && ($param['c'] != 'sent')) {
				$out .= outhtml_help_request_form($param);
			}
			
			//

		$out .= '</div>';
		
	
	$out .= '</div>';

	return $out.PHP_EOL;
}

?>