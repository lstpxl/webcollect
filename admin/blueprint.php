 <?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image_parse.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_delete.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/fn/block_iurel_edit.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/fn/block_item_upload.php');


// =============================================================================
function outhtml_blueprint_instruction($param) {
	
	$out = '';
	
		$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

			$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
			$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Изображение</strong></p>';
			$out .= '<p style=" margin-bottom: 6px; " >Подходят монохромные изображения. Черное изображение на белом фоне</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Формата файла JPEG или PNG.</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Максимальный размер: 550x180 пикселов.</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Минимальный размер: 200x20 пикселов.</p>';
			$out .= '<p style=" margin-bottom: 6px; " ><img src="/images/blueprint_sample_on_white.png" /></p>';
			$out .= '<p style=" margin-bottom: 6px; " >Нос корабля справа, корма - слева.</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Нижняя граница изображения должна проходить по ватерлинии.</p>';
			$out .= '<p style=" margin-bottom: 6px; " >Левый край корпуса должен отстоять от левой границы изображения на 10 пикселей по уровню ватерлинии.</p>';
			// $out .= '<p style=" margin-bottom: 6px; " >Скачайте инструкцию по подготовке изображений.</p>';
			// $out .= '<p style=" margin-bottom: 6px; " ><a style=" color: #ffffff; font-size: 10pt; "  href="/help/blueprint_manual.pdf">Инструкция</a></p>';
			$out .= '</div>';

		$out .= '</div>';

	return $out;
}


