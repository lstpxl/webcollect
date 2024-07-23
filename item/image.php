<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');


// =============================================================================
function can_i_view_item_picture($item_id) {
	return true;
}


// =============================================================================
function pass_denied_image($s) {

	//my_write_log('Image Deny A '.__LINE__.'');

	$filename = my_get_siteroot_dir().'/images/item_picture_denied_200x200.jpg';
	// if ($s == 'l') $filename = my_get_siteroot_dir().'/images/item_picture_denied_500x500.jpg';
	if ($s == 's') $filename = my_get_siteroot_dir().'/images/item_picture_denied_90x90.jpg';
	
	header('Content-Type: image/jpeg');
	send_headers_2weeks();
	
	// header("Content-length: ".$q[0]["size"]);
	// header("Content-Disposition: attachment; filename=\"".$q[0]["filename"]."\"");
	//header("Content-Type: application/octet-stream");
	// header("Content-Transfer-Encoding: binary");
	
	//my_write_log('Image Deny B '.__LINE__.'');
	
	$handle = fopen($filename, "r");
	if ($handle === false) {
		out_silent_error('fopen fail.');
	}
	fpassthru($handle);
	fclose($handle);
	
	//my_write_log('Image Deny C '.__LINE__.'');

	return true;
}


// =============================================================================
function localxhr($param) {

	// my_write_log('Image line '.__LINE__.'');

	// i = item_id
	// n = image index
	// s = size (o - original, m - list, l - large, s - small)
	
	if (!isset($param['i'])) return pass_denied_image($param['s']);
	if (!ctype_digit($param['i'])) return pass_denied_image($param['s']);
	$param['i'] = ''.intval($param['i']);
	
	if ($param['i'] == 0) {
		return pass_denied_image($param['s']);
	}
	
	$sizelist = array('m', 'l', 's', 'o');
	if (!isset($param['s'])) $param['s'] = $sizelist[0];
	if (!in_array($param['s'], $sizelist)) $param['s'] = $sizelist[0];
	
	if (!can_i_view_item_picture($param['i'])) {
		return pass_denied_image($param['s']);
	}

	if (!(am_i_admin() || am_i_vipcollector())) {
		if (($param['s'] == 'l') || ($param['s'] == 'o')) {
			// my_write_log('Hi Res Requested '.__LINE__.'');
			return pass_denied_image($param['s']);
		}
	}
	
	/*
	if (!am_i_registered_user()) {
		return pass_denied_image($param['s']);
	}
	*/
	
	// my_write_log('Image line '.__LINE__.'');
	
	if (!isset($param['n'])) $param['n'] = '1';
	if (!ctype_digit($param['n'])) $param['n'] = '1';
	
	$filename = my_get_item_picture_filepath($param['i'], $param['n'], $param['s']);
	
	// my_write_log('Image line '.__LINE__.'');
	//my_write_log('Image filename '.$filename.'');
	
	if ($filename === false) {
		my_write_log('!!!!');
		return pass_denied_image($param['s']);
	}
	
	//my_write_log('Image line '.__LINE__.'');
	//my_write_log('Image filename '.$filename.'');

	//header('Content-Type: text/html; charset=utf-8');
	//print $filename;
	
	my_write_log('Item image requested i='.$param['i'].' s='.$param['s'].' n='.$param['n'].'.');

	header('Content-Type: image/jpeg');
	send_headers_2weeks();
	
	// header("Content-length: ".$q[0]["size"]);
	// header("Content-Type: application/octet-stream");
	// header("Content-Transfer-Encoding: binary");
	if (am_i_admin()) {
		header("Content-Disposition: attachment; filename=\"".'webcollect_navy_item_'.$param['i'].'_n'.$param['n'].'_s'.$param['s'].'.jpeg'."\"");
	}
	
	$handle = fopen($filename, "r");
	if ($handle === false) {
		out_silent_error('fopen fail.');
	}
	fpassthru($handle);
	fclose($handle);

	return true;
}

?>