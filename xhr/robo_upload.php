<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image_parse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image.php');


// =============================================================================
function get_batch_upload_src_dir() {
	return '/home/wkh/1.wkh.z8.ru/docs/batchuploadsrc';
}


// =============================================================================
function get_batch_upload_file_list() {
	
	$dir = get_batch_upload_src_dir();

	if (!$dh = @opendir($dir)) return false;
	
	$list = array();
	
    while (false !== ($obj = readdir($dh))) {
        if($obj == '.' || $obj == '..') continue;
		$list[] = $obj;
    }
    closedir($dh);
	
	usort($list, "my_cmp_alphabeta");
	
	return $list;
}



// =============================================================================
function outhtml_parse_file_frombatchsrc($param) {

	$out = '';

	//print_r($_FILES);
	
	my_write_log('mem_before_upload_start='.memory_get_usage());

	$dir = get_batch_upload_src_dir();
	
	if (!isset($param['robo_upload_filename'])) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	my_write_log('Batch uploading file '.$param['robo_upload_filename']);

	$filename = $dir.'/'.$param['robo_upload_filename'];
	//..$aFileNameParts = explode(".", $filename);
	//$sFileExtension = end($aFileNameParts);

	if (!is_file($filename)) {
		$out .= outhtml_error("Prombelm1! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}

	$filesize = filesize($filename);
	
	if ($filesize == 0) {
		$out .= outhtml_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	if ($filesize > my_get_max_picture_file_size()) {
		$out .= outhtml_error("Слишком большой файл! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_1';

	/*
	$sTempFileName = $filename;
	$oTempFile = fopen($sTempFileName, "rb");
	$sBinaryPhoto = fread($oTempFile, $filesize);
	fclose($oTempFile);
	*/
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_2_'.3;

	//print "<p>"."Имя: ".$_FILES["upfile"]["name"].", Размер: ".$filesize." байт. <p>";

	$is = getimagesize($filename);

	//print_r($is);

	if ( ($is[0] < 190) && ($is[1] < 190) ) {
		$out .= outhtml_error("Слишком малое изображение! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	$image_mem_size = ($is[0] * $is[1] * 3);
	my_write_log('image_memory_amount='.$image_mem_size);
	
	if ($image_mem_size > (18 * 1024 * 1024)) {
		$out .= outhtml_error("Слишком большое изображение! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_2_'.$is[0].'*'.$is[1].' '.$filename;

	//print 'q';

	switch ($is[2]) {
		case 1:   //   gif -> jpg
        	$img_src = imagecreatefromgif($filename);
        	break;
		case 2:   //   jpeg -> jpg
        	$img_src = imagecreatefromjpeg($filename);
        	break;
		case 3:  //   png -> jpg
			$img_src = imagecreatefrompng($filename);
			break;
		case 6:  //   bmp -> jpg
			$img_src = imagecreatefrombmp($filename);
			break;
	}
	
	if (!$img_src) {
		$out .= outhtml_error("Ошибка при обработке файла! (".__FILE__." Line ".__LINE__.")");
		return $out;
	}
	
	my_write_log('mem_after_loading='.memory_get_usage());
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_3_'.$is[0].'*'.$is[1].' '.$filename;

	
	$iswhite = img_frame_is_white($img_src);
	if (!$iswhite) {
		// $out .= outhtml_error("Фон изображения не белый!");
		// imagedestroy($img_src);
		// return $out;
	}
	
	//print 'w';
	
	//my_create_ifnot_item_picture_storage_dir(intval($param['i']));

	$existing_images_count = my_get_item_picture_count($param['i']);
	
	if ($existing_images_count === false) {
		// return $out.' Проблема с папкой item images! ';
		$existing_images_count = 0;
	}
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_4_'.$existing_images_count;
	
	
	
	
	//
	
	//my_get_item_picture_storage_dir($item_id, $n = 1)

	//print 'e';

	//$newfilename = my_get_item_picture_storage_dir($param['i']).'/original.jpg';
	$newfilename = my_get_item_picture_filepath($param['i'], ($existing_images_count + 1), 'o');
	
	my_write_log('uploadnewfilename='.$newfilename);
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_5_'.$newfilename;
	
	if (mb_strlen($newfilename) < 2) {
		$erm = 'uploadnewfilename(i='.$param['i'].', existing_images_count='.$existing_images_count.')';
		my_write_log($erm);
		return $out.$erm;
	}
	
	$result = my_create_container_folders($newfilename);
	if (!$result) {
		$erm = 'my_create_container_folders() failed file='.$newfilename;
		my_write_log($erm);
		return $out.$erm;
	}
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_6_'.$newfilename;
	
	my_write_log('mem_before_cropping='.memory_get_usage());
	
	//
	
	/*
	
	img_crop_symm_box(&$img_src);
	
	$tmp = img_crop_symm_lr(&$img_src);
	if ($tmp !== false) {
		//print 'croplr.';
		//imagedestroy($img_src);
		//$img_src = $tmp;
	} else {
		$erm = 'img_crop_symm_lr() failed.';
		my_write_log($erm);
		return $out.$erm;
	}
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_7_'.$newfilename;
	
	// print 'check2='.imagesx($img_src).'.';
	
	my_write_log('mem_before_second_cropping='.memory_get_usage());
	
	$tmp = img_crop_symm_tb(&$img_src);
	if ($tmp !== false) {
		//print 'croptd.';
		// imagedestroy($img_src);
		// $img_src = $tmp;
	} else {
		$erm = 'img_crop_symm_tb() failed.';
		my_write_log($erm);
		return $out.$erm;
	}
	
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	// return $out.'tut_8_'.$newfilename;
	
	// print 'check5='.imagesx($img_src).'.';
	
	*/
	
	$r = img_crop_symm_box($img_src);
	if (!$r) {
		$erm = "Ошибка при обрезке изображения! (".__FILE__." Line ".__LINE__.")";
		my_write_log($erm);
		return $out.$erm;
	}

	
	$r = imagejpeg($img_src, $newfilename, 98);
	if (!$r) {
		$erm = "Ошибка при записи файла! (".__FILE__." Line ".__LINE__.")";
		my_write_log($erm);
		return $out.$erm;
	}
	
	my_write_log('mem_after_writing='.memory_get_usage());
	
	imagedestroy($img_src);
	$img_src = null;
	
	my_write_log('mem_after_frombatchsrc='.memory_get_usage());
	
	return '';
}


// =============================================================================
function outhtml_robo_upload_file(&$param) {

	$out = '';
	// $param['robo_upload_success']
	// $param['robo_upload_filename']
	// $param['i']
	
	$s = mb_convert_encoding($param['robo_upload_filename'], 'utf-8', 'Windows-1251');
	
	$out .= '<p>Обработка файла: '.$s.'</p>';
	
	//
	$s = trim($s);
	$s = mb_strtolower($s);
	$s = str_replace('\\', ' ', $s);
	$s = str_replace("'", ' ', $s);
	$s = str_replace('"', ' ', $s);
	$s = str_replace('    ', ' ', $s);
	$s = str_replace('  ', ' ', $s);
	$s = trim($s);
	$image_filename_to_store = $s;
	
	
	$qr = mydb_query("".
		" INSERT INTO item ".
		" SET item.status = 'W', ".
		" item.submitter_id = '".$GLOBALS['user_id']."', ".
		/*
		" item.shipmodelclass_id = '".'3'."', ".
		" item.shipmodelclass_str = '".'Подводная лодка и спускаемый аппарат (ПЛ)'."', ".
		" item.top_shipmodelclass_id = '".'3'."', ".
		*/
		" item.time_submit_start = '".date('Y-m-d H:i:s')."' ".
		"");
	if (!$qr) {
		$param['robo_upload_success'] = false;
		$out .= '<p style=" color: #ff0000; " >Ошибка записи в базу данных! ('.__FILE__.' Line '.__LINE__.')</p>';
		return $out.PHP_EOL;
	}
	$item_id = mydb_insert_id();
	if ($item_id == 0) {
		$param['robo_upload_success'] = false;
		$out .= '<p style=" color: #ff0000; " >Ошибка записи в базу данных! ('.__FILE__.' Line '.__LINE__.')</p>';
		return $out.PHP_EOL;
	}
	
	// prepared query
	$a = array();
	$a[] = $image_filename_to_store;
	$t = 's';
	$q = "".
		" UPDATE item ".
		" SET item.image_filename_original = ? ".
		" WHERE item.item_id = '".$item_id."' ". 
		";";
	$qres = mydb_prepquery($q, $t, $a);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	
	$param['i'] = ''.$item_id;
	
	// if there are old
	$r = my_remove_item_pictures($item_id);

	$out .= '<p style=" color: #808080; ">Created item #'.$item_id.'</p>';

	$str = outhtml_parse_file_frombatchsrc($param);
	
	if ($str != '') {
		$qr = mydb_query("".
			" DELETE FROM item ".
			" WHERE item.item_id = '".$item_id."' ".
			"");
		$param['robo_upload_success'] = false;
		$out .= $str;
		$out .= '<p style=" color: #ff0000; " >Ошибка! ('.__FILE__.' Line '.__LINE__.')</p>';
		return $out.PHP_EOL;
	}
	
	$out .= '<p style=" color: #808080; ">Parsed image.</p>';

	$str .= outhtml_make_item_resized_pictures(array('i' => $item_id));
	if ($str != '') {
		$qr = mydb_query("".
			" DELETE FROM item ".
			" WHERE item.item_id = '".$item_id."' ".
			"");
		$param['robo_upload_success'] = false;
		$out .= $str;
		$out .= '<p style=" color: #ff0000; " >Ошибка! ('.__FILE__.' Line '.__LINE__.')</p>';
		return $out.PHP_EOL;
	}
	
	$out .= '<p style=" color: #808080; ">Created resized images.</p>';

	$dir = get_batch_upload_src_dir();
	$result = unlink($dir.'/'.$param['robo_upload_filename']);
	if ($result) {
		$out .= '<p style=" color: #808080; ">Удаление исходного файла.</p>';
	} else {
		$out .= '<p style=" color: #ffff00; ">Проблема удаления исходного файла.</p>';
	}
	
	//
	
	$qr = mydb_query("".
		" UPDATE item ".
		" SET item.time_submit_finish = '".date('Y-m-d H:i:s')."' ".
		" WHERE item.item_id = '".$item_id."' ".
		"");

	$out .= '<p style=" color: #00ff00; ">Элемент успешно добавлен.</p>';
	
	//
	
	// пользовательская информация для user_id = 3
	// $str = $image_filename_to_store;
	$a = explode(' ', mb_str_replace($image_filename_to_store, '.', ' '));
	$str = '';
	$previsalpha = false;
	for ($i = 0; $i < sizeof($a); $i++) {
		$a[$i] = trim($a[$i]);
		if (ctype_digit($a[$i])) {
			if ($previsalpha) $str .= $a[$i].' ';
			$previsalpha = false;
		} else {
			if ($a[$i] != 'jpg') $str .= $a[$i].' ';
			$previsalpha = true;
		}
	}
	// 1006 альбом 3 лист 05 5-11.jpg
	$process_user_id = 3;
	$result = iurel_set_value($item_id, $process_user_id, 'storageplace', $str);
	$result = iurel_set_value($item_id, $process_user_id, 'gotit', 'Y');
	update_iurel_searchstring($item_id, $process_user_id);
	
	//
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_robo_upload(&$param) {

	$out = '';
	// $param['robo_upload_success']
	
	
	$list = get_batch_upload_file_list();

	$out .= '<p>Изображений для автозагрузки: <span style=" padding: 4px; background-color: #f0f0a0; color: #000000; font-weight: bold; ">'.sizeof($list).'<span></p>';

	if (sizeof($list) < 1) {
		$param['robo_upload_success'] = 'finished';
		$out .= '<p>Завершение работы.</p>';
		return $out.PHP_EOL;
	} else {
		$param['robo_upload_filename'] = $list[0];
		$out .= outhtml_robo_upload_file($param);
		return $out.PHP_EOL;
	}
}

// =============================================================================
function out_robo_upload_prefix($success) {

	$out = '';
	
	$r = 'fail';
	if ($success === true) $r = 'ok';
	if ($success === 'finished') $r = 'finished';
	
	$out .= '<!--';
	$out .= 'blockid=robo_upload_'.$r.';';
	$out .= '-->';
	
	return $out;
}


// =============================================================================
function jqfn_robo_upload($param) {

	$out = '';
	
	header('Content-Type: text/html; charset=utf-8');
	
	if (!am_i_admin()) {
		/*
		$out .= out_robo_upload_prefix(false);
		$out .= '<p>Стоп. У пользователя нет прав на данную операцию.</p>';
		print $out;
		return true;
		*/
	}

	$param['robo_upload_success'] = true;
	$param['i'] = 0;
	
	$result = outhtml_robo_upload($param);
	// $result = 'finished';

	$out .= out_robo_upload_prefix($param['robo_upload_success']);
	
	$out .= $result;
	
	if ($param['robo_upload_success'] === true) {
		$out .= '<div style=" width: 188px; height: 188px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$param['i'].'&n=1&s=m\'); "></div>';
	}
	
	print $out;
	
	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/robo_upload.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_robo_upload($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>