// =============================================================================
function outhtml_try_delete_blueprint($param) {

	$param['shipmodel_id'] = ''.intval($param['shipmodel_id']);
	
	$out = '';
	
	$path = my_get_blueprint_storage_dir().'/'.str_pad((''.$param['shipmodel_id']), 10, '0', STR_PAD_LEFT).'.png';
	
	// delete old
	if (is_file($path)) {
		$r = unlink($path);
		if (!$r) {
			$out .= outhtml_error("Ошибка при удалении файла! (".__FILE__." Line ".__LINE__.")");
			return $out;
		}
	}
		
	$qr = mydb_query("".
		" UPDATE shipmodel ".
		" SET shipmodel.has_blueprint = 'N' ".
		" WHERE shipmodel.shipmodel_id = '".$param['shipmodel_id']."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	
	if ($out == '') $out .= '<p style=" ">Изображение успешно удалено.</p>';
	
	return $out;
}


// =============================================================================
function outhtml_blueprint_card($param) {

	$out = '';
	
	if (!isset($param['shipmodel_id'])) {
		return outhtml_welcome_screen($param);
	}
	
	$c_result = '';
	if (isset($param['c'])) {
		if ($param['c'] == 'delete') {
			$c_result .= outhtml_try_delete_blueprint($param);
		}
		if ($param['c'] == 'upload') {
			$c_result .= outhtml_try_parse_blueprint($param);
		}
	}
	
	$modelnamelong = my_get_shipmodel_name_long($param['shipmodel_id']);

	$GLOBALS['pagetitle'] = 'Силуэт проекта '.$modelnamelong.' - '.$GLOBALS['pagetitle'];
	
	//
	
	$out .= '<div style=" float: left;  clear: none; width: 570px; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
		
		$out .= '<h1 style=" font-size: 20pt; padding-left: 18px; margin-bottom: 30px; ">Чертеж проекта  <span style="  ">'.$modelnamelong.'</span></h1>';
		
		//
		
		if ($c_result != '') {
			$messagecolor = '3f6b86';
			if (mb_strpos($c_result, 'успешно') === false) $messagecolor = 'a00000';
			$out .= '<div style=" margin-top: 30px; margin-bottom: 30px; padding-left: 18px; background-color: #'.$messagecolor.'; color: #ffffff; border: none; border-radius: 4px; -moz-border-radius: 4px;  padding: 5px 15px 5px 20px;">';
				$out .= $c_result;
			$out .= '</div>';
		}
		
		// status
		
		$path = my_get_shipmodel_blueprint($param['shipmodel_id']);
		
		if ($path === false) {
			$statuscolor = 'a0a0a0';
			$statusstr = 'не загружен';
			$uploadtext = 'Загрузить';
		} else {
			$statuscolor = '3f6b86';
			$statusstr = 'загружен';
			$uploadtext = 'Заменить';
		}

		
		// current image
		
		if ($path !== false) {
			$out .= '<div style=" margin-top: 30px; padding-left: 9px; background-color: #66737b; ">';
			// opacity: 0.5;
			$out .= '<img src="'.$path.'" style=" display: block; " />';
			$out .= '</div>';
		}
		
		
		// black bar
		$out .= '<div style=" background-color: #000000; min-height: 4px; ">';
		$out .= '</div>';

		// sea gray bar
		$out .= '<div style=" background-color: #66737b; min-height: 4px; padding: 10px 20px 10px 20px; ">';
		$out .= '<p style=" font-size: 11pt; color: #ffffff; ">';
		$out .= 'Чертеж проекта '.$modelnamelong;
		$out .= '</p>';
		$out .= '<p style=" font-size: 9pt; color: #b5b5b5; ">';
		$out .= 'Статус: '.$statusstr;
		$out .= '</p>';
		$out .= '</div>';
		
		
		//
		
		// upload
		
			$out .= '<div style=" padding-top: 8px; vertical-align: top; ">';
			
					$out .= '<table><tr><td style=" padding-right: 16px; ">';
					
						$link = '/admin/blueprint.php';
						$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';

						$out .= '<input type="hidden" name="shipmodel_id" value="'.$param['shipmodel_id'].'" />';
						$out .= '<input type="hidden" name="c" value="upload" />';
						$out .= '<input type="hidden" name="MAX_FILE_SIZE" value="'.my_get_max_picture_file_size().'" />';
					
						$out .= '<input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 240px; overflow: hidden; " size="10" name="upfile" type="file" value="" />';
					
					$out .= '</td><td style=" vertical-align: middle; padding-right: 16px; ">';
					
						$out .= '<button class="hoverwhiteborder" type="submit" name="button" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: top; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; ">'.$uploadtext.'</button>';
						
						$out .= '</form>';
						
					$out .= '</td><td style=" vertical-align: middle; padding-right: 16px; ">';
					
						if ($path !== false) {
					
							$link = '/admin/blueprint.php';
							$out .= '<form method="GET" enctype="multipart/form-data" action="'.$link.'">';
							$out .= '<input type="hidden" name="shipmodel_id" value="'.$param['shipmodel_id'].'" />';
							
							$out .= '<button class="hoverwhiteborder" type="submit" name="c"  value="delete" style=" background-color: #d88d88; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #532026; padding: 2px 12px 3px 12px; min-width: 130px; " >Удалить</button>';
							
							$out .= '</form>';
						
						}
					
					$out .= '</td></tr></table>';
				
				
			
			$out .= '</div>';
		
		// end upload
		
		
		// buttons
		
		/*
		
		if ($path !== false) {
		
			$out .= '<div style=" margin-top: 10px; ">';
			
				$out .= '<div style=" float: left; margin-right: 10px; ">';
				
					$out .= '<div style=" padding-top: 2px; vertical-align: top; ">';
					
						$link = '/admin/blueprint.php';
						$out .= '<form method="GET" enctype="multipart/form-data" action="'.$link.'">';
						$out .= '<input type="hidden" name="shipmodel_id" value="'.$param['shipmodel_id'].'" />';
						
						$out .= '<button class="hoverwhiteborder" type="submit" name="c"  value="delete" style=" background-color: #d88d88; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #532026; padding: 2px 12px 3px 12px; min-width: 130px; " >Удалить</button>';
					
					$out .= '</div>';
				
				$out .= '</div>';
				
				$out .= '<div style=" clear: left; "></div>';
			
			$out .= '</div>';
		
		}
		
		*/
		
		//
		
		$out .= '</div>';

		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		$out .= outhtml_blueprint_instruction($param);
		$out .= '</div>';


		//$out .= '<div style=" clear: both; ">';
		
		//$out .= '</div>';
		


		$out .= '<div style=" clear: both; min-height: 100px; ">';
		$out .= '</div>';
		
		//$out .= '</div>';
		
		
	
	//$out .= '</div>';
	
	return $out;
}


// =============================================================================
function outhtml_admin_blueprint($param) {
	
	$out = '';
	
	if (!am_i_admin()) {
		return outhtml_welcome_screen($param);
	}
	
	if (!isset($param['shipmodel_id'])) {
		return outhtml_welcome_screen($param);
	}
	if (!ctype_digit($param['shipmodel_id'])) {
		return outhtml_welcome_screen($param);
	}
	$param['shipmodel_id'] = ''.intval($param['shipmodel_id']);
	
	if (!isset($param['c'])) $param['c'] = '';
	
	//
	
	$q = " SELECT shipmodel.shipmodel_id ".
		" FROM shipmodel ".
		" WHERE ( shipmodel.shipmodel_id = '".$param['shipmodel_id']."' ) ".
		"";
	$qr = mydb_queryarray($q);

	if ($qr === false) {
		return false;
	}
	if (sizeof($qr) != 1) {
		return false;
	}
	
	$out .= outhtml_blueprint_card($param);

	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>