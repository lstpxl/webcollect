<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/refresh_downlink.php');


// =============================================================================
function outhtml_admin_downlink_total_process($param) {

	$qr = mydb_queryarray("".
		" SELECT item.item_id ".
		" FROM item ".
		" WHERE item.status = 'K' ".
		" ORDER BY item.item_id ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	$str = '';
	
	// downlink_to_item(4420);
	
	$startfrom = 0;
	$finishat = sizeof($qr);
	/*
	$startfrom = 8000;
	$finishat = $startfrom + 1000;
	if ($finishat > sizeof($qr)) $finishat = sizeof($qr);
	*/
	for ($i = $startfrom; $i < $finishat; $i++) {
		$str .= '<p>'.$qr[$i]['item_id'].'</p>';
		downlink_to_item($qr[$i]['item_id']);
	}

	$out = '';

	$out .= '<div id="batch_upload_status_div" style=" margin-left: 18px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; border: solid 1px #a0a0a0; min-height: 100px; font-size: 10pt; font-family: \'Courier New\', Courier, monospace; color: #606060; " >';
	
		$out .= $str;
			
		$out .= '<p>Обработано: <span style=" padding: 4px; background-color: #f0f0a0; color: #000000; font-weight: bold; ">'.sizeof($qr).'<span></p>';
	
	$out .= '</div>';
			
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_admin_downlink_total($param) {
	
	$out = '';
	
	if (!am_i_admin()) {
		return outhtml_welcome_screen($param);
	}
	
	$GLOBALS['pagetitle'] = 'Обновить данные предметов на основании данных структуры / '.$GLOBALS['pagetitle'];
	
	
	
	//
	
	$out .= '<div style=" background-color: #f8f8f8; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; line-height: 100%; ">Обновить данные предметов на основании данных структуры</h1>';


			// var_dump(my_elemtree_get_children_list_complete('ship', 2185));
			
			$out .= '<p style="padding-left: 18px;">Заполняет текстовые поля знаков на основании текстов, указанных для корабля, проекта, класса корабля.</p>';
			$out .= '<p style="padding-left: 18px;">Применять после обновления названия класса, модели, классификации НАТО.</p>';
			
			if (!isset($param['process'])) $param['process'] = '';
			if ($param['process'] != 'yes') $param['process'] = '';
			if ($param['process'] == 'yes') {
				$out .= outhtml_admin_downlink_total_process($param);
			}
			
			$out .= '<div style=" margin-left: 18px; margin-top: 15px; margin-bottom: 30px; vertical-align: top; ">';
			
				$link = '/admin/downlink_total.php';
				$out .= '<form method="GET" action="'.$link.'">';
			
					$out .= '<button class="hoverwhiteborder" type="none" name="process" value="yes" style="background-color: #d88d88; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #532026; padding: 2px 12px 3px 12px; min-width: 130px; ">Запуск</button>';
				
				$out .= '</form>';
				
			$out .= '</div>';
		
		$out .= '</div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>