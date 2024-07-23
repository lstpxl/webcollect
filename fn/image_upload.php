<?php

// =============================================================================
function dwordize($str) {
	$a = ord($str[0]);
	$b = ord($str[1]);
	$c = ord($str[2]);
	return $c * 256 * 256 + $b * 256 + $a;
}


// =============================================================================
function imagecreatefrombmp($filename) {
	
	$f = fopen($filename, "rb");

	//read header
	$header = fread($f, 54);
	$header = unpack('c2identifier/Vfile_size/Vreserved/Vbitmap_data/Vheader_size/'.
	'Vwidth/Vheight/vplanes/vbits_per_pixel/Vcompression/Vdata_size/'.
	'Vh_resolution/Vv_resolution/Vcolors/Vimportant_colors', $header);

	if ($header['identifier1'] != 66 or $header['identifier2'] != 77)
	return false;

	if ($header['bits_per_pixel'] != 24)
	return false;

	$wid2 = ceil((3 * $header['width']) / 4) * 4;

	$wid = $header['width'];
	$hei = $header['height'];

	$img = imagecreatetruecolor($header['width'], $header['height']);

	//read pixels
	for ($y = $hei - 1; $y >= 0; $y--) {
	$row = fread($f, $wid2);
	$pixels = str_split($row, 3);

	for ($x = 0; $x < $wid; $x++) {
	imagesetpixel($img, $x, $y, dwordize($pixels[$x]));
	}
	}
	fclose($f);
	return $img;
}


// =============================================================================
function process_image_upload(&$param) {

	// вход
	//   $param['field_name']
	//   $param['size_limit_bytes']
	//   $param['size_limit_px_width']
	//   $param['size_limit_px_height']
	// выход
	//   image resource OR false 
	//   $param['image_upload_error_message']
	
	$param['image_upload_error_message'] = '';

	if (!isset($param)) {
		$param['image_upload_error_message'] = "Ошибка (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	if (!isset($param['field_name'])) {
		$param['image_upload_error_message'] = "Ошибка (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	if (!isset($param['size_limit_bytes'])) $param['size_limit_bytes'] = 0;
	if (!isset($param['size_limit_px_width'])) $param['size_limit_px_width'] = 0;
	if (!isset($param['size_limit_px_height'])) $param['size_limit_px_height'] = 0;

	$out = '';
	
	if (!isset($_FILES)) {
		$param['image_upload_error_message'] = "Ошибка (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	if (!isset($_FILES[$param['field_name']])) {
		$param['image_upload_error_message'] = "Ошибка (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	$file = $_FILES[$param['field_name']];
	
	if (!isset($file['name'])) {
		$param['image_upload_error_message'] = "Ошибка (".__FILE__." Line ".__LINE__.")";
		return false;
	}

	if ($file['error'] != 0) {
		$param['image_upload_error_message'] = "Ошибка (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	$filesize = $file['size'];
	// $filetype = $file['type'];
	if ($filesize == 0) {
		$param['image_upload_error_message'] = "Ошибка (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	if ($param['size_limit_bytes'] > 0) if ($filesize > $param['size_limit_bytes']) {
		$param['image_upload_error_message'] =  "Слишком большой файл (".__FILE__." Line ".__LINE__.")";
		return false;
	}

	$oTempFile = fopen($file['tmp_name'], 'rb');
	$sBinaryPhoto = fread($oTempFile, fileSize($file['tmp_name']));
	fclose($oTempFile);

	$is = getimagesize($file['tmp_name']);

	
	if ($param['size_limit_px_width'] > 0) if ($is[0] > $param['size_limit_px_width']) {
		$param['image_upload_error_message'] =  "Слишком большой размер изображения (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	if ($param['size_limit_px_height'] > 0) if ($is[0] > $param['size_limit_px_height']) {
		$param['image_upload_error_message'] =  "Слишком большой размер изображения (".__FILE__." Line ".__LINE__.")";
		return false;
	}

	switch ($is[2]) {
		case 1:   //   gif -> jpg
        	$img_src = imagecreatefromgif($file["tmp_name"]);
        	break;
		case 2:   //   jpeg -> jpg
        	$img_src = imagecreatefromjpeg($file["tmp_name"]);
        	break;
		case 3:  //   png -> jpg
			$img_src = imagecreatefrompng($file["tmp_name"]);
			break;
		case 6:  //   bmp -> jpg
			$img_src = imagecreatefrombmp($file["tmp_name"]);
			break;
		default:
			$param['image_upload_error_message'] =  "Неизвестный тип файла изображения (".__FILE__." Line ".__LINE__.")";
			return false;
	}
	
	if (!$img_src) {
		$param['image_upload_error_message'] =  "Ошибка при обработке файла! (".__FILE__." Line ".__LINE__.")";
		return false;
	}

	$width = imagesx($img_src);
	if ($width < 1) {
		$param['image_upload_error_message'] =  "Ошибка при обработке файла! (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	if ($param['size_limit_px_width'] > 0) if ($width > $param['size_limit_px_width']) {
		$param['image_upload_error_message'] =  "Слишком большой размер изображения (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	$height = imagesy($img_src);
	if ($height < 1) {
		$param['image_upload_error_message'] =  "Ошибка при обработке файла! (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	if ($param['size_limit_px_height'] > 0) if ($height > $param['size_limit_px_height']) {
		$param['image_upload_error_message'] =  "Слишком большой размер изображения (".__FILE__." Line ".__LINE__.")";
		return false;
	}
	
	return $img_src;
}

?>