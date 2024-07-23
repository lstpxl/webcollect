<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_gotit_picto.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_sellit_picto.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_wantit_picto.php');


// =============================================================================
function outhtml_item_image_instruction() {
	
	$out = '';
	
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

	return $out;
}


// =============================================================================
function outhtml_uni_paginator($pagesize, $sidearea, $jsfunc, $curpage, $total) {

	$out = '';
	
	if ($total <= $pagesize) {
		return '';
	}
	
	$out .= '<div style=" min-height: 10px; padding: 10px 20px 10px 20px; ">';

	$pages = ceil($total / $pagesize);
	
	$firstvisible = $curpage - $sidearea;
	if ($firstvisible < 0) $firstvisible = 0;
	
	$lastvisible = $curpage + $sidearea;
	if ($lastvisible > ($pages - 1)) $lastvisible = ($pages - 1);
	
	if ($firstvisible > 0) {
		$out .= '<a style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #c0c0c0; color: #303030; float: left; font-size: 12px; border: 1px solid #808080; border-radius: 2px 2px 2px 2px;" href="#" onclick="'.$jsfunc.'(\''.'0'.'\'); return false" ontap="'.$jsfunc.'(\''.'0'.'\'); return false" >';
		$out .= '1';
		$out .= '</a> ';
	}
	
	if ($curpage > 0) {
		$out .= '<a style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #c0c0c0; color: #303030; float: left; font-size: 12px; border: 1px solid #808080; border-radius: 2px 2px 2px 2px; width: 20px; text-align: center; " href="#" onclick="'.$jsfunc.'(\''.($curpage - 1).'\'); return false" ontap="'.$jsfunc.'(\''.($curpage - 1).'\'); return false" >';
		$out .= '<';
		$out .= '</a> ';
	} else {
		$out .= '<p style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #f0f0f0; color: #e8e8e8; float: left; font-size: 12px; border: 1px solid #e8e8e8; border-radius: 2px 2px 2px 2px; width: 20px; text-align: center; " >';
		$out .= '<';
		$out .= '</p> ';
	}
	
	if ($firstvisible > 0) {
		$out .= '<span style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; color: #303030; float: left; font-size: 12px; width: 12px; text-align: center; " >';
		$out .= '...';
		$out .= '</span> ';
	}
	
	for ($c = $firstvisible; $c <= $lastvisible; $c++) {
		$local = '';
		if ($c == $curpage) {
			$local .= '<div style=" cursor: pointer; margin-right: 4px; float: left; padding: 1px 4px 6px 4px; background-color: #303030; color: #f0f0f0; font-size: 13px; border: 1px solid #000000; border-radius: 2px 2px 2px 2px; ">';
			$local .= ''.($c + 1).'';
			$local .= '</div> ';
		} else {
			$local .= '<a style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #e0e0e0; color: #303030; float: left; font-size: 12px; border: 1px solid #808080; border-radius: 2px 2px 2px 2px;" href="#" onmouseover="void(0)" onmouseout="void(0)" onclick="'.$jsfunc.'(\''.$c.'\'); return false;" ontap="'.$jsfunc.'(\''.$c.'\'); return false;" >';
			$local .= ''.($c + 1);
			$local .= '</a> ';
		}
		$out .= $local;
	}
	
	if ($lastvisible < ($pages - 1)) {
		$out .= '<span style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; color: #303030; float: left; font-size: 12px; width: 12px; text-align: center; " >';
		$out .= '...';
		$out .= '</span> ';
	}
	
	if ($curpage < ($pages - 1)) {
		$out .= '<a style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #c0c0c0; color: #303030; float: left; font-size: 12px; border: 1px solid #808080; border-radius: 2px 2px 2px 2px; width: 20px; text-align: center; " href="#" onclick="'.$jsfunc.'(\''.($curpage + 1).'\'); return false" ontap="'.$jsfunc.'(\''.($curpage + 1).'\'); return false" >';
		$out .= '>';
		$out .= '</a> ';
	} else {
		$out .= '<p style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #f0f0f0; color: #e8e8e8; float: left; font-size: 12px; border: 1px solid #e8e8e8; border-radius: 2px 2px 2px 2px; width: 20px; text-align: center; " >';
		$out .= '>';
		$out .= '</p> ';
	}
	
	if ($lastvisible < ($pages - 1)) {
		$out .= '<a style=" cursor: pointer; margin-right: 4px; padding: 1px 4px 1px 4px; background-color: #c0c0c0; color: #303030; float: left; font-size: 12px; border: 1px solid #808080; border-radius: 2px 2px 2px 2px;" href="#" onclick="'.$jsfunc.'(\''.($pages - 1).'\'); return false" ontap="'.$jsfunc.'(\''.($pages - 1).'\'); return false" >';
		$out .= ''.$pages;
		$out .= '</a> ';
	}
	
	$out .= '<div style=" clear: both; "></div>';
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_uni_paginator_v2($pagesize, $sidearea, $jsfunc, $curpage, $total) {

	$out = '';
	
	if ($total <= $pagesize) {
		return '';
	}
	
	$out .= '<div class="paginator" >';

	$pages = ceil($total / $pagesize);
	
	$firstvisible = $curpage - $sidearea;
	if ($firstvisible < 0) $firstvisible = 0;
	
	$lastvisible = $curpage + $sidearea;
	if ($lastvisible > ($pages - 1)) $lastvisible = ($pages - 1);
	
	if ($firstvisible > 0) {
		$out .= '<div class="btn active " onclick="'.$jsfunc.'(\''.'0'.'\'); return false" >';
			$out .= '1';
		$out .= '</div> ';
	}
	
	if ($curpage > 0) {
		$out .= '<div class="btn active stepleft " onclick="'.$jsfunc.'(\''.($curpage - 1).'\'); " >';
		// $out .= '<';
		$out .= '</div> ';
	} else {
		$out .= '<div class="btn disabled stepleft " >';
		// $out .= '<';
		$out .= '</div> ';
	}
	
	if ($firstvisible > 0) {
		$out .= '<div class="dots" >';
		$out .= '</div> ';
	}
	
	for ($c = $firstvisible; $c <= $lastvisible; $c++) {
		$local = '';
		if ($c == $curpage) {
			$local .= '<div class="btn current ">';
				$local .= ''.($c + 1).'';
			$local .= '</div> ';
		} else {
			$local .= '<div class="btn active " onclick=" '.$jsfunc.'(\''.$c.'\'); return false; " >';
				$local .= ''.($c + 1);
			$local .= '</div> ';
		}
		$out .= $local;
	}
	
	if ($lastvisible < ($pages - 1)) {
		$out .= '<div class="dots" >';
		// $out .= '...';
		$out .= '</div> ';
	}
	
	if ($curpage < ($pages - 1)) {
		$out .= '<div class="btn active  stepright " onclick="'.$jsfunc.'(\''.($curpage + 1).'\'); " >';
		//$out .= '>';
		$out .= '</div> ';
	} else {
		$out .= '<div class="btn disabled stepright " >';
		//$out .= '>';
		$out .= '</div> ';
	}
	
	if ($lastvisible < ($pages - 1)) {
		$out .= '<div class="btn active " onclick="'.$jsfunc.'(\''.($pages - 1).'\'); " >';
			$out .= ''.$pages;
		$out .= '</div> ';
	}
	
	$out .= '<div style=" clear: both; "></div>';
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_uni_paginator_mini($pagesize, $sidearea, $jsfunc, $curpage, $total) {

	$out = '';
	
	if ($total <= $pagesize) {
		return '';
	}
	
	$out .= '<div class="paginatormini" >';

	$pages = ceil($total / $pagesize);
	
	$firstvisible = $curpage - $sidearea;
	if ($firstvisible < 0) $firstvisible = 0;
	
	$lastvisible = $curpage + $sidearea;
	if ($lastvisible > ($pages - 1)) $lastvisible = ($pages - 1);
	
	if ($firstvisible > 0) {
		$out .= '<div class="btn active " onclick="'.$jsfunc.'(\''.'0'.'\'); return false" >';
			$out .= '1';
		$out .= '</div> ';
	}
	
	if ($curpage > 0) {
		$out .= '<div class="btn active stepleft " onclick="'.$jsfunc.'(\''.($curpage - 1).'\'); " >';
		// $out .= '<';
		$out .= '</div> ';
	} else {
		$out .= '<div class="btn disabled stepleft " >';
		// $out .= '<';
		$out .= '</div> ';
	}
	
	if ($firstvisible > 0) {
		$out .= '<div class="dots" >';
		$out .= '</div> ';
	}
	
	for ($c = $firstvisible; $c <= $lastvisible; $c++) {
		$local = '';
		if ($c == $curpage) {
			$local .= '<div class="btn current ">';
				$local .= ''.($c + 1).'';
			$local .= '</div> ';
		} else {
			$local .= '<div class="btn active " onclick=" '.$jsfunc.'(\''.$c.'\'); return false; " >';
				$local .= ''.($c + 1);
			$local .= '</div> ';
		}
		$out .= $local;
	}
	
	if ($lastvisible < ($pages - 1)) {
		$out .= '<div class="dots" >';
		// $out .= '...';
		$out .= '</div> ';
	}
	
	if ($curpage < ($pages - 1)) {
		$out .= '<div class="btn active  stepright " onclick="'.$jsfunc.'(\''.($curpage + 1).'\'); " >';
		//$out .= '>';
		$out .= '</div> ';
	} else {
		$out .= '<div class="btn disabled stepright " >';
		//$out .= '>';
		$out .= '</div> ';
	}
	
	if ($lastvisible < ($pages - 1)) {
		$out .= '<div class="btn active " onclick="'.$jsfunc.'(\''.($pages - 1).'\'); " >';
			$out .= ''.$pages;
		$out .= '</div> ';
	}
	
	$out .= '<div style=" clear: both; "></div>';
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function get_item_inlist_color($item_id) {

	$item_id = ''.intval($item_id);

	if (!$GLOBALS['is_registered_user']) return 'ffffff';

	$q = " SELECT iurel.* ".
		" FROM iurel ".
		" WHERE iurel.user_id = '".$GLOBALS['user_id']."' ".
		" AND iurel.item_id = '".$item_id."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) > 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (sizeof($qr) == 1) {
		if (($qr[0]['gotit'] == 'Y') && ($qr[0]['sellit'] == 'N')) return 'ffd200';
		if (($qr[0]['gotit'] == 'Y') && ($qr[0]['sellit'] == 'Y')) return '82e9ff';
		if (($qr[0]['gotit'] == 'N') && ($qr[0]['wantit'] == 'Y')) return 'fab1fd';
	}
	
	return 'ffffff';

	// yellow ffd200
	// purple f523fd, fab1fd
	// cyan 82e9ff
}



// =============================================================================
function outhtml_item_inlist($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, item.ship_str, item.notes, item.sortfield_c, ".
		" item.shipmodel_id, item.ship_id, item.shipmodelclass_id  ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	// bagde thumb div
	$out .= '<div style=" float: left; margin-right: 3px; margin-bottom: 20px; width: 188px; ">';
	
	if (am_i_admin()) {
		$zoomins = ' onmouseover=" js_popup_zoom1_show(event, '.$item_id.'); return false; "  onmousemove =" js_popup_zoom1_move(event); return false; " onmouseout=" js_popup_zoom1_hide(event); return false; " ';
		$zoomins = '';
	} else {
		$zoomins = '';
	}

	// bagde image div
	$out .= '<div id="div'.$item_id.'" style=" width: 188px; height: 188px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$item_id.'&n=1&s=m\'); "  >';
	
	// inner transparent layer v2
	if (can_i_view_item($item_id)) {
		$href = '/item/view.php?i='.$item_id;
		$out .= '<a id="spacer'.$item_id.'" style=" position:relative; display: block; width: 186px; height: 186px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " href="'.$href.'" '.$zoomins.' >';
	} else {
		$out .= '<div style=" position:relative; display: block; width: 186px; height: 186px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " >';
	}
	
	
	
	
	// top overlay elements
	
	// item number
	if ($GLOBALS['is_registered_user']) {
		$out .= '<div style=" position: absolute; left: 0px; top: 3px; padding: 1px 3px 2px 3px; height: 15px; color: #808080; background-color: #ffffff; opacity: 0.7; border-radius: 3px; -moz-border-radius: 3px; font-size: 8pt; ">';
			$out .= '#'.$item_id;
			if ($GLOBALS['user_id'] == 2) {
				$out .= ' '.$qr[0]['sortfield_c'];
			}
		$out .= '</div>';
	}
	
	// unclassified
	if (am_i_moderator()) {
		$unclass = (($qr[0]['shipmodel_id'] == 0) || ($qr[0]['ship_id'] == 0) || ($qr[0]['shipmodelclass_id'] == 0) || (mb_strpos($qr[0]['sortfield_c'], 'z')));
		if ($unclass) {
			$out .= '<div style=" position: absolute; left: 2px; top: 20px; padding: 0px 3px 2px 3px; width: 16px; height: 16px; color: #808080; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\''.'/images/orange_i.png'.'\'); opacity: 1.0; border-radius: 3px; -moz-border-radius: 3px; font-size: 8pt; " title="Неидентифицирован" >';
				//$out .= '?';
			$out .= '</div>';
		}
	}
	
	// gotit picto
	if ($GLOBALS['is_registered_user']) {
		$out .= '<div style=" position: absolute; left: 167px; top: 0px;  padding: 2px 1px 1px 1px; width: 18px; height: 19px; opacity: 0.7; ">';
			$out .= outhtml_iurel_gotit_picto_div(array('i' => $item_id));
		$out .= '</div>';
	}
	
	// wantit picto
	if ($GLOBALS['is_registered_user']) {
		$out .= '<div style=" position: absolute; left: 0px; bottom: 0px; overflow: hidden; padding: 1px 1px 0px 3px; width: 18px; height: 17px; opacity: 0.7; ">';
			$out .= outhtml_iurel_wantit_picto_div(array('i' => $item_id));
		$out .= '</div>';
	}
	
	// sellit picto
	if ($GLOBALS['is_registered_user']) {
		$out .= '<div style=" position: absolute; right: 0px; bottom: 0px; overflow: hidden; padding: 1px 1px 0px 3px; width: 18px; height: 17px; opacity: 0.7; ">';
			$out .= outhtml_iurel_sellit_picto_div(array('i' => $item_id));
		$out .= '</div>';
	}
	
	// end inner transparent layer v2
	if (can_i_view_item($item_id)) {
		$out .= '</a>'; 
	} else {
		$out .= '</div>'; 
	}
	
	// inner transparent layer
	/*
	$out .= '<a href="'.$href.'">';
		$out .= '<img src="/images/spacer.gif" width="186" height="186" style=" display: block; "/></a>';
	
	*/
	//
	
	$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';
	

	$out .= outhtml_item_inlist_label_div(array('i' => $item_id));

	//
	/*
	$gotit = (my_iurel_get($item_id, 'gotit') == 'Y');
	$color = 'ffffff';
	if ($gotit) $color = 'afead0';
	*/

	/*
	$color = get_item_inlist_color($item_id);

	// bagde description div
	$out .= '<div style=" min-height: 10px;  padding: 6px 20px 6px 20px; background-color: #'.$color.'; border: solid 1px #f0f0f0; border-radius: 3px; -moz-border-radius: 3px; ">';
	$out .= '<p style=" font-size: 10pt; color: #66737b; overflow: hidden; width: 166; white-space: nowrap; ">';
	//$out .= 'Фрунзе';
	$out .= $qr[0]['ship_str'];
	$out .= '</p>';
	$out .= '<p style=" font-size: 8.5pt; color: #66737b; overflow: hidden; width: 166; white-space: nowrap; ">';
	//$out .= 'т.м., накл, г.э.';
	$out .= $qr[0]['shipmodel_str'];
	$out .= '</p>';
	if ($GLOBALS['is_registered_user']) {
		$out .= '<div style=" font-size: 8.5pt; color: #66737b; overflow: hidden; width: 166; white-space: nowrap; ">';
	//$out .= 'т.м., накл, г.э.';
		$out .= outhtml_iurel_gotit_picto_div(array('i' => $item_id));
		// $out .= '<img src="/images/star_gray.png" style=" margin-right: 4px; " />';
		$out .= '<img src="/images/bino_gray.png" style=" margin-right: 4px; " />';
		$out .= '<img src="/images/arrows_gray.png" style=" margin-right: 4px; " />';
		$out .= '</div>';
	}
	$out .= '</div>';
	*/
	//

	$out .= '</div>';
	// end thumb

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_item_inlist_small_moderation($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, item.ship_str, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	// bagde thumb div
	$out .= '<div style=" float: left; margin-right: 4px; margin-bottom: 20px; width: 98px; ">';
		
		/*
		if (am_i_admin()) {
			$zoomins = ' onmouseover=" js_popup_zoom1_show(event, '.$item_id.'); return false; "  onmousemove =" js_popup_zoom1_move(event); return false; " onmouseout=" js_popup_zoom1_hide(event); return false; " ';
			$zoomins = '';
		} else {
			$zoomins = '';
		}
		*/

		// bagde image div
		$out .= '<div style=" width: 98px; height: 98px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$item_id.'&n=1&s=s\'); "  >';
			
			// inner transparent layer v2
			if (am_i_moderator() || am_i_lim_moderator()) {
				$href = '/item/edit.php?i='.$item_id;
				$out .= '<a style=" position:relative; display: block; width: 96px; height: 96px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " href="'.$href.'" '.$zoomins.' >';
			} else {
				$out .= '<div style=" position:relative; display: block; width: 96px; height: 96px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " '.$zoomins.' >';
			}
					
				// top overlay elements
				
				// item number
				if ($GLOBALS['is_registered_user']) {
					$out .= '<div style=" position: absolute; left: 0px; top: 0px; padding: 0px 3px 2px 3px; height: 15px; color: #808080; background-color: #ffffff; opacity: 0.7; border-radius: 3px; -moz-border-radius: 3px; font-size: 8pt; ">';
						$out .= '#'.$item_id;
					$out .= '</div>';
				}
				
			
			// end inner transparent layer v2
			if (am_i_moderator() || am_i_lim_moderator()) {
				$out .= '</a>'; 
			} else {
				$out .= '</div>'; 
			}
			
			$out .= '<div style=" clear: both; "></div>';

		$out .= '</div>';
		
		//

		$out .= '<div style=" width: 98px; overflow: hidden; min-height: 24px; background-color: #ffffff; border: solid 1px #f0f0f0; border-radius: 3px; -moz-border-radius: 3px; ">';
		
			$out .= '<div style=" min-height: 24px; ">';
		
				$out .= '<div style=" padding: 3px 3px 0px 3px; ">';

					$out .= '<p style=" font-size: 9pt; color: #66737b; width: 90px; overflow: hidden; white-space: nowrap; ">';
						$out .= my_get_user_name($qr[0]['submitter_id']);
					$out .= '</p>';

					$out .= '<p style=" font-size: 8pt; color: #66737b; width: 90px; overflow: hidden; white-space: nowrap; ">';
						$t = strtotime($qr[0]['time_submit_finish']);
						if (date('Y', $t) < 2013) {
							$str = '&mdash;';
						} else {
							$str = date('d/m/Y H:i', $t);
						}
						$out .= ''.$str;
					$out .= '</p>';


					$out .= '<div style=" clear: both; "></div>';
					
				$out .= '</div>';
			
			$out .= '</div>';

		$out .= '</div>';

		//

	$out .= '</div>';
	// end thumb

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_item_inlist_smallth($item_id, $todisplay) {

	$item_id = ''.intval($item_id);

	
	$ad = explode(' ', $todisplay);

	$edit = can_i_edit_item($item_id);
	$view = can_i_view_item($item_id);
	$link = '';
	if ($edit || $view) {
		if ($edit) {
			$link = '/item/edit.php';
		} else {
			$link = '/item/view.php';
		}
	}

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, item.ship_str, ".
		" item.shipmodel_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	// bagde thumb div
	$out .= '<div style=" float: left; margin-right: 4px; margin-bottom: 20px; width: 98px; ">';
		
		/*
		if (am_i_admin()) {
			$zoomins = ' onmouseover=" js_popup_zoom1_show(event, '.$item_id.'); return false; "  onmousemove =" js_popup_zoom1_move(event); return false; " onmouseout=" js_popup_zoom1_hide(event); return false; " ';
			$zoomins = '';
		} else {
			$zoomins = '';
		}
		*/

		// bagde image div
		$out .= '<div style=" width: 98px; height: 98px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$item_id.'&n=1&s=s\'); "  >';
			
			// inner transparent layer v2
			if ($link != '') {
				$href = $link.'?i='.$item_id;
				$out .= '<a style=" position:relative; display: block; width: 96px; height: 96px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " href="'.$href.'" '.$zoomins.' >';
			} else {
				$out .= '<div style=" position:relative; display: block; width: 96px; height: 96px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " '.$zoomins.' >';
			}
					
				// top overlay elements
				
				// item number
				if ($GLOBALS['is_registered_user']) {
					$out .= '<div style=" position: absolute; left: 0px; top: 0px; padding: 0px 3px 2px 3px; height: 15px; color: #808080; background-color: #ffffff; opacity: 0.7; border-radius: 3px; -moz-border-radius: 3px; font-size: 8pt; ">';
						$out .= '#'.$item_id;
					$out .= '</div>';
				}
				
			
			// end inner transparent layer v2
			if ($link != '') {
				$out .= '</a>'; 
			} else {
				$out .= '</div>'; 
			}
			
			$out .= '<div style=" clear: both; "></div>';

		$out .= '</div>';
		
		//

		$out .= '<div style=" width: 98px; overflow: hidden; min-height: 24px; background-color: #ffffff; border: solid 1px #f0f0f0; border-radius: 3px; -moz-border-radius: 3px; ">';
		
			$out .= '<div style=" min-height: 24px; ">';
		
				$out .= '<div style=" padding: 2px 2px 0px 2px; ">';
				
					

					for ($i = 0; $i < sizeof($ad); $i++) {

						if ($ad[$i] == 'submitter') {
							$str = my_get_user_name($qr[0]['submitter_id']);
							$out .= '<p style=" font-size: 9px; color: #66737b; width: 90px; overflow: hidden; white-space: nowrap; " title="'.htmlspecialchars($str, ENT_QUOTES).'">';
								$out .= ''.htmlspecialchars($str, ENT_QUOTES);
							$out .= '</p>';
						}

						if ($ad[$i] == 'submittime') {
							$t = strtotime($qr[0]['time_submit_finish']);
							if (date('Y', $t) < 2013) {
								$str = '&mdash;';
							} else {
								$str = date('d/m/Y H:i', $t).' ';
							}
								
							$out .= '<p style=" font-size: 8px; color: #66737b; width: 90px; overflow: hidden; white-space: nowrap; " title="'.htmlspecialchars($str, ENT_QUOTES).'">';
								
								$out .= ''.htmlspecialchars($str, ENT_QUOTES).' ';
							$out .= '</p>';
						}
						
						if ($ad[$i] == 'ship') {
							$str = get_item_ship_name($item_id);
							$out .= '<p style=" font-size: 10px; color: #66737b; width: 90px; overflow: hidden; white-space: nowrap; " title="'.htmlspecialchars($str, ENT_QUOTES).'">';
								$out .= htmlspecialchars($str, ENT_QUOTES).' ';
							$out .= '</p>';
						}
						
						if ($ad[$i] == 'model') {
							$str = get_item_shipmodel_name_full($qr[0]['shipmodel_id'], $qr[0]['shipmodel_str']);
							$out .= '<p style=" font-size: 9px; color: #66737b; width: 90px; overflow: hidden; white-space: nowrap; " title="'.htmlspecialchars($str, ENT_QUOTES).'">';
								$out .= htmlspecialchars($str, ENT_QUOTES).' ';
							$out .= '</p>';
						}
						
						if ($ad[$i] == 'status') {
							$code = my_get_item_status($item_id);
							$str = my_decode_item_status($code);
							$out .= '<p style=" font-size: 9px; color: #66737b; width: 90px; overflow: hidden; white-space: nowrap; " title="'.htmlspecialchars($str, ENT_QUOTES).'">';
								$out .= htmlspecialchars($str, ENT_QUOTES).' ';
							$out .= '</p>';
						}

					}

					$out .= '<div style=" clear: both; "></div>';
					
				$out .= '</div>';
			
			$out .= '</div>';

		$out .= '</div>';

		//

	$out .= '</div>';
	// end thumb

	return $out.PHP_EOL;
}



// =============================================================================
function get_item_list_head_shiptype_str($shipmodel_id, $shipmodel_str, $shipmodelclass_str, $hasmodel) {

	$shipmodel_id = ''.intval($shipmodel_id);
	
	if ($shipmodel_str == '') {
		$shipmodel_str = 'Проект неидентифицирован';
	} else {
		$shipmodel_str = $shipmodel_str;
	}
	
	if (!$hasmodel) $shipmodel_str = '';

	if ($hasmodel) {
		if ($shipmodel_id > 0) {
			
			$str = my_get_shipmodel_name_long($shipmodel_id);

			if ($str != '') $shipmodel_str = $str;

			$shipmodelclass_id = my_get_shipmodel_class($shipmodel_id);
			$shipmodelclass_str = my_get_shipclass_name($shipmodelclass_id);
		}
	}

	$out = '';

	$out .= $shipmodel_str.' '.$shipmodelclass_str;

	return $out;
}


// =============================================================================
function outhtml_item_list_head_shiptype($shipmodel_id, $shipmodel_str, $shipmodelclass_str, $hasship, $hasmodel) {

	$shipmodel_id = ''.intval($shipmodel_id);
	
	if ($shipmodel_str == '') {
		$shipmodel_str = 'Проект неидентифицирован';
	} else {
		$shipmodel_str = $shipmodel_str;
	}

	if (!$hasmodel) {
		$shipmodel_str = '';
		$shipmodel_id = 0;
	}

	if ($shipmodel_id > 0) {

		// $p1 = get_item_shipmodel_name($list[$i]['item_id'], 1);
		// $p2 = get_item_shipmodel_name($list[$i]['item_id'], 2);
		
		$str = my_get_shipmodel_name_long($shipmodel_id);

		if ($str != '') $shipmodel_str = $str;

		$shipmodelclass_id = my_get_shipmodel_class($shipmodel_id);
		$shipmodelclass_str = my_get_shipclass_name($shipmodelclass_id);
	}

	$out = '';
	
	// ship type/project div
	//  background-color: #f8f8f8;
	$out .= '<div style=" clear: both; margin-left: 18px; margin-right: 18px; margin-top: 30px; overflow: hidden; background-color: #66737b; box-shadow: 0 1px 2px gray; border-radius: 3px; " >';
	
	// image schematic
	// 
	if ($shipmodel_id > 0) {
	
		$picturesrc = my_get_shipmodel_blueprint($shipmodel_id);
		
		if ($picturesrc != false) {
			$out .= '<div style=" margin-top: 15px; padding-left: 15px; padding-right: 30px; text-align: center; " >';
				$out .= '<img src="'.$picturesrc.'" style=" display: block; margin: 0 auto; " />';
			$out .= '</div>';
		
			// margin-left: 18px; margin-right: 18px;
			// box-shadow: inset 0 1px 0 #e4e9ec;
			$out .= '<div style=" margin-top: 0; margin-bottom: 4px; height: 0px; border-top: 1px solid #485057; border-bottom: 1px solid #b6babd; "></div>';
		} else {
		
			$picturesrc = '/images/lifebelt.png';
		
			$out .= '<div style=" margin-top: 15px; padding-left: 15px; padding-right: 15px; text-align: center; " >';
				$out .= '<img src="'.$picturesrc.'" title="Помогите нам. Отправьте изображение чертежа этого проекта." style=" float: right; cursor: pointer; " onclick=" window.location.href = \'/item/load_blueprint.php?shipmodel_id='.$shipmodel_id.'\'; " />';
				$out .= '<div style=" clear: both; " ></div>';
			$out .= '</div>';
		
			// margin-left: 18px; margin-right: 18px;
			// box-shadow: inset 0 1px 0 #e4e9ec;
			$out .= '<div style=" margin-top: 0; margin-bottom: 4px; height: 0px; border-top: 1px solid #485057; border-bottom: 1px solid #b6babd; "></div>';
		
			// $out .= '<div style=" margin-top: 15px; "><img src="/images/lifebelt.png" style=" display: block; margin: 0 auto; " /></div>';
		
			/*
			$out .= '<div style=" margin-top: 15px; padding-left: 15px; padding-right: 30px; text-align: center; " >';
				$out .= '<img src="/images/lifebelt.png" style=" display: block; margin: 0 auto; " />';
			$out .= '</div>';
			*/
			
		}
	}


	if ($hasmodel) {
		$out .= '<h2 class="modelname" style="  margin-left: 18px; padding: 10px 20px 2px 0px;  ">';
			$out .= $shipmodel_str;
		$out .= '</h2>';
	}
	
	$out .= '<h2 class="modelclass" style="  margin-left: 18px; padding: 10px 20px 2px 0px;  ">';
		$out .= $shipmodelclass_str;
	$out .= '</h2>';
	


	/*
	// black bar
	$out .= '<div style=" background-color: #000000; min-height: 4px; ">';
	$out .= '</div>';

	// sea gray bar
	$out .= '<div style=" background-color: #66737b; min-height: 4px; padding: 10px 20px 10px 20px; margin-bottom: 10px; ">';
	$out .= '<p style=" font-size: 11pt; color: #ffffff; ">';
	$out .= $shipmodel_str;
	$out .= '</p>';
	$out .= '<p style=" font-size: 9pt; color: #b5b5b5; ">';
	$out .= $shipmodelclass_str;
	$out .= '</p>';
	$out .= '</div>';
	*/
	
	$out .= '</div>';
	
	
	
	$out .= '<div style=" clear: both; "></div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_list_head_ship($str, $detail='') {

	$out = '';
	
	$out .= '<div style=" clear: both; "></div>';
	
	// $out .= '<div style=" margin-top: 0; margin-bottom: 4px; height: 0px; border-top: 1px solid #7d8a90; border-bottom: 1px solid #ffffff; margin-left: 18px; margin-right: 18px; "></div>';
	
	$out .= '<div style=" margin-left: 18px; margin-right: 18px; margin-top: 16px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #b6bec3; border: solid 1px #b6bec3; border-radius: 3px; ">';
	
		$out .= '<h2 class="shipname" style=" text-align: center; padding: 0px 0px 0px 0px; margin: 10px 0px 7px 0px; ">';
			$out .= $str;
		$out .= '</h2>';
		
		$out .= '<h2 class="shipname shipheaddetail" style=" text-align: center; padding: 0px 0px 0px 0px; margin: 3px 0px 7px 0px; font-size: 9px; color: #576369; ">';
			$out .= $detail;
		$out .= '</h2>';
	
	$out .= '</div>';
	
	/*
	$out .= '<div style=" margin-left: 18px; margin-right: 18px; margin-top: 24px; margin-bottom: 24px;  box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';

		$out .= '<div style=" padding: 10px 10px 10px 10px; color: #a0a0a0; font-size: 12pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing:2px; text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); text-align: center; ">';
			
			$out .= '<span style=" color: #607090; font-size: 12pt; text-transform: uppercase; ">'.$str.'</span>';

		$out .= '</div>';

	$out .= '</div>';
	*/
	
	// $out .= '<div style=" margin-left: 18px; margin-right: 18px; margin-top: 0; margin-bottom: 4px; height: 1px; border-top: 1px solid #b6bec3; box-shadow: inset 0 1px 0 white; "></div>';
	
	/*
	$out .= '<div style=" float: left; margin-left: 18px; margin-bottom: 20px; margin-top: 20px; padding: 10px 20px 2px 0px; color: #607090; font-size: 14pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing: 2px;  text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); ">';
		$out .= $str;
	$out .= '</div>';
	*/
	
	/*
	$out .= '<div style=" clear: left; overflow: hidden; margin-top: 10px; margin-bottom: 10px; background-color: #3f6b86; min-height: 4px; padding: 10px 20px 10px 20px; ">';
	$out .= '<p style=" font-size: 10pt; color: #ffffff; ">';
	$out .= $str;
	$out .= '</p>';
	$out .= '</div>';
	*/
	
	$out .= '<div style=" clear: both; "></div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_list_head_alpha($str) {

	$out = '';

	$str = mb_strtoupper($str);
	
	$out .= '<div style=" clear: left; "></div>';
	
	// float: left;
	$out .= '<div style="  margin-left: 18px; margin-right: 18px; margin-top: 30px;  background-color: #66737b; box-shadow: 0 1px 2px gray; border-radius: 3px; " >';
	
	$out .= '<h2 class="alpha" style=" margin-left: 18px; padding-top: 20px; padding-bottom: 10px; min-height: 50px; ">';
		$out .= $str;
	$out .= '</h2>';
	
	$out .= '</div>';
	
	$out .= '<div style=" clear: left; "></div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_item_list_head_itemset($str) {

	$out = '';

	$str = mb_strtoupper($str);
	
	$out .= '<div style=" clear: left; "></div>';
	
	// float: left;
	$out .= '<div style="  margin-left: 18px; margin-right: 18px; margin-top: 30px;  background-color: #66737b; box-shadow: 0 1px 2px gray; border-radius: 3px; " >';
	
	$out .= '<h2 class="itemset" style=" margin-left: 18px; padding-top: 20px; min-height: 36px; ">';
		$out .= $str;
	$out .= '</h2>';
	
	$out .= '</div>';
	
	$out .= '<div style=" clear: left; "></div>';
	
	return $out.PHP_EOL;
}




// =============================================================================
function outhtml_item_image_filename_original($item_id) {

	$item_id = ''.intval($item_id);

	$qr = mydb_queryarray("".
		" SELECT item.image_filename_original ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$str = $qr[0]['image_filename_original'];
	
	if ($str == '') return '';
	
	$out = '';
	$out .= '<div style=" ">';
		$out .= 'Имя исходного файла: ';
		$out .= '<span style=" font-weight: bold">'.$str.'</span>';
	$out .= '</div>';

	return $out;
}


// =============================================================================
function outhtml_switch_i1($position=false, $text='выкл', $color, $onclickstr) {
	
	$out = '';
	
	$radius = 6;
	$textwidth = 25;
	
	$out .= '<div class="hoverlightblueborder" style=" display: block; float: left; background-color: #'.$color.'; font-size: 11px; border-radius: '.($radius + 6).'px; -moz-border-radius: '.($radius + 6).'px;  margin: 1px; cursor: pointer; box-shadow: inset 0 1px 2px rgba(0,0,0,0.4); " onclick="'.$onclickstr.'" >';
	
		$out .= '<div style=" display: block; float: '.($position?'right':'left').'; background-color: #ffffff; border-radius: '.($radius).'px; -moz-border-radius: '.($radius).'px;  margin: 3px; width: '.($radius * 2 - 2).'px; height: '.($radius * 2 - 2).'px; cursor: pointer; border: solid 1px #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.6); " onclick="'.$onclickstr.'" ></div>';
		
		$out .= '<div style=" color: #303030; width: '.$textwidth.'px; height: '.($radius * 2 - 2).'px; float: '.($position?'right':'left').'; text-align: '.($position?'right':'left').'; line-height: 120%; margin: 3px; margin-'.($position?'left':'right').': 6px; ">';
			$out .= htmlspecialchars($text, ENT_QUOTES);
		$out .= '</div>';
		
		$out .= '<div style=" clear: both; "></div>';
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_switch_i1mini($position=false, $text='выкл', $color, $onclickstr) {
	
	$out = '';
	
	$radius = 4;
	$textwidth = 18;
	
	$out .= '<div class="hoverlightblueborder" style=" display: block; float: left; background-color: #'.$color.'; font-size: 10px; border-radius: '.($radius + 6).'px; -moz-border-radius: '.($radius + 6).'px;  margin: 1px; cursor: pointer; box-shadow: inset 0 1px 2px rgba(0,0,0,0.4); " onclick="'.$onclickstr.'" >';
	
		$out .= '<div style=" display: block; float: '.($position?'right':'left').'; background-color: #ffffff; border-radius: '.($radius).'px; -moz-border-radius: '.($radius).'px;  margin: 3px; width: '.($radius * 2 - 2).'px; height: '.($radius * 2 - 2).'px; cursor: pointer; border: solid 1px #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.6); " onclick="'.$onclickstr.'" ></div>';
		
		$out .= '<div style=" color: #303030; width: '.$textwidth.'px; height: '.($radius * 2 - 2).'px; float: '.($position?'right':'left').'; text-align: '.($position?'right':'left').'; line-height: 120%; margin: 1px; margin-'.($position?'left':'right').': 3px; ">';
			$out .= htmlspecialchars($text, ENT_QUOTES);
		$out .= '</div>';
		
		$out .= '<div style=" clear: both; "></div>';
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_switch_tick($position=false, $text='выкл', $color, $onclickstr) {
	
	$out = '';
	
	$radius = 6;
	
	$out .= '<div class="hoverlightblueborder" style=" display: block; float: left; background-color: #'.$color.'; font-size: 10px; border-radius: '.($radius + 6).'px; -moz-border-radius: '.($radius + 6).'px;  margin: 1px; cursor: pointer; box-shadow: inset 0 1px 2px rgba(0,0,0,0.4); width: '.($radius * 2 + 6).'px; height: '.($radius * 2).'px; " onclick="'.$onclickstr.'" >';
	
		if ($position) {
			$out .= '<div style=" display: block; float: left; background-color: #ffffff; border-radius: '.($radius).'px; -moz-border-radius: '.($radius).'px;  margin-left: 5px; margin-top: 2px; width: '.($radius * 2 - 6).'px; height: '.($radius * 2 - 6).'px; cursor: pointer; border: solid 1px #ffffff; box-shadow: 0 1px 2px rgba(0,0,0,0.6); opacity: 0.9; "  ></div>';
			// onclick="'.$onclickstr.'"
		}
		
		$out .= '<div style=" clear: both; "></div>';
		
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_edit_instruction($param) {
	
	$out = '';
	
	// raised rect

	$out .= '<div id="item_status_actionrect" style=" margin-top: 20px; width: 370px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; color: #606060; line-height: 125%; ">';
	
		$out .= '<div style=" padding: 15px 15px 15px 15px; ">';
		
			$out .= '<div style=" margin-bottom: 20px; font-size: 11pt; color: #909090; ">';
				$out .= 'Редактирование';
			$out .= '</div>';
			
			$out .= '<p class="grayeleg" style=" line-height: 140%; margin-top: 0px; font-size: 12px; letter-spacing: 0.5px; ">';
				$out .= 'Старайтесь использовать предлагаемые варианты проектов и названий кораблей';
			$out .= '</p>';
			
			$out .= '<p class="grayeleg" style=" line-height: 140%; margin-top: 0px; font-size: 12px; letter-spacing: 0.5px; margin-bottom: 0px; ">';
				$out .= 'После сохранения знака он будет ожидать проверки модератором, после чего будет опубликован. Ваши знаки, ожидающие модерации можно найти в разделе «Личный кабинет»';
			$out .= '</p>';

		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '</div>';
		
	$out .= '</div>';
	
	return $out;
}


// =============================================================================
function outhtml_item_view_advanced_info($param) {

	$out = '';

	$info = get_item_view_info($param['i']);
	
	$out .= '<div style=" float: right; margin-top: 0px; clear: none; width: 370px; ">';

		$out .= '<div style="box-shadow: 0 1px 2px rgba(30,32,35,0.3); background-color: #ebeff1; border: solid 1px #ebeff1; border-radius: 3px; padding: 10px; ">';

		// upload info
		if (am_i_admin()) {
		
			$out .= '<div style=" font-size: 11px; ">';
			
				$out .= '<p>';
					$out .= '<span style=" color: #808080; ">'.'Загружен: '.'</span>';
					$out .= '<span style=" color: #303030; ">'.date('d.m.Y', strtotime($info['time_submit_start'])).'</span>';
				$out .= '</p>';
				
				$out .= '<p style=" margin-top: 5px; ">';
					$out .= '<span style=" color: #808080; ">'.'Загрузил: '.'</span>';
					$out .= '<span style=" color: #303030; ">'.htmlspecialchars(my_get_user_name($info['submitter_id']), ENT_QUOTES).'</span>';
				$out .= '</p>';
				
				if ($info['moderator_id'] > 0) {
					$out .= '<p style=" margin-top: 5px; ">';
						$out .= '<span style=" color: #808080; ">'.'Модератор: '.'</span>';
						$out .= '<span style=" color: #303030; ">'.htmlspecialchars(my_get_user_name($info['moderator_id']), ENT_QUOTES).'</span>';
					$out .= '</p>';
				}
				
				if (am_i_superadmin()) {
					$out .= '<p style=" margin-top: 5px; ">';
						$out .= '<span style=" color: #808080; ">'.'Есть у '.'</span>';
						$owners = iurel_item_get_number_owners($param['i']);
						$out .= '<span style=" color: #303030; ">'.$owners.'</span>';
						$out .= '<span style=" color: #808080; ">'.' пользовател(я/ей):'.'</span>';
						if ($owners > 0) {
							$out .= '<p style=" margin-left: 0px; ">';
								$list = iurel_item_get_list_owners($param['i']);
								$out .= '<span style=" color: #303030; ">';
								$max = sizeof($list);
								if ($max > 20) $max = 20;
								for ($i = 0; $i < $max; $i++) {
									$out .= htmlspecialchars(my_get_user_name($list[$i]['user_id'], true), ENT_QUOTES);
									if ($i < ($max - 1)) {
										$out .= ', ';
									}
								}
								if ($max < sizeof($list)) $out .= '... ';
								$out .= '</span>';
							$out .= '</p>';
						}
					$out .= '</p>';
				}
				
				if (am_i_superadmin()) {
					$out .= '<p style=" margin-top: 5px; ">';
						$out .= '<span style=" color: #808080; ">'.'Редкость знака: '.'</span>';
						$out .= '<span style=" color: #303030; ">'.iurel_item_get_number_wanting($param['i']).'/'.iurel_item_get_number_owners($param['i']).'</span>';
						$out .= '<span style=" color: #808080; ">'.''.'</span>';
					$out .= '</p>';
				}
				
				
			$out .= '</div>';
		}
		//

		$out .= '</div>';
	
	$out .= '</div>';
	
	return $out;
}


// =============================================================================
function outhtml_noitem_form($param) {

	$out = '';

	$param['i'] = ''.intval($param['i']);

	$GLOBALS['pagetitle'] = 'Знак #'.$param['i'].' не существует - '.$GLOBALS['pagetitle'];

	$out .= '<div style=" float: left; width: 530px; margin-top: 40px; padding: 0px 0px 0px 20px; color: #808080; line-height: 125%; ">';
	
		// title
		$out .= '<h1 class="grayemb" style=" margin-top: 5px; margin-bottom: 30px; font-size: 18px; color: #a00000; ">';
			$out .= 'Знак';
			$out .= ' <span style=" color: #f02020; ">#'.$param['i'].'</span> ';
			$out .= 'не существует';
		$out .= '</h1>';
		
		//
	
		$out .= '<p class="grayeleg" style=" text-align: justify; ">';
			$out .= ' Мы не знаем почему, но запрашиваемый элемент не существует в нашем каталоге. ';
		$out .= '</p>';
		
		$out .= '<p class="grayeleg" style=" text-align: justify; ">';
			$out .= 'Вы можете просмотреть весь <a href="/index.php?m=c">каталог</a>. ';
		$out .= '</p>';
			
	$out .= '</div>';
		
	$out .= '<div style=" clear: both; min-height: 50px; "></div>';
	
	return $out;
}


?>