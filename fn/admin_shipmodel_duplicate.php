<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_shipmodel_duplicate() {

$str = <<<SCRIPTSTRING

var my_shipmodel_duplicate_destination_id = 0;


function js_shipmodel_duplicate_onchange(shipmodel_id) {

	if (shipmodel_id < 1) {
		alert('Error 1');
		return false;
	}
	
	my_shipmodel_duplicate_destination_id = shipmodel_id;
}

function js_shipmodel_duplicate_action(shipmodel_id) {

	if (shipmodel_id < 1) {
		alert('Error 1');
		return false;
	}
	
	if (my_shipmodel_duplicate_destination_id < 1) {
		alert('Необходимо выбрать приемника');
		return false;
	}
	
	if (my_shipmodel_duplicate_destination_id == shipmodel_id) {
		alert('Не должно совпадать');
		return false;
	}
		
	var url = '/index.php';
	var params = {m:'a', sm:'dup', obj:'model', command:'delete', shipmodel_id:shipmodel_id, destination:my_shipmodel_duplicate_destination_id};
	
	return my_get_url(url, params);
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function get_model_child_ship_list($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);
	
	$q = "SELECT ship.* ".
		" FROM ship ".
		" WHERE ship.shipmodel_id = '".$shipmodel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr;
}


// =============================================================================
function get_model_child_item_list($shipmodel_id) {

	$shipmodel_id = ''.intval($shipmodel_id);
	
	$q = "SELECT item.* ".
		" FROM item ".
		" WHERE item.shipmodel_id = '".$shipmodel_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr;
}



// =============================================================================
function outhtml_delete_model_duplicate($shipmodel_id, $good_id) {

	// return false;
	
	// --------------------------****************************************
	
	if ($shipmodel_id < 1) return false;
	if ($good_id < 1) return false;

	$name1 = my_get_shipmodel_name($shipmodel_id);
	if ($name1 === false) return false;
	// if ($name1 == '') return false;
	
	$name2 = my_get_shipmodel_name($good_id);
	if ($name2 === false) return false;
	if ($name2 == '') return false;
	
	//

	$sl = get_model_child_ship_list($shipmodel_id);
	for ($i = 0; $i < sizeof($sl); $i++) {
		$qr = mydb_query("".
			" UPDATE ship ".
			" SET ship.shipmodel_id = '".$good_id."' ".
			" , ship.refresh = 'Y' ".
			" WHERE ship.ship_id = '".$sl[$i]['ship_id']."' ".
			"");
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
	}
	
	$il = get_model_child_item_list($shipmodel_id);
	for ($i = 0; $i < sizeof($il); $i++) {
		$qr = mydb_query("".
			" UPDATE item ".
			" SET item.shipmodel_id = '".$good_id."' ".
			" , item.downlink_time = '".'1999-02-02 00:00:00'."' ".
			" , item.refresh = 'Y' ".
			" WHERE item.item_id = '".$il[$i]['item_id']."' ".
			"");
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
	}
	
	// delete blueprint
	$blueprintpath = my_get_blueprint_storage_dir().'/'.str_pad((''.$shipmodel_id), 10, '0', STR_PAD_LEFT).'.png';
	if (is_file($blueprintpath)) {
		$r = unlink($blueprintpath);
		if (!$r) {
			$out .= outhtml_error("Ошибка при удалении файла! (".__FILE__." Line ".__LINE__.")");
			return $out;
		}
	}
	
	//
	
	$qr = mydb_query("".
		" DELETE FROM shipmodel ".
		" WHERE shipmodel.shipmodel_id = '".$shipmodel_id."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$qr = mydb_query("".
		" UPDATE shipmodel ".
		" SET shipmodel.refresh = 'Y' ".
		" WHERE shipmodel.shipmodel_id = '".$good_id."' ".
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
function outhtml_model_duplicate_group($name, $param) {

	$out = '';

	// prepared query
	$a = array();
	$q = "".
		" SELECT * ".
		" FROM shipmodel ".
		" WHERE shipmodel.name = ? ".
		" ORDER BY shipmodel.shipmodel_id ".
		"";
	$a[] = $name;
	$t = 's';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		die('outhtml_model_duplicate_group() fatal error');
	}
	// end prepared query
	
	//
	
	/*
	$out .= '<div style=" margin-bottom: 5px; " >';
		
			$href = '/admin/tool_dup_shipmodel.php'.'?shipmodel='.$qr[0]['shipmodel_id'];
			$out .= '<a href="'.$href.'" style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #606060; " >';
				$out .= '<nobr>'.'Разобраться'.'</nobr>';
			$out .= '</a>';
			
	$out .= '</div>';
	*/
	
	//
	
	
	for ($i = 0; $i < sizeof($qr); $i++) {
	
		$out .= '<div style=" margin-bottom: 5px; " >';
		
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 1px 3px 0px 3px; min-width: 20px; text-align: center; color: #606060; background-color: #d0d0d0; " >';
				$out .= '<input type="radio" name="destination" value="'.$qr[$i]['shipmodel_id'].'" onchange=" js_shipmodel_duplicate_onchange('.$qr[$i]['shipmodel_id'].'); " title="Переместить сюда элементы удаляемой группы" />';
			$out .= '</span>';
		
			$out .= '<span style=" cursor: pointer; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #b00000; " onclick=" js_shipmodel_duplicate_action('.$qr[$i]['shipmodel_id'].'); " title="Удалить группу" >';
				// $out .= '<nobr>'.'x'.'</nobr>';
				$out .= 'x';
			$out .= '</span>';
			
			/*
			$href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=model&command=delete&shipmodel_id='.$qr[$i]['shipmodel_id'];
			$out .= '<a href="'.$href.'" style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #b00000; " >';
				$out .= '<nobr>'.'x'.'</nobr>';
			$out .= '</a>';
			*/
		
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
	
	//
	
	$out .= '<div style=" margin-bottom: 15px; " ></div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_administration_duplicates_model($param) {

	$out = '';
	
	if (isset($param['command'])) {
		if ($param['command'] == 'delete') {
			$param['shipmodel_id'] = ''.intval($param['shipmodel_id']);
			$param['destination'] = ''.intval($param['destination']);
			$out .= outhtml_delete_model_duplicate($param['shipmodel_id'], $param['destination']);
		}
	}
	
	//SELECT name, COUNT(*) c FROM table GROUP BY name HAVING c > 1;
	
	$qr = mydb_queryarray("".
		" SELECT shipmodel.name, COUNT(*) c ".
		" FROM shipmodel ".
		" GROUP BY name ".
		" HAVING ( c > 1 ) ".
		" ORDER BY name ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= outhtml_script_shipmodel_duplicate();
	
	$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; " >';
	
		for ($i = 0; $i < sizeof($qr); $i++) {
			$out .= '<div style=" margin-bottom: 20px; " >';
				$out .= '<p style=" color: #303030; " >'.$qr[$i]['name'].'</p>';
				$out .= '<div style=" padding-left: 18px; " >';
					$out .= outhtml_model_duplicate_group($qr[$i]['name'], $param);
				$out .= '</div>';
			$out .= '</div>';
		}
	
	$out .= '</div>';
	
	
	return $out.PHP_EOL;
}

?>