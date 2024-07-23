<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');


// =============================================================================
function outhtml_send_robot_message_response_error(&$param) {

	$out = '';
	
	$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; padding-left: 18px; ">';
		
		$out .= '<h1 style=" font-size: 15pt; margin-top: 20px; margin-bottom: 20px; color: #900000; ">Запрос не отправлен. '.$param['error_text'].'</h1>';

	$out .= '</div>';

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_send_robot_message_response(&$param) {

	$out = '';
		
	$param['body'] = trim($param['body']);
	if ($param['body'] == '') {
		$param['c'] = '';
		$param['error_text'] = 'Пустое сообщение';
		return outhtml_send_robot_message_response_error(&$param);
	}

	$a = array();
	$a['text'] = $param['body'];
	$a['to'] = $param['to'];
	$a['subject'] = $param['subject'];
	$result = myemail_send_faq_response($a);
	
	if (!$result) {
		$param['c'] = '';
		$param['error_text'] = 'Техническая проблема отправки.';
		return outhtml_send_robot_message_response_error(&$param);
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
function outhtml_send_robot_message($param) {
	
	$out = '';
		
	$out .= '<div style=" margin-top: 40px; margin-bottom: 30px; padding-left: 18px; ">';
		
		$link = '/admin/send_robot_message.php';
		$out .= '<form method="POST" action="'.$link.'">';
		
		// $out .= '<input type="hidden" name="m" value="h" />';
		
		//
		
		$out .= '<p style=" font-size: 10pt; margin-bottom: 3px;  ">';
		$out .= 'Получатель:';
		$out .= '</p>';

		$out .= '<textarea cols="40" rows="1" class="hoverwhiteborder" style=" width: 552px; text-align: left; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; " name="to" />'.''.'</textarea>';
		
		//
		
		$out .= '<p style=" font-size: 10pt; margin-bottom: 3px;  ">';
		$out .= 'Тема:';
		$out .= '</p>';

		$out .= '<textarea cols="40" rows="1" class="hoverwhiteborder" style=" width: 552px; text-align: left; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; " name="subject" />'.''.'</textarea>';
		
		//
		
		$out .= '<p style=" font-size: 10pt; margin-bottom: 3px;  ">';
		$out .= 'Текст сообщения:';
		$out .= '</p>';

		$out .= '<textarea cols="40" rows="8" class="hoverwhiteborder" style=" width: 552px; text-align: left; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; " name="body" />'.''.'</textarea>';
		
		//
		
		$out .= '<p style=" font-size: 10pt; margin-bottom: 3px;  ">';
		$out .= 'Ответ будет отправлен с адреса сайта';
		$out .= '</p>';
		
		//
		
		$out .= '<button class="hoverwhiteborder" type="submit" name="c" value="sent" style=" float: left; margin-top: 18px; margin-right: 10px; margin-bottom: 30px; background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #ffffff; padding: 2px 12px 3px 12px; min-width: 130px; ">Отправить</button>';
		
		//
		
		$out .= '</form>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_admin_send_robot_message($param) {
	
	if (!am_i_admin()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}

	$GLOBALS['pagetitle'] = 'Отправить сообщение от сайта / '.$GLOBALS['pagetitle'];
	
	if (!isset($param['c'])) $param['c'] = '';
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8f; " >';
	
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 10px 0px; color: #888888; line-height: 125%; ">';
		
			if (($GLOBALS['is_registered_user']) && ($param['c'] == 'sent')) {
				$out .= outhtml_send_robot_message_response($param);
			}
			
			$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; padding-left: 18px; ">Отправить сообщение от сайта</h1>';
			
			//
			
			if (($GLOBALS['is_registered_user']) && ($param['c'] != 'sent')) {
				$out .= outhtml_send_robot_message($param);
			}
			
			//

		$out .= '</div>';
		
	
	$out .= '</div>';

	return $out.PHP_EOL;
}

?>