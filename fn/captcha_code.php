<?php

function store_visitor_captcha($code) {
  if (!$GLOBALS['visitor_id']) return false;
  $qr = mydb_query("".
  " UPDATE visitor ".
  " SET visitor.captcha_code = '".$code."' ".
  " WHERE visitor.visitor_id = '".$GLOBALS['visitor_id']."'; ".
  "");
  if ($qr === false) return false;
	return true;
}

function get_visitor_captcha() {
  if (!$GLOBALS['visitor_id']) return false;
  $qr = mydb_queryarray("".
  "SELECT visitor.visitor_id, visitor.captcha_code ".
  "FROM visitor ".
  " WHERE visitor.visitor_id = '".$GLOBALS['visitor_id']."'; ".
  "");
  if ($qr === false) return false;
  if (sizeof($qr) < 1) return false;
  return $qr[0]['captcha_code'];
}


function store_user_captcha($code) {
  if (!$GLOBALS['user_id']) return false;
  $qr = mydb_query("".
  " UPDATE user ".
  " SET user.captcha_code = '".$code."' ".
  " WHERE user.user_id = '".$GLOBALS['user_id']."'; ".
  "");
  if ($qr === false) return false;
	return true;
}

function get_user_captcha() {
  if (!$GLOBALS['user_id']) return false;
  $qr = mydb_queryarray("".
  "SELECT user.user_id, user.captcha_code ".
  "FROM user ".
  " WHERE user.user_id = '".$GLOBALS['user_id']."'; ".
  "");
  if ($qr === false) return false;
  if (sizeof($qr) < 1) return false;
  return $qr[0]['captcha_code'];
}
