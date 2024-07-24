<?php
//require_once($_SERVER['DOCUMENT_ROOT'].'/phpmailer/class.phpmailer.php');

// =============================================================================
function myemail_send() {

	$email_subject = 'Привет от '.my_get_http_domain();
	$email_subject = '=?'.'UTF-8'.'?B?'.base64_encode($email_subject).'?=';
	
	$email_to = 'ip@lastpixel.ru';
	// $email_to = '=?'.'UTF-8'.'?B?'.base64_encode($email_to).'?=';
	
	$email_headers = '';
	$email_headers .= 'From: "Robot '.my_get_http_domain().'" <noreply@'.my_get_http_domain().'>'.PHP_EOL;
	$email_headers .= 'MIME-Version: 1.0'.PHP_EOL;
	$email_headers .= 'Content-type: text/plain; charset=UTF-8'.PHP_EOL;
	$email_headers .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
	
	$email_body = "Всякая фигня 1\nLine 2\nLine 3";
	$email_body = wordwrap($email_body, 70);
	$email_body = htmlspecialchars_decode($email_body, ENT_QUOTES);
	$email_body = base64_encode($email_body);

	$result = mail($email_to, $email_subject, $email_body, $email_headers);
	
	if (!$result) return false;

	return true;
}


// =============================================================================
function myemail_send_registration($param) {

	// var_dump('sending email..');

	$linkstr = 'http://'.my_get_http_domain().'/register.php?a=v&code='.$param['codestr'];

	$email_subject = ''.my_get_http_domain().' регистрация';
	// $email_subject = '=?'.'UTF-8'.'?B?'.base64_encode($email_subject).'?=';
	
	$email_to = $param['email'];
	// $email_to = '=?'.'UTF-8'.'?B?'.base64_encode($email_to).'?=';
	
	$email_headers = '';
	$email_headers .= 'From: '.my_get_robot_email()."\r\n";
	$email_headers .= 'Reply-To: '.my_get_reply_email()."\r\n";
	$email_headers .= 'X-Mailer: PHP/' . phpversion().PHP_EOL;
	$email_headers .= 'Content-type: text/plain; charset=UTF-8';

	// $email_headers .= 'MIME-Version: 1.0'.PHP_EOL;
	// $email_headers .= 'Content-type: text/plain; charset=UTF-8'.PHP_EOL;
	// $email_headers .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
	
	$email_body = 'Здравствуйте!'.PHP_EOL;
	$email_body .= 'Пожалуйста, не отвечайте на это сообщение. Оно создано автоматической системой.'.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'При регистрации на сайте '.my_get_http_domain().' был указан ваш e-mail.'.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'Если регистрация была запущена не вами, проигнорируйте это сообщение.'.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'Для завершения регистрации перейдите по ссылке '.PHP_EOL;
	$email_body .= 	$linkstr.PHP_EOL;
	$email_body .= 'или'.PHP_EOL;
	$email_body .= 'скопируйте код '.PHP_EOL;
	$email_body .= $param['codestr'].PHP_EOL;
	$email_body .= 'в форму на сайте '.my_get_http_domain().PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'Внимание! Запрос на регистрацию активен в течении суток с момента отправки этого сообщения.'.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'Искренне ваш,'.PHP_EOL;
	$email_body .= 'Робот '.my_get_http_domain().PHP_EOL;
	// $email_body = wordwrap($email_body, 70); // не работает с мультибайт нормально
	$email_body = htmlspecialchars_decode($email_body, ENT_QUOTES);
	// $email_body = base64_encode($email_body);

/* 	var_dump($email_to);
	var_dump($email_subject);
	var_dump($email_body);
	var_dump($email_headers); */

	// $result = mail($email_to, $email_subject, $email_body, $email_headers);
	$result = mb_send_mail($email_to, $email_subject, $email_body, $email_headers);
	
	if (!$result) {
		var_dump('Problem sending email');
		var_dump($email_to);
		var_dump($email_subject);
		var_dump($email_body);
		var_dump($email_headers);
		return false;
	}

	// var_dump('email sent');

	return true;
}



// =============================================================================
function myemail_send_phone_check($param) {

	$email_subject = 'webcollect##Код для сайта webcollect.ru: '.$param['code'].' Илья передает привет';
	$email_subject = '=?'.'UTF-8'.'?B?'.base64_encode($email_subject).'?=';
	
	$email_to = 'forward@sms4b.ru';
	// $email_to = '=?'.'UTF-8'.'?B?'.base64_encode($email_to).'?=';
	
	$email_headers = '';
	// $email_headers .= 'From: "noreply@'.my_get_http_domain().''.PHP_EOL;
	$email_headers .= 'From: noreply_webcollect'.PHP_EOL;
	$email_headers .= 'MIME-Version: 1.0'.PHP_EOL;
	$email_headers .= 'Content-type: text/plain; charset=UTF-8'.PHP_EOL;
	$email_headers .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
	
	$email_body = $param['phone'].PHP_EOL;
	
	$email_body = htmlspecialchars_decode($email_body, ENT_QUOTES);
	$email_body = base64_encode($email_body);

	$result = mail($email_to, $email_subject, $email_body, $email_headers);
	
	if (!$result) return false;

	return true;
}


