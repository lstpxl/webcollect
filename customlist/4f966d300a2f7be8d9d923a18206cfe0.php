<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_search_result.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist.php');



// =============================================================================
function outhtml_item_list_searchbar_customlist_4f966d300a2f7be8d9d923a18206cfe0($param) {

	$out = '';

		$link = '/customlist/4f966d300a2f7be8d9d923a18206cfe0.php';
		$out .= '<form method="GET" id="searchform" enctype="multipart/form-data" action="'.$link.'">';
			$out .= '<input type="hidden" name="m" value="c" />';
			$out .= '<input type="hidden" id="prevsearch" value="browse" />';
			$out .= '<input type="hidden" name="q" id="search_input" value="" />';
			$out .= '<input type="hidden" name="num" id="searchnum_input" value="" />';
			$out .= '<input type="hidden" name="my" id="" value="204" />';
			$out .= '<input type="hidden" name="tcl" id="" value="2" />';
			$out .= '<input type="hidden" name="extuser" id="" value="3" />';
			$out .= '<input type="hidden" name="sort" id="" value="c" />';
			$out .= '<input type="hidden" name="unknown" id="" value="a" />';
		$out .= '</form>';

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_item_search_result_customlist_4f966d300a2f7be8d9d923a18206cfe0($param) {

	$out = '';
	
	//
	
	$param['q'] = '';
	$param['num'] = '';
	$param['my'] = '204';
	$param['tcl'] = '2';
	$param['sort'] = 'c';
	$param['unknown'] = 'a';
	$param['extuser'] = '3';
	$param['search'] = 'browse';
	$GLOBALS['customlist'] = '4f966d300a2f7be8d9d923a18206cfe0';

	
	//
	$allowed = array('browse', 'text', 'num');
	if (!isset($param['search'])) $param['search'] = $allowed[0];
	if (!in_array($param['search'], $allowed)) $param['search'] = $allowed[0];
	if (!isset($param['q'])) $param['q'] = '';
	if (!isset($param['my'])) $param['my'] = '0';
	if (!isset($param['unknown'])) $param['unknown'] = 'a';
	
	/*
	if (isset($param['extuser'])) {
		if (!am_i_superadmin()) {
			$param['extuser'] = '0';
		} else {
			if (!ctype_digit($param['extuser'])) $param['extuser'] = '0';
			$param['extuser'] = ''.intval($param['extuser']);
		}
	} else {
		$param['extuser'] = '0';
	}
	*/
	
	//
	
	$titlestr = 'Коллекция по запросу';
	
	// sidebar
	$out .= '<div id="searchbarenv" style=" ">';
		$out .= outhtml_item_list_searchbar_customlist_4f966d300a2f7be8d9d923a18206cfe0($param);
		$GLOBALS['body_script_str'] .= outhtml_script_searchbar_slide();
	$out .= '</div>';

	
	//
	$total_count = get_item_search_result_count($param);
	
	// print $total_count;
	

	/*
	if ($param['my'] > 0) $titlestr = 'Моя коллекция. ';
	if ($param['my'] == '1') $titlestr .= '<span style=" color: #3f6b86; " >'.'Мои интересы'.'</span>';
	if ($param['my'] == '2') $titlestr .=  '<span style=" color: #3f6b86; " >'.'У меня есть'.'</span>';
	if ($param['my'] == '3') $titlestr .=  '<span style=" color: #3f6b86; " >'.'Ищу'.'</span>';
	if ($param['my'] == '4') $titlestr .=  '<span style=" color: #3f6b86; " >'.'Меняю/продаю'.'</span>';
	*/
	
	// $out .= '<h1 style=" float: left; clear: left; font-size: 20pt; margin-bottom: 20px; margin-top: 20px; padding-left: 18px; ">'.$titlestr.'</h1>';
	
	$out .= '<h1 class="grayemb" style=" margin-left: 20px; margin-bottom: 20px; margin-top: 25px; font-size: 24px; ">';
		$out .= $titlestr;
	$out .= '</h1>';

	//
	if ($param['my'] > 0) {
		$out .= '<p style=" float: right; font-size: 10pt; margin-top: 20px; margin-right: 20px; ">'.$total_count.' '.get_item_count_str_case($total_count).'</p>';
	} elseif (($param['search'] != 'browse') || ($total_count == 0)) {
		$out .= '<p style=" float: right; font-size: 10pt; margin-top: 20px; margin-right: 20px; ">Найдено '.$total_count.' '.get_item_count_str_case($total_count).'</p>';
	}
	
	//
	$out .= '<div style=" float: left; clear: both; margin-top: 20px; ">';
		$out .= outhtml_item_search_result_paginator($param, $total_count);
	$out .= '</div>';
	
	//
	$out .= '<div style=" clear: left; "></div>';
	
	//
	$list = get_item_search_result_list($param);
	
	//print_r($list);
	
	// calc_search_result_rows($param, $list);
	
	$shipstr = '_';
	$shipstrfull = '_';
	$shipmodel_head = '';
	$shipmodelclass_str = '_';
	$alpha = '_';
	
	$block_shipname = '_';
	$block_alpha = '_';
	
	$inrow = 0;
	$columns = 5;
	
	$block_itemset_id = -1;
	
	// sortfield_c

	for ($i = 0; $i < sizeof($list); $i++) {
	
		// !!!!
		// update_item_searchstring($list[$i]['item_id']);
		// !!!!!!!
	
		// print '('.$list[$i]['alpha_g'].'-'.$list[$i]['ship_g'].')';
		
		if ($param['sort'] == 's') {
		
			$cur_itemset_id  = get_item_itemset_id($list[$i]['item_id']);
		
			if ($block_itemset_id != $cur_itemset_id) {
				$block_itemset_id = $cur_itemset_id;
				if ($block_itemset_id > 0) {
					$str = my_get_itemset_name($block_itemset_id);
				} else {
					$str = '<span style=" font-size: 20px; padding-bottom: 10px; ">не входят в серию</span>';
				}
				$out .= outhtml_item_list_head_itemset($str);
				$inrow = 0;
			}
		}
		
		if ($param['sort'] == 'c') {
		
			// model head
		
			// next item classify
			
			$ship_id = get_item_ship_id($list[$i]['item_id']);
			if ($ship_id > 0) {
				$shipmodel_id = my_get_ship_model_id($ship_id);
				if ($shipmodel_id > 0) {
					$shipmodel_str = my_get_shipmodel_name($shipmodel_id);
				} else {
					$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
				}
			} else {
				$shipmodel_id = get_item_shipmodel_id($list[$i]['item_id']);
				$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
			}
			
			if ($shipmodel_id > 0) {
				$shipmodelclass_id = my_get_shipmodel_class($shipmodel_id);
				$shipmodelclass_str = my_get_shipclass_name($shipmodelclass_id);
			} else {
				$shipmodelclass_str = get_item_shipclass_name($list[$i]['item_id']);
			}
			
			$hasmodel = (get_item_ship_has_model($list[$i]['item_id']) == 'Y');
			if (!$hasmodel) {
				$shipmodel_id = 0;
				$shipmodel_str = '';
			}
			// $hasship = get_item_ship_has_ship($list[$i]['item_id']);
			
			$model_html = outhtml_item_list_head_shiptype($shipmodel_id, $shipmodel_str, $shipmodelclass_str, true, $hasmodel);
			
			$model_sub_str = get_item_list_head_shiptype_str($shipmodel_id, $shipmodel_str, $shipmodelclass_str, $hasmodel);
			
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			/*
			if ($GLOBALS['user_id'] == 2) {
				$out .= '('.$shipmodel_id.','.$shipmodel_str.','.$shipmodelclass_str.','.$hasmodel.','.$model_sub_str.') ';
				
			}
			*/
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		
			$printmodelhtml = false;
			if ($shipmodel_head != $model_html) {
			
				$shipmodel_head = $model_html;
				
				$inrow = 0;
				
				$modelhtml = $shipmodel_head;
				
				$printmodelhtml = true;
			}
			
			// ship head
			
			
			
			if ($ship_id > 0) {
				$cur_ship_str = my_get_ship_name($ship_id);
				$cur_ship_facnum = my_get_ship_factoryserialnum($ship_id);
			} else {
				$cur_ship_str = get_item_ship_name($list[$i]['item_id']);
				$cur_ship_facnum = my_get_item_ship_factoryserialnum($list[$i]['item_id']);
			}
			if ($cur_ship_facnum != '') $cur_ship_str .= ' <span class="shipserialnum">(зав. '.$cur_ship_facnum.')</span>';

			$printshiphtml = false;
			if ($shipstrfull != ($cur_ship_str.$model_sub_str)) {
			
				$shipstr = $cur_ship_str;
				if ($shipstr == '') {
					$shipstrd = 'Корабль неидентифицирован';
				} else {
					$shipstrd = $shipstr;
				}
				$detail = '';
				if ($model_sub_str != '') $detail = $model_sub_str;
				$shiphtml = outhtml_item_list_head_ship($shipstrd, $detail);
				/*
				if ($GLOBALS['user_id'] == 2) {
					$out .= '['.$detail.'] ';
				}
				*/
				$inrow = 0;
				
				$printshiphtml = true;
				$shipstrfull = ($cur_ship_str.$model_sub_str);
			}
			
			
			if ($printmodelhtml) $out .= $modelhtml;
			if ($printmodelhtml || $printshiphtml) {
				$out .= $shiphtml;
				/*
				if ($GLOBALS['user_id'] == 2) {
					$out .= '['.$model_sub_str.'] ';
				
				}
				*/
			}
			
		}
		
		if ($param['sort'] == 'a') {
		
			$ship_id = get_item_ship_id($list[$i]['item_id']);
			// $thisshipname = get_item_ship_name($list[$i]['item_id']);
			
			if ($ship_id > 0) {
				$cur_ship_str = my_get_ship_name($ship_id);
				$cur_ship_facnum = my_get_ship_factoryserialnum($ship_id);
			} else {
				$cur_ship_str = get_item_ship_name($list[$i]['item_id']);
				$cur_ship_facnum = my_get_item_ship_factoryserialnum($list[$i]['item_id']);
			}
			if ($cur_ship_facnum != '') $cur_ship_str .= ' <span class="shipserialnum">(зав. '.$cur_ship_facnum.')</span>';
			
			$thisshipalpha = mb_substr($cur_ship_str, 0, 1);
			
			if ($block_alpha != $thisshipalpha) {
				if ($thisshipalpha != '') {
					$out .= outhtml_item_list_head_alpha($thisshipalpha);
				} else {
					$out .= outhtml_item_list_head_alpha('<span style=" font-size: 20px; padding-bottom: 10px; ">Без названия</span>');
				}
				$inrow = 0;
				$block_alpha = $thisshipalpha;
			}
			if ( ($thisshipalpha != '') && ($block_shipname != $cur_ship_str) ) {
				$out .= outhtml_item_list_head_ship($cur_ship_str);
				$inrow = 0;
				$block_shipname = $cur_ship_str;
			}

		
			/*
			if ($list[$i]['alpha_g'] > 3) {
				//print 'ddfdslfhdfl'.$thisshipalpha;
				$thisshipname = get_item_ship_name($list[$i]['item_id']);
				$thisshipalpha = mb_substr($thisshipname, 0, 1);
				$out .= outhtml_item_list_head_alpha($thisshipalpha);
				$inrow = 0;
			}
		
			if ($list[$i]['ship_g'] > 3) {
				$thisshipname = get_item_ship_name($list[$i]['item_id']);
				$out .= outhtml_item_list_head_ship($thisshipname);
				$inrow = 0;
			}
			*/
		
		}
		
		if ($param['sort'] == 'c') {
		
			if ($list[$i]['model_g'] > 3) {
				$shipmodel_id = get_item_shipmodel_id($list[$i]['item_id']);
				$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
				$shipmodelclass_str = get_item_shipclass_name($list[$i]['item_id']);
				$model_html = outhtml_item_list_head_shiptype($shipmodel_id, $shipmodel_str, $shipmodelclass_str, true, true);
				
				$shipmodel_head = $model_html;
				$out .= $shipmodel_head;
				
				$inrow = 0;
			}
		
			if ($list[$i]['ship_g'] > 3) {
				$thisshipname = get_item_ship_name($list[$i]['item_id']);
				$out .= outhtml_item_list_head_ship($thisshipname);
				$inrow = 0;
			}
		
		}
		
		//
		// $out .= outhtml_item_inlist($list[$i]['item_id']);
		$out .= outhtml_item_inlist_div(array('i' => $list[$i]['item_id']));
		$inrow++;

		//
		if ($inrow >= $columns) {
			$out .= '<div style=" clear: left; "></div>';
			$inrow = 0;
		}
	}

	//
	$out .= '<div style=" clear: left; " ></div>';
	
	//
	$out .= '<div style=" float: left; clear: left; ">';
	$out .= outhtml_item_search_result_paginator($param, $total_count);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_customlist_4f966d300a2f7be8d9d923a18206cfe0($param) {
	
	$out = '';
	
	$out .= outhtml_script_item_search_result();
	$out .= outhtml_script_item_inlist();

	$out .= '<div style=" padding-top: 20px; background-color: #f8f8f8; padding-left: 0px; color: #888888; line-height: 125%; " >';
	
		$out .= '<div id="item_search_result_div" style=" width: 970px; ">';
			$out .= outhtml_item_search_result_customlist_4f966d300a2f7be8d9d923a18206cfe0($param);
		$out .= '</div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>