<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');



// =============================================================================
function outhtml_delete_model_orphan($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);

	$name = my_get_shipmodel_name($shipmodel_id, 1);
	if ($name === false) return false;
	
	// delete blueprint
	$blueprintpath = my_get_blueprint_storage_dir().'/'.str_pad((''.$shipmodel_id), 10, '0', STR_PAD_LEFT).'.png';
	if (is_file($blueprintpath)) {
		$r = unlink($blueprintpath);
		if (!$r) {
			$out .= outhtml_error("Ошибка при удалении файла! (".__FILE__." Line ".__LINE__.")");
			return $out;
		}
	}
	
	
	$qr = mydb_query("".
		" DELETE FROM shipmodel ".
		" WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$out = '';
	$out .= '<div style=" margin-bottom: 5px; " >';
	$out .= 'Удалено';
	$out .= '</div>';

	return $out;
}


// =============================================================================
function outhtml_model_orphan($shipmodel_id, $param) {

	$out = '';

	// prepared query
	$a = array();
	$q = "".
		" SELECT * ".
		" FROM shipmodel ".
		" WHERE shipmodel.shipmodel_id = ? ".
		"";
	$a[] = $shipmodel_id;
	$t = 'i';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		die('outhtml_model_orphan() fatal error');
	}
	// end prepared query
	
	// is it really orphan?
	$children = my_elemtree_get_children_list('shipmodel', $shipmodel_id);
	if (sizeof($children) > 0) return '<p>not_orphan</p>';
	
	// !!!!!!!!!!!!!!!!!
	/*
	if ($qr[0]['has_blueprint'] == 'N') {
		$out .= outhtml_delete_model_orphan($shipmodel_id);
		return $out;
	}
	*/
	// !!!!!!!!!!!!!!!!!
	
	if (isset($param['command'])) {
		if ($param['command'] == 'delete') {
			if ($shipmodel_id == $param['shipmodel_id']) {
				$out .= outhtml_delete_model_orphan($shipmodel_id);
				return $out;
			}
		}
	}
	
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= '<div style=" margin-bottom: 5px; " >';
		
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=modelorphans&command=delete&shipmodel_id='.$qr[$i]['shipmodel_id'];
			$out .= '<a href="'.$href.'" style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #b00000; " >';
				$out .= '<nobr>'.'x'.'</nobr>';
			$out .= '</a>';
		
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #d0d0d0; " >';
				$out .= '<nobr>'.'id='.$qr[$i]['shipmodel_id'].'</nobr>';
			$out .= '</span>';
			
			$list = get_model_child_ship_list($qr[$i]['shipmodel_id']);
			$bg = (sizeof($list) > 0)?'20a020':'c0c0c0';
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #'.$bg.'; " >';
				$out .= '<nobr>'.sizeof($list).' К'.'</nobr>';
			$out .= '</span>';
			
			$list = get_model_child_item_list($qr[$i]['shipmodel_id']);
			$bg = (sizeof($list) > 0)?'20a020':'c0c0c0';
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #'.$bg.'; " >';
				$out .= '<nobr>'.sizeof($list).' З'.'</nobr>';
			$out .= '</span>';
			
			if ($qr[$i]['has_blueprint'] == 'Y') {
				$bg = 'f080f0';
				$c = '000000';
				$txt = 'чертеж';
			} else {
				$bg = 'c0c0c0';
				$c = '909090';
				$txt = 'без ч.';
			}
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #'.$c.'; background-color: #'.$bg.'; " >';
				$out .= '<nobr>'.$txt.'</nobr>';
			$out .= '</span>';
			
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #e0e0e0; " >';
				$out .= '<nobr>'.$qr[$i]['name'].'</nobr>';
			$out .= '</span>';
			


			if ($qr[$i]['natoc_id'] > 0) {
				$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #a0d0f0; " >';
					$out .= '<nobr>'.my_get_natoc_str($qr[$i]['natoc_id']).'</nobr>';
				$out .= '</span>';
			}
			
			if ($qr[$i]['shipmodelclass_id'] > 0) {
				$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #f0f0d0; " >';
					$out .= '<nobr>'.my_get_shipclass_name($qr[$i]['shipmodelclass_id']).'</nobr>';
				$out .= '</span>';
			}
			
		$out .= '</div>';
	}
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_orphans_model($param) {

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT shipmodel.shipmodel_id ".
		" FROM shipmodel ".
		" WHERE shipmodel_id ".
		" NOT IN ".
		" ( ".
		" SELECT DISTINCT shipmodel_id FROM ship ".
		" UNION ".
		" SELECT DISTINCT shipmodel_id FROM item ".
		" ) ".
		" ORDER BY shipmodel.numcode, shipmodel.nick ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	
	$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; " >';
	
		for ($i = 0; $i < sizeof($qr); $i++) {
			$out .= '<div style=" margin-bottom: 20px; " >';
				$out .= '<p style=" color: #303030; " >'.$qr[$i]['numcode'].'</p>';
				$out .= '<div style=" padding-left: 18px; " >';
					$out .= outhtml_model_orphan($qr[$i]['shipmodel_id'], $param);
				$out .= '</div>';
			$out .= '</div>';
		}
	
	$out .= '</div>';
	
	
	return $out.PHP_EOL;
}

?>