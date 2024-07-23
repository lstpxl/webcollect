<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image.php');


// =============================================================================
function outhtml_item_add_image_v2_step_select($param) {

	$out = '';

	$out .= '<div style=" float: left; width: 530px; padding: 0px 0px 10px 20px; color: #808080; line-height: 125%; ">';
	
		$out .= '<div style=" margin-top: 24px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';
			
			$out .= '<div style=" position: relative; width: 508px; height: 100px; padding: 10px; background-repeat: no-repeat; background-position: 220px 15px; background-image: url(\'/item/image.php?i='.$param['i'].'&n='.'1'.'&s=s\'); ">';
			
				$out .= '<div style=" float: left; width: 220px; ">';
				
					// title
					$out .= '<h1 class="grayemb" style=" margin-top: 5px; margin-left: 5px;  margin-bottom: 10px; font-size: 18px; ">';
						$out .= 'Знак';
						$out .= ' <span style=" color: #b0b0b0; ">#'.$param['i'].'</span>';
					$out .= '</h1>';

				$out .= '</div>';
			
				$out .= '<div style=" clear: both; "></div>';
			
			$out .= '</div>';
		
		$out .= '</div>';
		
		
		$out .= '<h1 class="grayemb" style=" margin-top: 20px; margin-bottom: 30px; font-size: 18px; ">';
			$out .= 'Добавление изображения';
		$out .= '</h1>';

		$link = '/item/add_image_v2.php';
		$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';

			$out .= '<input type="hidden" name="i" value="'.$param['i'].'" />';
			$out .= '<input type="hidden" name="MAX_FILE_SIZE" value="'.my_get_max_picture_file_size().'" />';
			
			$out .= '<div style=" float: left; width: 410px; ">';
			
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Выберите файл</div>';
				
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="upfile" type="file" value="" /></div>';
				
			$out .= '</div>';

			$out .= '<button class="lightbluegradient hoverlightblueborder" type="submit" name="step" value="process" style=" float: right; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; margin-top: 20px; min-width: 100px; padding: 4px 0px 4px 0px; ">Далее</button>';

			
			$out .= '<div style=" clear: both; "></div>';
			
		$out .= '</form>';
		
	$out .= '</div>';
	
	
	$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		$out .= outhtml_item_image_instruction();
	$out .= '</div>';


	$out .= '<div style=" clear: both; min-height: 100px; "></div>';

	return $out;
}


// =============================================================================
function outhtml_item_add_image_v2_step_process($param) {

	$out = '';

	$p = array();
	$p['i'] = $param['i'];
	$result = try_add_item_image(&$p);
	
	if (!$result) {
		$out .= '<div style=" float: left; width: 530px; padding: 0px 0px 10px 20px; color: #808080; line-height: 125%; ">';
			$out .= '<h1 class="grayemb" style=" margin-top: 20px; margin-bottom: 30px; font-size: 18px; color: #8f2e2e; ">';
				$out .= 'Ошибка загрузки';
			$out .= '</h1>';
			$out .= '<p class="grayemb" style=" margin-top: 20px; margin-bottom: 30px; font-size: 18px; color: #808080; ">';
				$out .= $p['error_message'];
			$out .= '</p>';
		$out .= '</div>';
		$out .= outhtml_item_add_image_v2_step_select($param);
		return $out;
	}

	$out = '';

	$out .= '<div style=" float: left; width: 530px; padding: 0px 0px 10px 20px; color: #808080; line-height: 125%; ">';
	
		$out .= '<div style=" margin-top: 24px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';
			
			$out .= '<div style=" position: relative; width: 508px; height: 190px; padding: 10px; background-repeat: no-repeat; background-position: 175px 15px; background-image: url(\'/item/image.php?i='.$param['i'].'&n='.$p['image_number'].'&s=m\'); ">';
			
				$out .= '<div style=" float: left; width: 220px; ">';
				
					// title
					$out .= '<h1 class="grayemb" style=" margin-top: 5px; margin-left: 5px;  margin-bottom: 10px; font-size: 18px; ">';
						$out .= 'Знак';
						$out .= ' <span style=" color: #b0b0b0; ">#'.$param['i'].'</span>';
					$out .= '</h1>';

				$out .= '</div>';
			
				$out .= '<div style=" clear: both; "></div>';
			
			$out .= '</div>';
		
		$out .= '</div>';
		
		
		$out .= '<h1 class="grayemb" style=" margin-top: 20px; margin-bottom: 20px; font-size: 18px; text-align: center; ">';
			$out .= 'Изображение загружено';
		$out .= '</h1>';
		
		$out .= '<div style=" margin: 0 auto; width: 280px; ">';

			$link = '/item/add_image_v2.php';
			$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';

				$out .= '<input type="hidden" name="i" value="'.$param['i'].'" />';
				$out .= '<input type="hidden" name="n" value="'.$p['image_number'].'" />';
				

				$out .= '<button class="redgradient hoverlightblueborder" type="submit" name="step" value="undo" style=" float: right; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; margin-top: 20px; min-width: 100px; padding: 4px 0px 4px 0px; ">Отменить</button>';
				
			$out .= '</form>';
			
			$link = '/item/edit.php';
			$out .= '<form method="GET" action="'.$link.'">';
				
				$out .= '<button class="lightbluegradient hoverlightblueborder" type="submit" name="i" value="'.$param['i'].'" style=" float: left; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; margin-top: 20px; min-width: 100px; padding: 4px 0px 4px 0px; margin-right: 20px; ">ОК</button>';

				
				$out .= '<div style=" clear: both; "></div>';
				
			$out .= '</form>';
		
		$out .= '</div>';
		
	$out .= '</div>';
	
	
	$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		$out .= outhtml_item_image_instruction();
	$out .= '</div>';


	$out .= '<div style=" clear: both; min-height: 100px; "></div>';

	return $out;
}


