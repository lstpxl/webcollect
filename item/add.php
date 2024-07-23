<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/item_image_parse.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');


// =============================================================================
function outhtml_item_add($param) {
	
	$out = '';
	
	//print 'zzz';
	if (!can_i_submit_item()) {
		return outhtml_welcome_screen($param);
	}


	$GLOBALS['pagetitle'] .= ' - Загрузка знака, шаг 1';
	$out = '';

	my_write_log('Add Item Start');

	$out .= '<div style="padding-left: 18px; margin-top: 30px; ">';

		$out .= '<div style=" float: left;  clear: none; width: 570px; padding: 20px 20px 10px 0px; color: #888888; line-height: 125%; ">';
		$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; ">Загрузка знака</h1>';
		$out .= '<h2 style=" font-size: 12pt; margin-bottom: 20px; color: #3f6b86; ">Шаг 1: Выберите изображение</h2>';
		$link = '/item/edit.php';
		$out .= '<form method="POST" enctype="multipart/form-data" action="'.$link.'">';

		$out .= '<input type="hidden" name="i" value="addnew" />';
		$out .= '<input type="hidden" name="MAX_FILE_SIZE" value="'.my_get_max_picture_file_size().'" />';
		
		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #888888; ">Основной вид:</div>';
		$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="upfile" type="file" value="" /></div>';

		$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="c" value="parseimage" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; ">Далее</button></div>';
		$out .= '</form>';
		
		$out .= '</div>';

		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
			$out .= outhtml_item_image_instruction();

		$out .= '</div>';

	$out .= '</div>';


	$out .= '<div style=" clear: both; min-height: 100px; ">';
	$out .= '</div>';

	return $out;
}

?>