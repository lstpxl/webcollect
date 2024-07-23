<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');


// =============================================================================
function can_i_view_item_picture($item_id) {
	return true;
}


// =============================================================================
function localxhr($param) {

	if (!can_i_view_item_picture($param['i'])) {
		$filename = '/home/lastpx/www/site3/public_html/images/item_picture_denied_200x200.jpg';
	} else {
		$filename = my_get_item_picture_storage_dir($param['i']).'/original.jpg';
	}
	
	$im = imagecreatefromjpeg($filename);

	header('Content-Type: image/jpeg');
	imagejpeg($im, 91);
	imagedestroy($im);
}

?>