// =============================================================================
function outhtml_item_add_image_v2_step_undo($param) {

	if (!isset($param['n'])) return false;
	if (!ctype_digit($param['n'])) return false;
	$param['n'] = ''.intval($param['n']);
	
	$result = try_remove_item_image($param['i'], $param['n']);
	
	$out = '';

	$out .= '<div style=" float: left; width: 530px; padding: 0px 0px 10px 20px; color: #808080; line-height: 125%; ">';
	
		$out .= '<div style=" margin-top: 24px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';
			
			$out .= '<div style=" position: relative; width: 508px; height: 190px; padding: 10px; background-repeat: no-repeat; background-position: 175px 15px; background-image: url(\'/item/image.php?i='.$param['i'].'&n='.$p['image_number'].'&s=m\'); ">';
			
				$out .= '<div style=" float: left; width: 220px; ">';
				
					// title
					$out .= '<h1 class="grayemb" style=" margin-top: 5px; margin-left: 5px;  margin-bottom: 10px; font-size: 18px; ">';
						$out .= 'Знак';
						$out .= ' <span style=" color: #b0b0b0; ">#'.$param['i'].'</span>';
					$out .= '</h1>';

				$out .= '</div>';
			
				$out .= '<div style=" clear: both; "></div>';
			
			$out .= '</div>';
		
		$out .= '</div>';
		
		
		$out .= '<h1 class="grayemb" style=" margin-top: 20px; margin-bottom: 20px; font-size: 18px; text-align: center; ">';
			$out .= 'Загрузка отменена';
		$out .= '</h1>';
		
		$out .= '<div style=" ">';

			$link = '/item/edit.php';
			$out .= '<form method="GET" action="'.$link.'">';

				$out .= '<input type="hidden" name="i" value="'.$param['i'].'" />';

				// margin-top: 20px;
				$out .= '<button class="lightbluegradient hoverlightblueborder" type="submit" name="step" value="process" style=" display: block; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; margin-top: 30px; color: #606060; width: 100px; padding: 4px 0px 4px 0px; margin: 0 auto; ">ОК</button>';

				
				$out .= '<div style=" clear: both; "></div>';
				
			$out .= '</form>';
		
		$out .= '</div>';
		
	$out .= '</div>';
	
	
	$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		$out .= outhtml_item_image_instruction();
	$out .= '</div>';


	$out .= '<div style=" clear: both; min-height: 100px; "></div>';

	return $out;
}


// =============================================================================
function outhtml_item_add_image_v2($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	if (!can_i_edit_item($param['i'])) return false;

	$GLOBALS['pagetitle'] = 'Знак #'.$param['i'].' - Загрузка изображения - '.$GLOBALS['pagetitle'];
	
	if (!isset($param['step'])) $param['step'] = 'select';
	
	if ($param['step'] == 'select') {
		return outhtml_item_add_image_v2_step_select($param);
	} elseif ($param['step'] == 'process') {
		return outhtml_item_add_image_v2_step_process($param);
	} elseif ($param['step'] == 'undo') {
		return outhtml_item_add_image_v2_step_undo($param);
	} else {
		return false;
	}

	return false;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>