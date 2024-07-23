<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_sel.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

//require_once($_SERVER['DOCUMENT_ROOT'].'/jquery-1.9.1.js');


// =============================================================================
function outhtml_script_item_add() {

$str = <<<SCRIPTSTRING


function js_shipmodel_sel_click(template_id, cmd, event) {

}

SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function my_get_max_picture_file_size() {
	return (4 * 1024 * 1024);
}


// =============================================================================
function outhtml_item_add_step1($param) {

	$qr = mydb_query("".
		" INSERT INTO item ".
		" SET item.status = 'I', ".
		" item.submitter_id = '".$param['user_id']."', ".
		" item.time_submit_start = '".date('Y-m-d H:i:s')."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	$item_id = mydb_insert_id();
	if (!($item_id > 0)) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	$GLOBALS['pagetitle'] .= ' - Загрузка знака, шаг 1';
	$out = '';

	my_write_log('Add Step 1');

	$out .= '<div style="padding-left: 18px; margin-top: 30px; ">';

	$out .= '<div style=" float: left;  clear: none; width: 570px; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
	$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Загрузка знака</h1>';
	$out .= '<h2 style=" font-size: 12pt; margin-bottom: 20px; color: #3f6b86; ">Шаг 1: Выберите изображение</h2>';
	$link = $_SERVER['PHP_SELF'];
	$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';

	$out .= '<input type="hidden" name="i" value="'.$item_id.'" />';
	$out .= '<input type="hidden" name="MAX_FILE_SIZE" value="'.my_get_max_picture_file_size().'" />';
	
	$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Основной вид:</div>';
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="upfile" type="file" value="" /></div>';

	/*
	$out .= '<div style=" margin-top: 15px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Дополнительный вид:</div>';
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="file" type="file" value="" /></div>';
	*/
	
	$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="step" value="2" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; ">Далее</button></div>';
	$out .= '</form>';
	
	/*
	$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 38px; font-size: 10pt; color: #888888; ">';
	$out .= 'Вы получите сообщение с адреса noreply@babloscope.info, перейдите по ссылке, укзанной в этом сообщении для завершения регистрации.';
	$out .= '</div>';
	*/
	
	$out .= '</div>';

	$out .= '<div style=" float: right; clear: none; width: 310px; ">';

		$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

			$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
			$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Требования к изображениям</strong></p>';
			$out .= '<p style=" margin-bottom: 6px; " >Файл JPEG, объемом не более 4 МБ</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Знак на чистом белом фоне</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Знак полносью помещается на изображении</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Квадратное изображение (ширина равна высоте)</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Визуальный центр знака расположен в центре изображения</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Расстояние от края изображения до края знака не должно превышать 10% от размера изображения.</p>';
			$out .= '<img src="/images/picture_instructions.png" />';
			$out .= '</div>';

		$out .= '</div>';

	$out .= '</div>';


	//$out .= '<div style=" clear: both; ">';
	
	$out .= '</div>';
	/*
	$result = myemail_send();
	if (!$result) {
	$out .= 'boo!';
	}
	*/

	$out .= '<div style=" clear: both; min-height: 100px; ">';
	$out .= '</div>';

	return $out;
}


// =============================================================================
function outhtml_parse_file($param) {



	//print_r($_FILES);
	
	if (!isset($_FILES["upfile"]["name"])) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	if ($_FILES['upfile']['error'] != 0) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;	
	}
	
	$filename = $_FILES["attachfile"]["name"];
	//..$aFileNameParts = explode(".", $filename);
	//$sFileExtension = end($aFileNameParts);
	$filesize = $_FILES["upfile"]["size"];
	$filetype = $_FILES["upfile"]["type"];
	if ($filesize == 0) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if ($filesize > my_get_max_picture_file_size()) {
		my_print_error("Слишком большой файл! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	$sTempFileName = $_FILES["upfile"]["tmp_name"];
	$oTempFile = fopen($sTempFileName, "rb");
	$sBinaryPhoto = fread($oTempFile, fileSize($sTempFileName));
	fclose($oTempFile);

	//print "<p>"."Имя: ".$_FILES["upfile"]["name"].", Размер: ".$filesize." байт. <p>";

	$is = getimagesize($_FILES["upfile"]["tmp_name"]);

	//print_r($is);

	if ( ($is[0] < 190) && ($is[1] < 190) ) {
		my_print_error("Слишком малое изображение! (".__FILE__." Line ".__LINE__.")");
		return false;
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
	}

	//print 'w';
	

	my_check_item_picture_storage_dir($param['i']);

	//print 'e';

	$newfilename = my_get_item_picture_storage_dir($param['i']).'/original.jpg';

	//print 'r';

	$r = imagejpeg($img_src, $newfilename, 98);
	if (!$r) {
		my_print_error("Ошибка при записи файла! (".__FILE__." Line ".__LINE__.")");
		return false;	
	}

	//print 't';

	/*
	$wh = fopen($newfilename, 'wb');
    if (fwrite($wh, $sBinaryPhoto) === FALSE) {
		my_print_error("Ошибка при записи файла! (".__FILE__." Line ".__LINE__.")");
    }
	fclose($wh);
	*/

	
	//date("d.m.y", strtotime($list[$i]["added"]))

	/*
	$q = my_bd_query("INSERT INTO order_files ".
		"SET order_files.file_id = '".$newid."', ".
		"order_files.order_id = '".$_POST["order_id"]."' ");
	if ($q === false) {
		my_print_error("Îøèáêà çàïèñè â ÁÄ! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	*/
	
	return '';
}


// =============================================================================
function is_possible_to_add_item($param) {
	
	if (!isset($param['i'])) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (!ctype_digit($param['i'])) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$param['i'] = ''.intval($param['i']);
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.status, item.submitter_id ".
		" FROM item ".
		" WHERE item.item_id = '".$param['i']."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (($qr[0]['status'] != 'A') || ($qr[0]['submitter_id'] != $param['user_id'])) {
		my_print_error("Ошибка при загрузке файла! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return true;
}


// =============================================================================
function resize_jpeg_from_file($filename_src, $filename_dest, $dest_width, $dest_height) {

	$quality = 94;

	$img_src = imagecreatefromjpeg($filename_src);

	$width = imagesx($img_src);
	$height = imagesy($img_src);

	if ($width > $height) {
	    $newwidth = $dest_width;
	    $divisor = $width / $dest_width;
	    $newheight = floor($height / $divisor);
	}
	else {
	    $newheight = $dest_height;
	    $divisor = $height / $dest_height;
	    $newwidth = floor($width / $divisor);
	}

	$tmpimg = imagecreatetruecolor($newwidth, $newheight);

	imagecopyresampled($tmpimg, $img_src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	imagejpeg($tmpimg, $filename_dest, $quality);

	imagedestroy($img_src);
	imagedestroy($tmpimg);

	return true;
}


// =============================================================================
function outhtml_make_item_resized_pictures($param) {

	$itemdir = my_get_item_picture_storage_dir($param['i']);
	$original = $itemdir.'/original.jpg';

	// 200px thumb
	$m = $itemdir.'/medium.jpg';
	resize_jpeg_from_file($original, $m, 200, 200);

	// 500px thumb
	$l = $itemdir.'/large.jpg';
	resize_jpeg_from_file($original, $l, 500, 500);

	return '';
}


// =============================================================================
function outhtml_item_add_step2($param) {

	if (!is_possible_to_add_item($param)) {
		return outhtml_item_add_step1($param);
	}

	$GLOBALS['pagetitle'] .= ' - Загрузка знака, шаг 2';
	$out = '';

	my_write_log('Add Step 2');
	
	$out .= outhtml_script_shipmodel_sel();
	// zzz666();

	$out .= outhtml_parse_file($param);
	$out .= outhtml_make_item_resized_pictures($param);
	//$out .= outhtml_parse_file($param);

	$out .= '<div style="padding-left: 18px; margin-top: 30px; ">';

	$out .= '<div style=" float: left;  clear: none; width: 570px; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
	$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Загрузка знака</h1>';
	$out .= '<h2 style=" font-size: 12pt; margin-bottom: 20px; color: #3f6b86; ">Шаг 2: Опишите знак</h2>';

	// bagde image div
	$out .= '<div style=" margin-top: 20px; margin-bottom: 30px; width: 202px; height: 202px; display: table-cell; border: solid 1px #e0e0e0; border-radius: 6px; -moz-border-radius: 6px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: center center; background-image: url(\'/item/image.php?i='.$param['i'].'&s=m\'); ">';
	$out .= '<a href="#" style=" display: block; " >';
	$out .= '<img src="/images/spacer.gif" width="200" height="200" />';
	$out .= '</a>';
	$out .= '</div>';
	//


	$out .= '<div style=" margin-top: 20px; ">';

	$link = $_SERVER['PHP_SELF'];
	$out .= '<form method="POST" action="'.$link.'">';

	$out .= '<input type="hidden" name="i" value="'.$param['i'].'" />';
	
	$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Основной вид:</div>';
	$out .= '<div style=" vertical-align: top; padding-right: 16px; margin-bottom: 30px; ">id='.$param['i'].'</div>';

	$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Проект корабля:</div>';
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="shipmodel_input" id="shipmodel_input"  onchange="shipmodel_sel_test()" onkeydown="shipmodel_sel_test()" onkeyup="shipmodel_sel_test()" value="" /></div>';
	
	$out .= outhtml_shipmodel_sel_div();
	
	//print $_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_sel.php';
	
	// $out .= $_SERVER['SCRIPT_FILENAME'];

	/*
	$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Шифр проекта:</div>';
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="shiptype_code" value="" /></div>';

	$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Надписи на знаке (через пробел):</div>';
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="50" name="imprints" value="" /></div>';
	*/

	/*
	$out .= '<div style=" margin-top: 15px; padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Дополнительный вид:</div>';
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="file" type="file" value="" /></div>';
	*/
	
	$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="step" value="2" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; ">Далее</button></div>';
	$out .= '</form>';
	
	/*
	$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 38px; font-size: 10pt; color: #888888; ">';
	$out .= 'Вы получите сообщение с адреса noreply@babloscope.info, перейдите по ссылке, укзанной в этом сообщении для завершения регистрации.';
	$out .= '</div>';
	*/

	$out .= '</div>';
	
	$out .= '</div>';

	$out .= '<div style=" float: right; clear: none; width: 310px; ">';

		$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

			$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
			$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Требования к изображениям</strong></p>';
			$out .= '<p style=" margin-bottom: 6px; " >Файл JPEG</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Знак на чистом белом фоне</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Квадратное изображение (ширина равна высоте)</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Визуальный центр знака расположен в центре изображения</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Расстояние от края изображения до края знака не должно превышать 10% от размера изображения.</p>';
			$out .= '<img src="/images/picture_instructions.png" />';
			$out .= '</div>';

		$out .= '</div>';

	$out .= '</div>';


	//$out .= '<div style=" clear: both; ">';
	
	$out .= '</div>';
	
	/*
	$result = myemail_send();
	if (!$result) {
	$out .= 'boo!';
	}
	*/

	$out .= '<div style=" clear: both; min-height: 100px; ">';
	$out .= '</div>';

	return $out;
}


// =============================================================================
function outhtml_item_add($param) {
	
	$out = '';
	
	//$path = $_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_sel.php';
	//require_once($path);
	//$abrabr2 = aaa();
	
	//print fileSize($path);
	// $oTempFile = fopen($path, "rb");
	// $sBinaryPhoto = fread($oTempFile, fileSize($path));
	//fclose($oTempFile);
	// print $sBinaryPhoto;
	
	//print 'z';

	if (!isset($param['step'])) $param['step'] = '1';
	
	if (isset($param['step'])) {
		if ($param['step'] == '2') {
			$ok = true;
			if (!isset($param['i'])) {
				$ok = false;
				$out .=  'Айяйяй';
			}
		}
		if ($param['step'] == '3') {
			$ok = true;
			if (!isset($param['action'])) $ok = false;
			if ($ok) if (!is_valid_hash($param['code'])) $ok = false;
			if ($ok) {
				$step = 'verifycode';
			}
		}
	}
	
	if ($param['step'] == '1') {
		$out .= outhtml_item_add_step1($param);
	}
	if ($param['step'] == '2') {
		$out .= outhtml_item_add_step2($param);
	}
	if ($param['step'] == '3') {
		$out .= outhtml_item_add_step3($param);
	}

	return $out.PHP_EOL;
}

?>