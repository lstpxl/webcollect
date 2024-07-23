<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_ship_duplicate() {

$str = <<<SCRIPTSTRING

var my_ship_duplicate_destination_id = 0;


function js_ship_duplicate_onchange(ship_id) {

	if (ship_id < 1) {
		alert('Error 1');
		return false;
	}
	
	my_ship_duplicate_destination_id = ship_id;
}

function js_ship_duplicate_action(ship_id) {

	if (ship_id < 1) {
		alert('Error 1');
		return false;
	}
	
	if (my_ship_duplicate_destination_id < 1) {
		alert('Необходимо выбрать приемника');
		return false;
	}
	
	if (my_ship_duplicate_destination_id == ship_id) {
		alert('Не должно совпадать');
		return false;
	}
		
	var url = '/index.php';
	var params = {m:'a', sm:'dup', obj:'ship', command:'delete', ship_id:ship_id, destination:my_ship_duplicate_destination_id};
	
	return my_get_url(url, params);
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function get_ship_child_item_list($ship_id) {

	$ship_id = ''.intval($ship_id);
	
	$q = "SELECT item.* ".
		" FROM item ".
		" WHERE item.ship_id = '".$ship_id."' ";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr;
}



// =============================================================================
function outhtml_delete_ship_duplicate($ship_id, $good_id) {

	// return false;
	
	
	// --------------------------****************************************
	
	if ($ship_id < 1) return false;
	if ($good_id < 1) return false;

	$name1 = my_get_ship_name($ship_id);
	if ($name1 === false) return false;
	// if ($name1 == '') return false;
	
	$name2 = my_get_ship_name($good_id);
	if ($name2 === false) return false;
	if ($name2 == '') return false;
		
	//
		
	$il = get_ship_child_item_list($ship_id);
	for ($i = 0; $i < sizeof($il); $i++) {
		$qr = mydb_query("".
			" UPDATE item ".
			" SET item.ship_id = '".$good_id."' ".
			" , item.downlink_time = '".'1999-02-02 00:00:00'."' ".
			" , item.refresh = 'Y' ".
			" WHERE item.item_id = '".$il[$i]['item_id']."' ".
			"");
		if ($qr === false) {
			my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
	}
	
	//
	
	$qr = mydb_query("".
		" DELETE FROM ship ".
		" WHERE ship.ship_id = '".$ship_id."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$qr = mydb_query("".
		" UPDATE ship ".
		" SET ship.refresh = 'Y' ".
		" WHERE ship.ship_id = '".$good_id."' ".
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
function outhtml_ship_duplicate_group($name, $param) {

	$out = '';

	// prepared query
	$a = array();
	$q = "".
		" SELECT * ".
		" FROM ship ".
		" WHERE ship.name = ? ".
		" ORDER BY ship.ship_id ".
		"";
	$a[] = $name;
	$t = 's';
	$qr = mydb_prepquery($q, $t, $a);
	if ($qr === false) {
		die('outhtml_ship_duplicate_group() fatal error');
	}
	// end prepared query
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= '<div style=" margin-bottom: 5px; " >';
		
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 1px 3px 0px 3px; min-width: 20px; text-align: center; color: #606060; background-color: #d0d0d0; " >';
				$out .= '<input type="radio" name="destination" value="'.$qr[$i]['ship_id'].'" onchange=" js_ship_duplicate_onchange('.$qr[$i]['ship_id'].'); " title="Переместить сюда элементы удаляемой группы" />';
			$out .= '</span>';
		
			// $href = $_SERVER['PHP_SELF'].'?m=a&sm=dup&obj=ship&command=delete&ship_id='.$qr[$i]['ship_id'];
			$out .= '<span style=" cursor: pointer; font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #b00000; " onclick=" js_ship_duplicate_action('.$qr[$i]['ship_id'].'); " title="Удалить группу" >';
				// $out .= '<nobr>'.'x'.'</nobr>';
				$out .= 'x';
			$out .= '</span>';
		
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #d0d0d0; " >';
				$out .= '<nobr>'.'id='.$qr[$i]['ship_id'].'</nobr>';
			$out .= '</span>';
						
			$list = get_ship_child_item_list($qr[$i]['ship_id']);
			$bg = (sizeof($list) > 0)?'20a020':'c0c0c0';
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #ffffff; background-color: #'.$bg.'; " >';
				$out .= '<nobr>'.sizeof($list).' З'.'</nobr>';
			$out .= '</span>';
			
			
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #e0e0e0; " >';
				$out .= '<nobr>'.$qr[$i]['name'].'</nobr>';
			$out .= '</span>';
			
			$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #a0d0f0; " >';
				$out .= '<nobr>'.$qr[$i]['factoryserialnum'].'</nobr>';
			$out .= '</span>';
			
			if ($qr[$i]['ship_id'] > 0) {
				$out .= '<span style=" font-size: 9pt; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 2px; padding: 0px 3px 0px 3px; min-width: 20px; text-align: center; color: #000000; background-color: #f0f0d0; " >';
					$out .= '<nobr>'.get_item_shipmodel_name_full($qr[$i]['shipmodel_id'], '').'</nobr>';
				$out .= '</span>';
			}
			
		$out .= '</div>';
	}
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_administration_duplicates_ship($param) {

	$out = '';
	
	if (isset($param['command'])) {
		if ($param['command'] == 'delete') {
			$param['ship_id'] = ''.intval($param['ship_id']);
			$param['destination'] = ''.intval($param['destination']);
			$out .= outhtml_delete_ship_duplicate($param['ship_id'], $param['destination']);
		}
	}
	
	$qr = mydb_queryarray("".
		" SELECT ship.name, COUNT(*) c ".
		" FROM ship ".
		" GROUP BY name ".
		" HAVING ( c > 1 ) ".
		" ORDER BY name ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= outhtml_script_ship_duplicate();
	
	
	$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; " >';
	
		for ($i = 0; $i < sizeof($qr); $i++) {
			$out .= '<div style=" margin-bottom: 20px; " >';
				$out .= '<p style=" color: #303030; " >'.$qr[$i]['name'].'</p>';
				$out .= '<div style=" padding-left: 18px; " >';
					$out .= outhtml_ship_duplicate_group($qr[$i]['name'], $param);
				$out .= '</div>';
			$out .= '</div>';
		}
	
	$out .= '</div>';
	
	
	return $out.PHP_EOL;
}

?>