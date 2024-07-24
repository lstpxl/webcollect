<?php

require_once('../fn/captcha_code.php');
require_once('../captcha/src/Gregwar/Captcha/CaptchaBuilder.php');

use Gregwar\Captcha\CaptchaBuilder;

function jqfn_captcha_image2($param) {
	$builder = new CaptchaBuilder;
	$builder->build();

	$captcha_code = $builder->getPhrase();
	// store_visitor_captcha($captcha_code);

	var_dump($captcha_code);

	// header('Content-type: image/jpeg');
	// $builder->output();
}

var_dump('1');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
