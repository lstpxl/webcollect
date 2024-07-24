<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/image_upload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/image_process.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image.php');


// =============================================================================
function outhtml_parse_file($param) {

	$out = '';

	//print_r($_FILES);
	
	if (!isset($_FILES["upfile"]["name"])) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	if ($_FILES['upfile']['error'] != 0) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;	
	}
	
	$filename = $_FILES["attachfile"]["name"];
	//..$aFileNameParts = explode(".", $filename);
	//$sFileExtension = end($aFileNameParts);
	$filesize = $_FILES["upfile"]["size"];
	$filetype = $_FILES["upfile"]["type"];
	if ($filesize == 0) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	if ($filesize > my_get_max_picture_file_size()) {
		$out .= outhtml_error("Слишком большой файл! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	$sTempFileName = $_FILES["upfile"]["tmp_name"];
	$oTempFile = fopen($sTempFileName, "rb");
	$sBinaryPhoto = fread($oTempFile, fileSize($sTempFileName));
	fclose($oTempFile);

	//print "<p>"."Имя: ".$_FILES["upfile"]["name"].", Размер: ".$filesize." байт. <p>";

	$is = getimagesize($_FILES["upfile"]["tmp_name"]);

	//print_r($is);

	if ( ($is[0] < 190) && ($is[1] < 190) ) {
		$out .= outhtml_error("Слишком малое изображение! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	//print 'q';

	switch ($is[2]) {
		case 1:   //   gif -> jpg
        	$img_src = imagecreatefromgif($_FILES["upfile"]["tmp_name"]);
        	break;
		case 2:   //   jpeg -> jpg
        	$img_src = imagecreatefromjpeg($_FILES["upfile"]["tmp_name"]);
        	break;
		case 3:  //   png -> jpg
			$img_src = imagecreatefrompng($_FILES["upfile"]["tmp_name"]);
			break;
		case 6:  //   bmp -> jpg
			$img_src = imagecreatefrombmp($_FILES["upfile"]["tmp_name"]);
			break;
	}
	
	if (!$img_src) {
		$out .= outhtml_error("Ошибка при обработке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	
	$iswhite = img_frame_is_white($img_src);
	if (!$iswhite) {
		$out .= outhtml_error("Фон изображения не белый!");
		// imagedestroy($img_src);
		// return $out;
	}
	
	//print 'w';
	

	$existing_images_count = my_get_item_picture_count($param['i']);
	
	
	my_check_item_picture_storage_dir($param['i']);
	
	//
	
	//my_get_item_picture_storage_dir($item_id, $n = 1)

	//print 'e';

	//$newfilename = my_get_item_picture_storage_dir($param['i']).'/original.jpg';
	$newfilename = my_get_item_picture_filepath($param['i'], ($existing_images_count + 1), 'o');
	
	my_write_log('uploadnewfilename='.$newfilename);
	
	if (mb_strlen($newfilename) < 2) {
		my_write_log('uploadnewfilename(i='.$param['i'].', existing_images_count='.$existing_images_count.')');
		return false;
	}
	
	$result = my_create_container_folders($newfilename);
	if (!$result) {
		my_write_log('my_create_container_folders() failed file='.$newfilename);
		return false;
	}
	
	if ($iswhite) {
	
		$tmp = img_crop_symm_lr($img_src);
		if ($tmp !== false) {
			//print 'croplr.';
			//imagedestroy($img_src);
			//$img_src = $tmp;
		} else {
			my_write_log('img_crop_symm_lr() failed.');
		}
		
		// print 'check2='.imagesx($img_src).'.';
		
		$tmp = img_crop_symm_tb($img_src);
		if ($tmp !== false) {
			//print 'croptd.';
			// imagedestroy($img_src);
			// $img_src = $tmp;
		} else {
			my_write_log('img_crop_symm_tb() failed.');
		}
	
	}

	
	$r = imagejpeg($img_src, $newfilename, 98);
	if (!$r) {
		$out .= outhtml_error("Ошибка при записи файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	return '';
}



// =============================================================================
function outhtml_parse_blueprint($param) {

	$out = '';

	//print_r($_FILES);
	
	if (!isset($_FILES["upfile"]["name"])) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	if ($_FILES['upfile']['error'] != 0) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;	
	}
	
	$filename = $_FILES["attachfile"]["name"];
	//..$aFileNameParts = explode(".", $filename);
	//$sFileExtension = end($aFileNameParts);
	$filesize = $_FILES["upfile"]["size"];
	$filetype = $_FILES["upfile"]["type"];
	if ($filesize == 0) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	if ($filesize > my_get_max_picture_file_size()) {
		$out .= outhtml_error("Слишком большой файл! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	$sTempFileName = $_FILES["upfile"]["tmp_name"];
	$oTempFile = fopen($sTempFileName, "rb");
	$sBinaryPhoto = fread($oTempFile, fileSize($sTempFileName));
	fclose($oTempFile);


	//print "<p>"."Имя: ".$_FILES["upfile"]["name"].", Размер: ".$filesize." байт. <p>";

	$is = getimagesize($_FILES["upfile"]["tmp_name"]);

	//print_r($is);

	
	if ( ($is[0] < 10) || ($is[1] < 10) ) {
		$out .= outhtml_error("Слишком малое изображение! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	if ( ($is[0] > 1000) || ($is[1] > 1000) ) {
		$out .= outhtml_error("Слишком большое изображение! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	//print 'q';

	switch ($is[2]) {
		case 1:   //   gif -> jpg
        	$img_src = imagecreatefromgif($_FILES["upfile"]["tmp_name"]);
        	break;
		case 2:   //   jpeg -> jpg
        	$img_src = imagecreatefromjpeg($_FILES["upfile"]["tmp_name"]);
        	break;
		case 3:  //   png -> jpg
			$img_src = imagecreatefrompng($_FILES["upfile"]["tmp_name"]);
			break;
		case 6:  //   bmp -> jpg
			$img_src = imagecreatefrombmp($_FILES["upfile"]["tmp_name"]);
			break;
	}
	
	if (!$img_src) {
		$out .= outhtml_error("Ошибка при обработке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	

	//print 'w';
	
	//$path = my_get_shipmodel_blueprint($param['shipmodel_id']);
	
	// checking file
	
	$checkstr = img_is_correct_blueprint_file($img_src);
	if ($checkstr != '') {
		$out .= outhtml_error('Файл не принят! ('.$checkstr.')');
		return $out;
	}
	
	// process opacity here
	
	$tmp = img_make_transparent($img_src, true);
	if ($tmp !== false) {
		//print 'croplr.';
		//imagedestroy($img_src);
		//$img_src = $tmp;
	} else {
		$out .= outhtml_error('Ошибка обработки прозрачности!');
		return $out;
	}
	
	//
	
	$path = my_get_blueprint_storage_dir().'/'.str_pad((''.$param['shipmodel_id']), 10, '0', STR_PAD_LEFT).'.png';
	
	// delete old
	if (is_file($path)) {
		$r = unlink($path);
		if (!$r) {
			$out .= outhtml_error("Ошибка при удалении файла! (".__FILE__." Line ".__LINE__.")");
			return $out;
		}
	}
	
	my_write_log('uploadnewfilename='.$path);
	
	$r = imagesavealpha($img_src, true);
	if (!$r) {
		$out .= outhtml_error("Ошибка при записи файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	$r = imagepng($img_src, $path, 0);
	if (!$r) {
		$out .= outhtml_error("Ошибка при записи файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	imagedestroy($img_src);
	
	$qr = mydb_query("".
		" UPDATE shipmodel ".
		" SET shipmodel.has_blueprint = 'Y' ".
		" WHERE shipmodel.shipmodel_id = '".$param['shipmodel_id']."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return '';
}


// =============================================================================
function outhtml_make_item_resized_pictures($param) {

	$existing_images_count = my_get_item_picture_count($param['i']);
	$original = my_get_item_picture_filepath($param['i'], $existing_images_count, 'o');

	// 180px thumb
	$m = my_get_item_picture_filepath($param['i'], $existing_images_count, 'm');
	//my_write_log('resize m='.$m);
	//resize_jpeg_from_file($original, $m, 200, 200);
	my_make_image_square_resized($original, $m, 180, 180, 0);

	// 500px thumb
	$l = my_get_item_picture_filepath($param['i'], $existing_images_count, 'l');
	my_make_image_square_resized($original, $l, 500, 500, 0);
	//resize_jpeg_from_file($original, $l, 500, 500);
	
	// 90px thumb
	$s = my_get_item_picture_filepath($param['i'], $existing_images_count, 's');
	my_make_image_square_resized($original, $s, 90, 90, 0);
	//resize_jpeg_from_file($original, $s, 100, 100);

	return '';
}


// =============================================================================
function outhtml_try_parse_item_image($param) {
	
	$out = '';
	$out .= outhtml_parse_file($param);
	$out .= outhtml_make_item_resized_pictures($param);
	if ($out == '') $out .= '<p style=" ">Изображение успешно добавлено.</p>';
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_try_parse_blueprint($param) {
	
	$out = '';
	$out .= outhtml_parse_blueprint($param);
	if ($out == '') $out .= '<p style=" ">Изображение успешно добавлено.</p>';
	// $out .= '<p style=" ">outhtml_try_parse_blueprint(...)</p>';
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_try_makefirst_item_image($param) {
	
	$out = '';
	
	if (!isset($param['i'])) return '';
	if (!isset($param['n'])) return '';
	
	if (!ctype_digit($param['i'])) return '';
	if (!ctype_digit($param['n'])) return '';
	
	$dir = my_get_item_picture_storage_dir($param['i'], $param['n']);
	if (!is_dir($dir)) return '';
	
	$newname = my_get_item_picture_base_storage_dir($param['i']);
	$newname .= '/'.'_1';
	
	rename($dir, $newname);
	
	my_sort_item_images($param['i']);
	
	if ($out == '') $out .= '<p style=" ">Изображение сделано основным.</p>';
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_try_delete_item_image($param) {
	
	$out = '';
	
	if (!isset($param['i'])) return '';
	if (!isset($param['n'])) return '';
	
	if (!ctype_digit($param['i'])) return '';
	if (!ctype_digit($param['n'])) return '';
	
	$piccount = my_get_item_picture_count($param['i']);
	if ($piccount <= 1) return '';
	
	$dir = my_get_item_picture_storage_dir($param['i'], $param['n']);
	if (!is_dir($dir)) return '';

	$result = unlinkRecursive($dir, true);
	if (!$result) return '';
	
	my_sort_item_images($param['i']);

	if ($out == '') $out .= '<p style=" ">Изображение удалено.</p>';
	return $out.PHP_EOL;
}


?>