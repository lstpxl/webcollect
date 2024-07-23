<?php

function jqfn_captcha_image($param) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/securimage/securimage.php');
	$img = new Securimage();
	$img->show();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>