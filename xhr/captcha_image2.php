<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/captcha_code.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/captcha/src/Gregwar/Captcha/CaptchaBuilder.php');

use Gregwar\Captcha\CaptchaBuilder;

function jqfn_captcha_image2($param) {
	$builder = new CaptchaBuilder;
	$builder->build();

	$captcha_code = $builder->getPhrase();
	store_visitor_captcha($captcha_code);
	store_user_captcha($captcha_code);

	header('Content-type: image/jpeg');
	$builder->output();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
