<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/treeindex.php');


// =============================================================================
function outhtml_delete_ship_orphan($ship_id) {

	$name = my_get_ship_name($ship_id);
	if ($name === false) return false;
		
	
	$qr = mydb_query("".
		" DELETE FROM ship ".
		" WHERE ship.ship_id = '".$ship_id."' ".
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
function outhtml_ship_orphan($ship_id, $param) {

	$out = '';

	// prepared query
	$a = array();
	$q = "".
		" SELECT * ".
		" FROM ship ".
		" WHERE ship.ship_id = ? ".
		"";
	$a[] = $ship_id;
	$t = 'i';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		die('outhtml_ship_orphan() fatal error');
	}
	// end prepared query
	
	
	// is it really orphan?
	$children = my_elemtree_get_children_list('ship', $ship_id);
	if (sizeof($children) > 0) return '<p>not_orphan</p>';
	
	
	// !!!!!!!!!!!!!!!!!!!!!
	$out .= outhtml_delete_ship_orphan($ship_id);
	return $out;
	// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
	if (isset($param['command'])) {
		if ($param['command'] == 'delete') {
			if ($ship_id == $param['ship_id']) {
				$out .= outhtml_delete_ship_orphan($ship_id);
				return $out;
			}
		}
	}
	
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= '<div style=" margin-bottom: 5px; " >';
		
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=shiporphans&command=delete&ship_id='.$qr[$i]['ship_id'];
			$out .= '<a href="'.$href.'" style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #b00000; " >';
				$out .= '<nobr>'.'x'.'</nobr>';
			$out .= '</a>';
		
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #d0d0d0; " >';
				$out .= '<nobr>'.'id='.$qr[$i]['ship_id'].'</nobr>';
			$out .= '</span>';
			
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #e0e0e0; " >';
				$out .= '<nobr>'.$qr[$i]['name'].'</nobr>';
			$out .= '</span>';
			


			if ($qr[$i]['shipmodel_id'] > 0) {
				$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #a0d0f0; " >';
					$out .= '<nobr>'.my_get_shipmodel_name($qr[$i]['shipmodel_id'], 0).'</nobr>';
				$out .= '</span>';
				
				$shipmodelclass_id = my_get_shipmodel_class($qr[$i]['shipmodel_id']);
				
				if ($shipmodelclass_id > 0) {
					$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #f0f0d0; " >';
						$out .= '<nobr>'.my_get_shipclass_name($shipmodelclass_id).'</nobr>';
					$out .= '</span>';
				}
			}
			
			
			
		$out .= '</div>';
	}
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_orphans_ship($param) {

	$out = '';
	
	$GLOBALS['pagetitle'] = 'Корабли-сироты / '.$GLOBALS['pagetitle'];
	
	$qr = mydb_queryarray("".
		" SELECT ship.ship_id, ship.name ".
		" FROM ship ".
		" WHERE ship_id ".
		" NOT IN ".
		" ( ".
		" SELECT DISTINCT ship_id FROM item ".
		" ) ".
		" ORDER BY ship.name, ship.factoryserialnum ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	

	
	$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; " >';
	
		for ($i = 0; $i < sizeof($qr); $i++) {
			$out .= '<div style=" margin-bottom: 20px; " >';
				$out .= '<div style=" padding-left: 18px; " >';
					$out .= outhtml_ship_orphan($qr[$i]['ship_id'], $param);
				$out .= '</div>';
			$out .= '</div>';
		}
	
	$out .= '</div>';
	
	
	return $out.PHP_EOL;
}

?>