 <?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');


// =============================================================================
function my_translit_str_v1($str) {

	$cyr  = array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у', 
				'ф','х','ц','ч','ш','щ','ъ', 'ы','ь', 'э', 'ю','я','А','Б','В','Г','Д','Е','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У',
				'Ф','Х','Ц','Ч','Ш','Щ','Ъ', 'Ы','Ь', 'Э', 'Ю','Я' );
	$lat = array( 'a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p','r','s','t','u',
				'f' ,'h' ,'ts' ,'ch','sh' ,'sht' ,'a', 'i', 'y', 'e' ,'yu' ,'ya','A','B','V','G','D','E','Zh',
				'Z','I','Y','K','L','M','N','O','P','R','S','T','U',
				'F' ,'H' ,'Ts' ,'Ch','Sh' ,'Sht' ,'A' ,'Y' ,'Yu' ,'Ya' );

	// $textcyr = str_replace($cyr, $lat, $textcyr);
	$textlat = str_replace($cyr, $lat, $str);
	
	return $textlat;
}


// =============================================================================
function my_translit_shipmodelmodel_str($str) {

	$str = my_translit_str_v1($str);
	$str = mb_strtolower($str);
	
	mb_regex_encoding('UTF-8');
	$str = mb_ereg_replace('[^a-z1-90_]', '_', $str);
	$str = mb_str_replace($str, '____', '_');
	$str = mb_str_replace($str, '__', '_');
	$str = trim($str);
	
	return $str;
}


// =============================================================================
function outhtml_admin_blueprint_export($param) {
	
	$out = '';
	
	if (!am_i_admin()) {
		return outhtml_welcome_screen($param);
	}
	
	$GLOBALS['pagetitle'] = 'Экспорт силуэтов / '.$GLOBALS['pagetitle'];
	
	//
	
	$q = " SELECT shipmodel.shipmodel_id ".
		" FROM shipmodel ".
		" WHERE ( shipmodel.has_blueprint = 'Y' ) ".
		" ORDER BY shipmodel.shipmodel_id ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	//
	
	$out .= '<div style=" background-color: #f8f8f8; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Экспорт силуэтов (/blueprintexport/*)</h1>';
			
			$out .= '<div id="batch_upload_status_div" style=" margin-left: 18px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; border: solid 1px #a0a0a0; min-height: 400px; font-size: 10pt; font-family: \'Courier New\', Courier, monospace; color: #606060; " >';
			
				$out .= '<p>Для экспорта: <span style=" padding: 4px; background-color: #f0f0a0; color: #000000; font-weight: bold; ">'.sizeof($qr).'<span></p>';

				for ($i = 0; $i < sizeof($qr); $i++) {
									
					$path = my_get_blueprint_storage_dir().'/'.str_pad((''.$qr[$i]['shipmodel_id']), 10, '0', STR_PAD_LEFT).'.png';
					
					//$out .= '<span>'.$path.'</span>';
					
					if (is_file($path)) {
					
					
						$modelname = my_get_shipmodel_name_alldetail($qr[$i]['shipmodel_id']);
						
						$info = my_translit_shipmodelmodel_str($modelname);
						
						$dest = my_get_blueprint_export_dir().'/'.str_pad((''.$qr[$i]['shipmodel_id']), 4, '0', STR_PAD_LEFT).'_'.$info.'.png';
						
						// $out .= '<p>'.$dest.'</p>';
						
						
						$result = copy($path, $dest);
						
						if ($result) {
							$out .= '<span>'.'ok'.'</span> ';
						} else {
							$out .= '<span style=" background-color: #ff0000; color: #ffffff; ">'.'error on id='.$qr[$i]['shipmodel_id'].'</span> ';
						}
						
					}
				}
			
			$out .= '</div>';
			
			/*
			$out .= '<div style=" margin-left: 18px; margin-top: 15px; margin-bottom: 30px; vertical-align: top; ">';
			
				$out .= '<button class="hoverwhiteborder" type="none" name="delete_button" style="background-color: #d88d88; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #532026; padding: 2px 12px 3px 12px; min-width: 130px; " onclick=" js_batch_upload_start(); return false; ">Запуск</button>';
				
			$out .= '</div>';
			*/
		
		$out .= '</div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>