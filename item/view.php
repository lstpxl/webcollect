<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_item_viewhead.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_delete.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_approve.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_iurel.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/form_correction.php');



// =============================================================================
function outhtml_register_warning() {

	$out = '';

	$out .= '<div style=" width: 340px; margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #ffe49f; border: solid 1px #ffe49f; border-radius: 3px; color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing: 2px; text-transform: uppercase; ">';

				$out .= '<div style=" padding: 20px 20px 20px 20px;  text-align: left; ">';
				
					$link = '/register.php';
					$out .= '<form method="GET" action="'.$link.'">';
					
						$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; margin-top: 10px; font-size: 9pt; color: #808080; white-space: normal; line-height: 150%; "><strong>Зарегистрируйтесь</strong> и получите больше возможностей.</div>';
						
						$out .= '<div style=" margin-top: 10px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Зарегистрироваться</button></div>';

					
					$out .= '</form>';

				$out .= '</div>';

			$out .= '</div>';
			
			
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_view_right_buttons($param) {

	$out = '';
	
	$out .= '<div style=" float: right; margin-top: 0px; clear: none; width: 370px; ">';

	if (can_i_edit_item($param['i'])) {
		$out .= '<div style=" float: left; margin-right: 5px; ">';
			$out .= '<form method="GET" action="/item/edit.php">';
				$out .= '<div style=" margin-top: 0px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" name="i" value="'.$param['i'].'" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Редактировать</button></div>';
			$out .= '</form>';
		$out .= '</div>';
	}
	
	if (can_i_approve_item($param['i'])) {
		$out .= '<div style=" float: left; margin-right: 5px; ">';
			$out .= '<form method="GET" action="/item/edit.php">';
				$out .= '<div style=" margin-top: 0px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" name="i" value="'.$param['i'].'" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Подтвердить</button></div>';
			$out .= '</form>';
		$out .= '</div>';
	}
	
		$out .= '<div style=" clear: both; "></div>';
	
	$out .= '</div>';
					
	return $out;
}



// =============================================================================
function outhtml_item_view_form($param) {

	$out = '';
	
	if (!isset($param['i'])) {
		return outhtml_welcome_screen($param);
	}
	
	$param['i'] = ''.intval($param['i']);
	
	$info = get_item_view_info($param['i']);
	$item = $info;

	$GLOBALS['pagetitle'] = 'Знак #'.$param['i'].' - '.$GLOBALS['pagetitle'];

	$out .= outhtml_script_form_item_viewhead();
	
	$out .= '<div style=" float: left; width: 530px; padding: 0px 0px 10px 20px; color: #808080; line-height: 125%; ">';
	
		$out .= '<div style=" margin-top: 24px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';
			$out .= outhtml_form_item_viewhead_div(array('i' => $param['i'], 'n' => '1'));
		$out .= '</div>';
		
		
		$out .= '<div style=" clear: both; "></div>';
		
		// -------------

		$out .= '<div style=" margin-top: 10px;  ">';
		
		
			// корабль
			
			$str = htmlspecialchars($info['ship_str'], ENT_QUOTES);
			$str_facnum = $info['ship_factoryserialnum_str'];
			if ($info['ship_factoryserialnum_str'] != '') $str .= ' <span class="shipserialnum" style=" font-size: 11px; ">(зав. '.htmlspecialchars($info['ship_factoryserialnum_str'], ENT_QUOTES).')</span>';
			// if ($str != '') $str = ' <span class="shipprefix">корабль</span> '.$str;
			if ($str == '') $str = 'Неизвестный корабль';
			if ($info['natoc_str'] != '') {
				$natoc = htmlspecialchars($info['natoc_str'], ENT_QUOTES);
				$natoc = '['.$natoc.']';
				$natoc = ' <span class="shipserialnum" style=" font-size: 11px; ">'.$natoc.'</span>';
			} else {
				$natoc = '';
			}
		
			$out .= '<div style=" box-shadow: 0 1px 2px gray; background-color: #b6bec3; border: solid 1px #b6bec3; border-radius: 3px; margin-bottom: 10px; padding: 0px; ">';
			
				$out .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 10px 0px 7px 0px; ">';
					$out .= htmlspecialchars($info['shipmodelclass_str'], ENT_QUOTES);
				$out .= '</h2>';
				
				$out .= '<div style=" margin-top: 0; margin-bottom: 4px; height: 0px; border-top: 1px solid #828b94; border-bottom: 1px solid #d3d6d7; "></div>';
	
				$out .= '<h2 class="shipname" style=" text-align: center; padding: 0px 0px 0px 0px; margin: 10px 0px 7px 0px; ">';
					$out .= $str;
				$out .= '</h2>';
				
				$out .= '<div style=" margin-top: 0; margin-bottom: 4px; height: 0px; border-top: 1px solid #828b94; border-bottom: 1px solid #d3d6d7; "></div>';
				
				$out .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 10px 0px 7px 0px; ">';

					$out .= htmlspecialchars($info['modelname'], ENT_QUOTES).$natoc;

				$out .= '</h2>';
			
			$out .= '</div>';

			
			// серия
			
			if ($info['itemset_id'] > 0) {
			
				$out .= '<form method="GET" action="/item/itemset.php">';
				
					$out .= '<button class="itemsetlink hoverlightblueborder" type="submit" name="itemset_id" value="'.$info['itemset_id'].'" style=" ">';
						$out .= '<span style=" color: #bfc5c9; margin-left: 10px; ">'.'Серия '.'</span>';
						$out .= ''.htmlspecialchars($info['itemset_str'], ENT_QUOTES).'';
					$out .= '</button>';
				
				$out .= '</form>';
			
			}

			
			// приурочен
			
			if ($info['occasion_id'] > 0) {
				$out .= '<div style=" margin-top: 4px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #f2f4f5; border: solid 1px #f2f4f5; border-radius: 3px; padding: 2px; ">';

					$out .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 1px 0px 1px 0px; ">';
						$out .= '<span style=" color: #606060; margin-left: 10px; ">'.'Приурочен: '.'</span>';
						$out .= '<span style=" color: #303030; ">'.htmlspecialchars($info['occasion_str'], ENT_QUOTES).'</span>';
					$out .= '</h2>';
				
				$out .= '</div>';
			}

			
			// характеристики
		
			$inner = '';

			if (($item['width'] > 0) || ($item['height'] > 0)) {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= '<span style=" color: #606060; margin-left: 10px; ">'.'Габаритные размеры: '.'</span>';
					$inner .= htmlspecialchars((''.$item['width'].' x '.$item['height'].' мм'), ENT_QUOTES);
				$inner .= '</h2>';
			}

			if ($item['metal_id'] > 0) {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= htmlspecialchars(my_get_metal_name($item['metal_id']), ENT_QUOTES);
				$inner .= '</h2>';
			}
			
			if ($item['enamel_id'] > 0) {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= htmlspecialchars(my_get_enamel_name($item['enamel_id']).' эмаль', ENT_QUOTES);
				$inner .= '</h2>';
			}
			
			if ($item['binding_id'] > 0) {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= htmlspecialchars(my_get_binding_name($item['binding_id']), ENT_QUOTES);
				$inner .= '</h2>';
			}
			
			if ($item['has_patch'] == 'Y') {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= 'накладка';
				$inner .= '</h2>';
			}
			
			if ($item['issuedate'] != 0) {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= 'выпущен в '.htmlspecialchars($item['issuedate'], ENT_QUOTES).' году';
				$inner .= '</h2>';
			}
			
			if ($item['batchsize'] != 0) {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= 'тираж '.htmlspecialchars($item['batchsize'], ENT_QUOTES).' шт';
				$inner .= '</h2>';
			}
			
			if ($item['factory_str'] != '') {
				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
					$inner .= '<span style=" color: #606060; margin-left: 10px; ">'.'Изготовитель: '.'</span>';
					$inner .= htmlspecialchars($item['factory_str'], ENT_QUOTES);
				$inner .= '</h2>';
			}
			
			
			// надписи
			
			if ($item['lettering'] != '') {
			
				$inner .= '<div style=" margin-top: 8px; margin-bottom: 4px; height: 0px; border-top: 1px solid #c5c9cd; border-bottom: 1px solid #fafafa; "></div>';

				$inner .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; color: #909090; ">';
					$inner .= 'Надписи на знаке:';
				$inner .= '</h2>';
				
				$inner .= '<p class="grayeleg" style=" text-align: center; margin: 2px; color: #505050; ">';
					$inner .= htmlspecialchars($info['lettering'], ENT_QUOTES);
				$inner .= '</p>';
				
			}
			
			if ($inner != '') {
			
				$out .= '<div style=" margin: 8px 0px 8px 0px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ebeff1; border: solid 1px #ebeff1; border-radius: 3px; padding: 0px; ">';
				
					$out .= $inner;
			
				$out .= '</div>';
				
			}


			// примечания
			
			if ($item['notes'] != '') {
			
				$out .= '<div style=" margin-top: 4px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ebeff1; border: solid 1px #ebeff1; border-radius: 3px; ">';

					$out .= '<h2 class="shipname" style=" font-size: 11px; text-align: center; padding: 0px 0px 0px 0px; margin: 2px; ">';
						$out .= '<span style=" color: #909090; ">'.'Примечания:'.'</span>';
					$out .= '</h2>';
					
					$out .= '<p class="grayeleg" style=" text-align: center; margin: 2px; color: #505050; ">';
						$out .= htmlspecialchars($info['notes'], ENT_QUOTES);
					$out .= '</p>';

				$out .= '</div>';
			}
			
			// внешняя ссылка
			
			if ($info['extlink'] != '') {
			
				$out .= '<div style=" margin-top: 4px; box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ebeff1; border: solid 1px #ebeff1; border-radius: 3px; ">';

					$out .= '<p class="grayeleg" style=" text-align: center; margin: 2px; color: #505050; ">';
						$out .= '<a target="_new" href="'.htmlspecialchars($info['extlink'], ENT_QUOTES).'" >';
							$out .= 'Информация на внешнем сайте';
						$out .= '</a>';
					$out .= '</p>';

				$out .= '</div>';
			}

			//
			
		$out .= '</div>';
			
	$out .= '</div>';
		
	
	// справа
	
	if ($GLOBALS['is_registered_user']) {
		$out .= '<div style=" float: right; margin-top: 20px; clear: none; ">';
			$out .= outhtml_form_iurel_div($param);
		$out .= '</div>';
	}

	/*
	$out .= '<div style=" float: right; margin-top: 20px; clear: none; ">';
		$out .= outhtml_item_view_right_buttons($param);
	$out .= '</div>';
	*/

	if ($GLOBALS['is_registered_user']) {
		$out .= '<div style=" float: right; margin-top: 40px; clear: none; ">';
			$out .= outhtml_item_view_advanced_info($param);
		$out .= '</div>';
	}
	
	if (am_i_registered_user()) {
		$out .= '<div style=" float: right; margin-top: 20px; clear: none; ">';
			$out .= outhtml_form_correction_div($param);
		$out .= '</div>';
	}
	
	if (!$GLOBALS['is_registered_user']) {
		$out .= '<div style=" float: right; margin-top: 40px; clear: none; ">';
			$out .= outhtml_register_warning();
		$out .= '</div>';
	}

	
	//

	$out .= '<div style=" clear: both; min-height: 50px; "></div>';
	
	return $out;
}


// =============================================================================
function outhtml_item_view($param) {
	
	$out = '';
	
	$noitem = false;
	
	if (!isset($param['i'])) $noitem = true;
	if (!ctype_digit($param['i'])) $noitem = true;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) $noitem = true;
	
	if ($noitem) {
		return outhtml_noitem_form($param);
		$param['i'] = 0;
		// return outhtml_welcome_screen($param);
	}
	
	if (!can_i_view_item($param['i'])) {
		return outhtml_welcome_screen($param);
	}

	$out .= outhtml_item_view_form($param);

	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>