// =============================================================================
function myemail_send_faq_message($param) {

	$linkstr = '';

	$email_subject = 'Вопрос от пользователя '.my_get_user_name($GLOBALS['user_id']);
	$email_subject = '=?'.'UTF-8'.'?B?'.base64_encode($email_subject).'?=';
	
	$email_to = $param['email'];
	// $email_to = '=?'.'UTF-8'.'?B?'.base64_encode($email_to).'?=';
	
	$email_headers = '';
	$email_headers .= 'From: "Robot WebCollect.ru" <noreply@'.my_get_http_domain().'>'.PHP_EOL;
	$email_headers .= 'MIME-Version: 1.0'.PHP_EOL;
	$email_headers .= 'Content-type: text/plain; charset=UTF-8'.PHP_EOL;
	$email_headers .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
	
	
	$email_headers .= 'Reply-To: '.my_get_user_email($GLOBALS['user_id']).PHP_EOL;
	
	/*
	my_get_user_name($GLOBALS['user_id'])
	my_get_user_email($GLOBALS['user_id'])
	*/
	
	$email_body = 'Вопрос от пользователя '.my_get_user_name($GLOBALS['user_id']).':'.PHP_EOL;
	$email_body .= 'Сайт '.my_get_http_domain().PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= '-------------- текст вопроса ---------------'.PHP_EOL;
	$email_body .= $param['text'].PHP_EOL;
	$email_body .= '--------------------------------------------'.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'Искренне ваш,'.PHP_EOL;
	$email_body .= 'Робот '.my_get_http_domain().PHP_EOL;
	// $email_body = wordwrap($email_body, 70); // не работает с мультибайт нормально
	$email_body = htmlspecialchars_decode($email_body, ENT_QUOTES);
	$email_body = base64_encode($email_body);

	$result = mail($email_to, $email_subject, $email_body, $email_headers);
	
	if (!$result) return false;

	return true;
}



// =============================================================================
function myemail_send_faq_response($param) {

	$linkstr = '';

	$email_subject = ''.$param['subject'];
	$email_subject = '=?'.'UTF-8'.'?B?'.base64_encode($email_subject).'?=';
	
	$email_to = $param['to'];
	// $email_to = '=?'.'UTF-8'.'?B?'.base64_encode($email_to).'?=';
	
	$email_headers = '';
	$email_headers .= 'From: "WebCollect.ru" <noreply@'.my_get_http_domain().'>'.PHP_EOL;
	$email_headers .= 'MIME-Version: 1.0'.PHP_EOL;
	$email_headers .= 'Content-type: text/plain; charset=UTF-8'.PHP_EOL;
	$email_headers .= 'Content-Transfer-Encoding: base64'.PHP_EOL;
	
	
	// $email_headers .= 'Reply-To: '.my_get_user_email($GLOBALS['user_id']).PHP_EOL;
	
	/*
	my_get_user_name($GLOBALS['user_id'])
	my_get_user_email($GLOBALS['user_id'])
	*/
	
	// $param['body']
	
	//$email_body = 'Вопрос от пользователя '.my_get_user_name($GLOBALS['user_id']).PHP_EOL;
	//$email_body .= 'Сайт '.my_get_http_domain().PHP_EOL;
	$email_body = 'Тема: '.$param['subject'].PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	// $email_body .= '-------------- текст сообщения ---------------'.PHP_EOL;
	$email_body .= $param['body'].PHP_EOL;
	//$email_body .= '--------------------------------------------'.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'Пожалуйста, не отвечайте на это сообщение. Используйте форму в разделе «Справка»'.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= ''.PHP_EOL;
	$email_body .= 'Искренне ваш,'.PHP_EOL;
	$email_body .= 'Коллектив '.my_get_http_domain().PHP_EOL;
	// $email_body = wordwrap($email_body, 70); // не работает с мультибайт нормально
	$email_body = htmlspecialchars_decode($email_body, ENT_QUOTES);
	$email_body = base64_encode($email_body);

	$result = mail($email_to, $email_subject, $email_body, $email_headers);
	
	if (!$result) return false;

	return true;
}



?>