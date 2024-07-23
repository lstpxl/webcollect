<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image_parse.php');

/*
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/ship_sel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_ship_factoryserialnum.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipmodel_sel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipclass_sel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/shipclass_upstore.php');
*/

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_classification.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_lettering.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_metal.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_enamel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_binding.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_patch.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_issuedate.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_batchsize.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_factory.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_itemset.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_itemsettitle.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_occasion.php');


require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_dimensions.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_notes.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_extlink.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_delete.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_undelete.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_approve.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_saveinput.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_iurel.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_item_status_action.php');

//require_once($_SERVER['DOCUMENT_ROOT'].'/fn/block_iurel_edit.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/block_item_upload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_correction.php');


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
function outhtml_item_add_step1($param) {

	$param['user_id'] = ''.intval($param['user_id']);

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
function outhtml_item_moderation_nav_block($param) {
	
	$out = '';
	
		$color = my_get_color_item_status('W');
		$next_id = get_next_item_to_moderate($param['i']);
	
		$out .= '<div style=" margin-top: 10px; background-color: #'.$color.'; padding: 20px; border-top: solid 4px #000000; ">';
			
			$out .= '<form method="GET" action="/item/edit.php">';
			
			$out .= '<input type="hidden" name="i" value="'.$next_id.'" />';
			
			$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
				
				$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Модерация</strong></p>';
				
				
				
				$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="approve_item_id" value="'.$param['i'].'" style="background-color: #5c8b24; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; " >Подтвердить и перейти к следующему</button></div>';
				
				$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="skip" value="y" style="background-color: #8f7547; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; ">Пропустить и перейти к следующему</button></div>';
				
				$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="reject_item_id" value="'.$param['i'].'" style="background-color: #904090; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; ">Отклонить и перейти к следующему</button></div>';
				
			$out .= '</div>';
		$out .= '</form>';
	$out .= '</div>';

	return $out;
}


// =============================================================================
function outhtml_item_images_block($param) {

	$out = '';
	
	// try parse new image
	
	$upload_result = '';
	if ($param['c'] == 'parseimage') {
		$upload_result .= outhtml_try_parse_item_image($param);
	}
	if ($param['c'] == 'deleteimage') {
		$upload_result .= outhtml_try_delete_item_image($param);
	}
	if ($param['c'] == 'makefirstimage') {
		$upload_result .= outhtml_try_makefirst_item_image($param);
	}
	
	$out .= '<div id="item_images_block" style=" margin-top: 30px; ">';
		
		$out .= '<h2 style=" font-size: 12pt; margin-bottom: 20px; color: #3f6b86; padding-left: 18px; ">Изображения</h2>';
		
		$out .= '<div style=" background-color: #ffffff; border: solid 1px #b0b0b0; margin-top: 20px; border-radius: 4px; -moz-border-radius: 4px;  margin-top: 5px; margin-right: 5px; padding: 5px 5px 5px 15px; ">';
		
		$out .= $upload_result;
		
		$piccount = my_get_item_picture_count($param['i']);
		
		/*
		if ($piccount > 0) {
			$qr = mydb_queryarray("".
				" SELECT item.item_id, ".
				" item.time_submit_start, ".
				" item.time_submit_finish ".
				" FROM item ".
				" WHERE item.item_id = '".$param['i']."' ".
				"");
			if ($qr === false) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (sizeof($qr) != 1) {
				my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
				return false;
			}
			if (date('Y', strtotime($qr[0]['time_submit_finish'])) < 2013) {
				$qru = mydb_query("".
					" UPDATE item ".
					" SET item.time_submit_finish = '".date('Y-m-d H:i:s')."' ".
					" WHERE item.item_id = '".$param['i']."' ". 
					"");
				if (!$qru) {
					my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
			}
		}
		*/
		
		//$out .= '<div style=" margin-top: 10px; ">';
		for ($c = 1; $c <= $piccount; $c++) {
		
			// bagde thumb div
			$out .= '<div style=" float: left; margin-right: 4px; margin-bottom: 20px; width: 188px; ">';

				// bagde image div
				$out .= '<div style=" width: 188px; height: 188px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$param['i'].'&n='.$c.'&s=m'.'&random='.rand (0, 999999).'\'); ">';
				
					$out .= '<div style=" position:relative; display: block; width: 186px; height: 186px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " >';
					$out .= '</div>';
				
				$out .= '</div>';
				
			

			/*
			// bagde thumb div
			$out .= '<div style=" float: left; margin-right: 8px; margin-bottom: 10px; width: 188px; ">';

			// bagde image div
			$out .= '<div style=" width: 188px; height: 188px; display: table-cell; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$param['i'].'&n='.$c.'&s=m\'); ">';
			// is not vertically aligned !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			// $out .= '<a href="#"><img src="/images/spacer.gif" width="96" height="96" /></a>';
			$out .= '<img src="/images/spacer.gif" width="186" height="186" style=" display: block; "/>';
			$out .= '</div>';
			//
			*/

			// bagde description div
			$out .= '<div style=" min-height: 10px;  padding: 3px 3px 1px 3px; background-color: #f8f8f8; border: solid 1px #f0f0f0; border-radius: 3px; -moz-border-radius: 3px; ">';
			
			
			if (am_i_superadmin()) {
			
				// window.open(url,'_blank');
				$href = '/item/fullscreenitemimage.php?i='.$param['i'].'&n='.$c.'&s=o&return=edit';
				$out .= '<div style=" float: right; ">';
				$out .= '<img src="/images/folder_picture.png" title="Скачать оригинал" onclick=" window.open (\''.$href.'\'); return false; " style=" padding-right: 4px; "/>';
				$out .= '</div>';

			}
			if ($piccount > 1) {
				$href = $_SERVER['PHP_SELF'].'?i='.$param['i'].'&c=deleteimage&n='.$c;
				$out .= '<div style=" float: right; ">';
				$out .= '<img src="/images/delete.png" title="Удалить" onclick=" window.location.href = \''.$href.'\'; " style=" padding-right: 4px; "/>';
				$out .= '</div>';

			}
			if ($c > 1) {
				$href = $_SERVER['PHP_SELF'].'?i='.$param['i'].'&c=makefirstimage&n='.$c;
				$out .= '<div style=" float: right; ">';
				$out .= '<img src="/images/award_star_gold_3.png" title="Сделать основным" onclick=" window.location.href = \''.$href.'\'; " style=" padding-right: 4px; "/>';
				$out .= '</div>';
			}
			$out .= '<div style=" clear: both; "></div>';
			$out .= '</div>';
			
			//

			$out .= '</div>';
			//
		}
		$out .= '<div style=" clear: both; "></div>';
		
		// add_image_v2
		$out .= '<div style=" display: block; float:right; margin-bottom: 5px; margin-right: 10px; ">';
			$out .= '<form method="GET" action="/item/add_image_v2.php">';
				$out .= '<div style=" margin-top: 0px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" name="i" value="'.$param['i'].'" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; ">Добавить изображение</button></div>';
			$out .= '</form>';
		$out .= '</div>';
		
		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '</div>';
	
	$out .= '</div>';
	
	return $out;
}


// =============================================================================
function outhtml_item_card($param) {

	$out = '';
	
	if (!isset($param['i'])) {
		return outhtml_welcome_screen($param);
	}

	$GLOBALS['pagetitle'] = 'Знак #'.$param['i'].' редактирование';
	
	$out .= outhtml_script_item_lettering();
	$out .= outhtml_script_item_dimensions();
	$out .= outhtml_script_item_metal();
	$out .= outhtml_script_item_enamel();
	$out .= outhtml_script_item_binding();
	$out .= outhtml_script_item_patch();
	$out .= outhtml_script_item_issuedate();
	$out .= outhtml_script_item_batchsize();
	$out .= outhtml_script_item_factory();
	$out .= outhtml_script_item_itemset();
	$out .= outhtml_script_item_itemsettitle();
	$out .= outhtml_script_item_occasion();
	
	$out .= outhtml_script_item_notes();
	$out .= outhtml_script_item_extlink();
	
	$out .= outhtml_script_item_delete();
	$out .= outhtml_script_item_undelete();
	$out .= outhtml_script_item_approve();
	$out .= outhtml_script_item_saveinput();
	
	//
	
	$out .= '<div style=" height: 30px; "></div>';
	
	$out .= '<input type="hidden" id="edited_item_id" value="'.$param['i'].'" />';

	$out .= '<div style=" float: left;  clear: none; width: 560px; padding: 20px 10px 10px 0px; color: #888888; line-height: 125%; ">';
	/* background-color: #f88888; */
		
		$out .= '<h1 style=" float: left; font-size: 20pt; margin-top: 5px; margin-bottom: 20px; padding-left: 18px; ">Редактирование знака <span style=" color: #b0b0b0; ">#'.$param['i'].'</span></h1>';
		
		$out .= '<form method="GET" action="/item/view.php">';
			$out .= '<button class="lightbluegradient hoverlightblueborder" type="submit" name="i" value="'.$param['i'].'" style=" float: right; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; margin-top: 0px; min-width: 100px; padding: 4px 0px 4px 0px; margin-left: 20px; ">Просмотр</button>';
		$out .= '</form>';
		
		$out .= '<div style=" clear: both; "></div>';
		
		// status
		$status = my_get_item_status($param['i']);
		$statusstr = my_decode_item_status($status);
		$statuscolor = my_get_color_item_status($status);
		$out .= '<div style=" margin-top: 7px; background-color: #'.$statuscolor.'; min-height: 4px; padding: 4px 20px 4px 20px; ">';
		$out .= '<p style=" font-size: 12px; color: #ffffff; ">';
		$out .= 'Статус: '.$statusstr;
		$out .= '</p>';
		$out .= '</div>';
		//

			
		$out .= outhtml_item_images_block($param);
		
		
		$out .= '<h2 style=" margin-top: 20px; font-size: 12pt; margin-bottom: 20px; color: #3f6b86; padding-left: 18px; ">Классификация</h2>';
		

		$out .= '<div style=" margin-top: 20px; ">';
		
			$out .= outhtml_item_classification_div(array('i' => $param['i']));
		
		$out .= '</div>';
		
		//
		
		$out .= '<div style=" background-color: #ffffff; border: solid 1px #b0b0b0; margin-top: 20px; border-radius: 4px; -moz-border-radius: 4px;  margin-top: 5px; margin-right: 5px; padding: 5px 5px 15px 15px; ">';
			$out .= '<div style=" margin-top: 10px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Надписи на знаке (через пробел):</div>';
			$out .= outhtml_item_lettering_div(array('i' => $param['i']));
		$out .= '</div>';
		
		//
		
		$out .= '<table><tr><td style=" vertical-align: top; ">';
		
		// ттх
		
		$out .= '<h2 style=" font-size: 12pt; margin-top: 20px; margin-bottom: 20px; color: #3f6b86; padding-left: 18px; ">Технические характеристики</h2>';
		
		$out .= '<div style=" background-color: #ffffff; border: solid 1px #b0b0b0; margin-top: 20px; border-radius: 4px; -moz-border-radius: 4px;  margin-top: 5px; margin-right: 5px; padding: 5px 5px 15px 15px; ">';
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Габаритные размеры:</div>';
		$out .= outhtml_item_dimensions_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Металл:</div>';
		$out .= outhtml_item_metal_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Эмаль:</div>';
		$out .= outhtml_item_enamel_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Крепление:</div>';
		$out .= outhtml_item_binding_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Накладка:</div>';
		$out .= outhtml_item_patch_div(array('i' => $param['i']));
		
		$out .= '</div>';
		
		//
		
		$out .= '</td><td>';
		
		// выпуск
		
		$out .= '<h2 style=" font-size: 12pt;  margin-top: 20px; margin-bottom: 20px; color: #3f6b86; padding-left: 18px; ">Характеристики выпуска</h2>';
		
		$out .= '<div style=" background-color: #ffffff; border: solid 1px #b0b0b0; margin-top: 20px; border-radius: 4px; -moz-border-radius: 4px;  margin-top: 5px; margin-right: 5px; padding: 5px 5px 15px 15px; ">';
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Год выпуска:</div>';
		$out .= outhtml_item_issuedate_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Тираж:</div>';
		$out .= outhtml_item_batchsize_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Изготовитель:</div>';
		$out .= outhtml_item_factory_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Серия знаков:</div>';
		
		$out .= outhtml_item_itemset_div(array('i' => $param['i']));
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 0px; font-size: 10pt; color: #888888; ">';
		$out .= outhtml_item_itemsettitle_div(array('i' => $param['i']));
		$out .= '&nbsp;Заглавный знак серии</div>';
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Приурочен/посвящен:</div>';
		$out .= outhtml_item_occasion_div(array('i' => $param['i']));
		
		$out .= '</div>';
		
		$out .= '</td></tr></table>';
		
		//
		
		//$out .= '</div>';
		
		
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Примечания:</div>';
		$out .= outhtml_item_notes_div(array('i' => $param['i']));
		
		
		/*
		$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="step" value="2" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; ">Сохранить</button></div>';
		*/
		
		//$out .= '</div>';
		
		$out .= '<div style=" margin-top: 10px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Ссылка на внешний ресурс:</div>';
		$out .= outhtml_item_extlink_div(array('i' => $param['i']));
		// $out .= '</div>';
		
		
		$out .= '<div style=" ">';
		$out .= '<div style=" float: left; margin-right: 10px; ">';
		$out .= outhtml_item_saveinput_div($param);
		$out .= '</div>';
		$out .= '<div style=" float: left; margin-right: 10px; ">';
		$out .= outhtml_item_approve_div($param);
		$out .= '</div>';
		/*
		$out .= '<div style=" float: left; margin-right: 10px; ">';
		$out .= outhtml_item_delete_div($param['i']);
		$out .= '</div>';
		*/
		$out .= '<div style=" float: left; margin-right: 10px; ">';
		$out .= outhtml_item_undelete_div($param['i']);
		$out .= '</div>';
		$out .= '<div style=" clear: left; "></div>';
		$out .= '</div>';
		
		
		// $out .= '<h2 style=" margin-top: 20px; font-size: 12pt; margin-bottom: 20px; color: #3f6b86; padding-left: 18px; ">Пользовательский комментарий?</h2>';
		
		$str = outhtml_item_image_filename_original($param['i']);
		if ($str != '') {
			$out .= '<div style=" margin-right: 10px; margin-left: 18px; font-size: 10pt; margin-top: 20px; ">';
				$out .= $str;
			$out .= '</div>';
		}
		
		if ($GLOBALS['user_id'] == 2) {
			$out .= '<div style=" margin-right: 10px; margin-left: 18px; font-size: 10pt; margin-top: 20px; ">';
				$out .= 'sortfield_c = '.get_item_sortfield_c($param['i']);
			$out .= '</div>';
		}
		
		//$out .= outhtml_iurel_block_div($param);
			
		
			
	
	$out .= '</div>';
	
	// SIDEBAR

	$out .= '<div style=" float: right; clear: none; width: 371px; ">';
		
		$out .= outhtml_script_form_item_status_action();
		if (am_i_admin()) {
			$out .= outhtml_form_item_status_action_div(array('i' => $param['i']));
		}
		
		if ($status == 'W') {
			// $out .= outhtml_item_moderation_nav_block($param);
		}
		$out .= outhtml_item_edit_instruction($param);
		
		// 
		$out .= '<div style=" margin-top: 30px; " id="iurelouter">';
	
			$out .= '<h2 style=" font-size: 12pt;  margin-top: 20px; margin-bottom: 20px; color: #3f6b86; padding-left: 18px; ">Моя коллекция</h2>';
		
			$out .= outhtml_form_iurel_div($param);
		
		$out .= '</div>';
		
		//
		
		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '<div style=" margin-top: 30px; ">';
			$out .= outhtml_item_view_advanced_info($param);
		$out .= '</div>';
		
		$out .= '<div style=" clear: both; "></div>';
		
		if (am_i_registered_user()) {
			$out .= '<div style=" margin-top: 20px; ">';
				$out .= outhtml_form_correction_div($param);
			$out .= '</div>';
		}
		
		
		
		// $out .= outhtml_item_image_instruction($param);
		// $out .= outhtml_item_upload_sidebar_block();
		
	$out .= '</div>';

	// SIDEBAR END

	//$out .= '<div style=" clear: both; ">';
	
	//$out .= '</div>';
	
	/*
	$result = myemail_send();
	if (!$result) {
	$out .= 'boo!';
	}
	*/
	

	$out .= '<div style=" clear: both; height: 100px; "></div>';
	
	//
	
	
	
	return $out;
}


// =============================================================================
function outhtml_item_edit($param) {

	if (!am_i_registered_user()) return false;
	
	$out = '';
	
	if (!isset($param['i'])) {
		return outhtml_welcome_screen($param);
	}
	
	if (!isset($param['c'])) $param['c'] = '';
	
	if (isset($param['approve_item_id'])) {
		$param['approve_item_id'] = ''.intval($param['approve_item_id']);
		$d = array('i' => $param['approve_item_id']);
		try_update_item_approve($d);
	}
	
	if (isset($param['reject_item_id'])) {
		$param['reject_item_id'] = ''.intval($param['reject_item_id']);
		$d = array('i' => $param['reject_item_id']);
		try_update_item_reject($d);
	}
	
	// try add new item
	
	if ($param['i'] == 'addnew') {
	
		if (!can_i_submit_item()) {
			return outhtml_welcome_screen($param);
		}

		$image_filename_original = '';
		if (isset($_FILES["upfile"]["name"])) {
			$s = $_FILES["upfile"]["name"];
			$s = trim($s);
			$s = mb_strtolower($s);
			$s = str_replace('ё', 'е', $s);
			mb_regex_encoding("UTF-8");
			$s = mb_ereg_replace('[^а-яa-z1-90]', ' ', $s);
			$s = str_replace('    ', ' ', $s);
			$s = str_replace('  ', ' ', $s);
			$s = trim($s);
			$image_filename_original = $s;
		}
		
		$qr = mydb_query("".
			" INSERT INTO item ".
			" SET item.status = 'I', ".
			" item.submitter_id = '".$GLOBALS['user_id']."', ".
			" item.time_submit_start = '".date('Y-m-d H:i:s')."' ".
			"");
		if (!$qr) {
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$item_id = mydb_insert_id();
		if ($item_id == 0) {
			my_write_log('Error condition user_id='.$GLOBALS['user_id'].'');
			my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		
		//
		
		// prepared query
		$a = array();
		$a[] = $image_filename_original;
		$a[] = $item_id;
		$q = "".
			" UPDATE item ".
			" SET item.image_filename_original = ? ".
			" WHERE item.item_id = ? ". 
			";";
		
		$t = 'si';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		// end of prepared query
		
		//
		
		$param['i'] = ''.$item_id;
		
	} else {
	
		if (!ctype_digit($param['i'])) {
			return outhtml_welcome_screen($param);
		}
		
		
		if (!can_i_edit_item($param['i'])) {
			return outhtml_welcome_screen($param);
		}
		
	}
	
	//
	
	if (!isset($param['i'])) $noitem = true;
	if (!ctype_digit($param['i'])) $noitem = true;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) $noitem = true;
	
	if ($noitem) {
		return outhtml_noitem_form($param);
		$param['i'] = 0;
	}
	
	//
	
	$GLOBALS['pagetitle'] .= ' - Редактирование знака';
	$out .= outhtml_item_card($param);
	
	//

	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>