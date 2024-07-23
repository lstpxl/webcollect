<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_item_addedon() {

$str = <<<SCRIPTSTRING


function js_addedon_refresh() {

	var elem = document.getElementById('addedon_year');
	if (!elem) return false;
	var year = elem.value;
	
	var elem = document.getElementById('addedon_month');
	if (!elem) return false;
	var month = elem.value;
	
	var param = new Array();
	param['year'] = year;
	param['month'] = month;
	return my_get_url('/item/addedon.php', param);
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function count_addedon_items($param) {
	
	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT COUNT(item.item_id) AS n ".
		" FROM item ".
		" WHERE ( item.item_id > 0 ) ".
		" AND ( item.status = 'K' ) ".
		" AND ( YEAR(item.time_approved) = '".$param['year']."' ) ".
		" AND ( MONTH(item.time_approved) = '".$param['month']."' ) ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if (sizeof($qr) != 1) {
		return $out.PHP_EOL;
	}
	
	return $qr[0]['n'];
}



// =============================================================================
function outhtml_addedon_items($param) {
	
	$out = '';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, item.time_approved ".
		" FROM item ".
		" WHERE ( item.item_id > 0 ) ".
		" AND ( item.status = 'K' ) ".
		" AND ( YEAR(item.time_approved) = '".$param['year']."' ) ".
		" AND ( MONTH(item.time_approved) = '".$param['month']."' ) ".
		" ORDER BY item.time_approved ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	/*
	if (sizeof($qr) < 1) {
		return $out.PHP_EOL;
	}
	
	$z = sizeof($qr);
	$max = (9 * 30);
	
	
	if ($z > $max) {
		$z = $max;
		$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; " >Показаны: <span style=" color: #66737b; ">'.$z.'<span></div>';
	}
	
	$day = '';
	*/
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; ">';
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= outhtml_item_inlist_smallth($qr[$i]['item_id'], 'submittime');
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_addedon($param) {

	if (!am_i_registered_user()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}
	
	$GLOBALS['pagetitle'] = 'Знаки добавленные / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= outhtml_script_item_addedon();
	
	$out .= '<div style=" float: left;  clear: none; width: 425px; margin-left: 18px; color: #606060; margin-bottom: 30px; ">';

		$out .= '<div style=" margin-top: 24px; width: 512px;  ">';
		
			$out .= '<h1 class="grayemb" style=" margin-bottom: 20px; ">';
				$out .= 'Знаки добавленные';
			$out .= '</h1>';
			
			//
			$nowyear = intval(date('Y'));
			if ($nowyear < 2015) $nowyear = 2015;
			$nowmonth = intval(date('m'));
			
			
			if (!isset($param['year'])) $param['year'] = $nowyear;
			$param['year'] = intval($param['year']);
			if (($param['year'] < 2014) || ($param['year'] > $nowyear)) $param['year'] = $nowyear;
			
			if (!isset($param['month'])) $param['month'] = $nowmonth;
			$param['month'] = intval($param['month']);
			if (($param['month'] < 1) || ($param['month'] > 12)) $param['month'] = $nowmonth;
			
			$out .= '<select class="hoverwhiteborder" name="month" id="addedon_month" style=" width: 120px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 12px; margin-right: 10px; " onChange=" js_addedon_refresh(); return false; " >';
			for ($i = 1; $i <= 12; $i++) {
				$ins = ($param['month'] == $i)?' selected ':'';
				$out .= '<option '.$ins.' value="'.$i.'">'.my_month_text($i, 1).'</option>';
			}
			$out .= '</select>';
			
			$out .= '<select class="hoverwhiteborder" name="year" id="addedon_year" style=" width: 80px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 12px; " onChange=" js_addedon_refresh(); return false; " >';
			for ($i = 2014; $i <= $nowyear; $i++) {
				$ins = ($param['year'] == $i)?' selected ':'';
				$out .= '<option '.$ins.' value="'.$i.'">'.$i.'</option>';
			}
			$out .= '</select>';
			
			//
			
			$out .= '<p class="grayeleg" style=" text-align: justify; ">';
				$out .= 'Здесь приведены знаки, появившиеся в нашем каталоге за указанный период времени.';
			$out .= '</p>';

			
		$out .= '</div>';
		
	$out .= '</div>';
	
	//
	
	$out .= '<div style=" float: right; clear: none; margin-top: 38px; ">';

	if (true) {
	
		$n = count_addedon_items($param);

		// width: 310px;
		$out .= '<div style=" width: 308px; margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; color: #808080; ">';

			$out .= '<div style=" padding: 10px 20px 10px 20px;  text-align: left; ">';
			
				$str = get_item_count_str_case($n);
				$out .= '<p class="grayeleg">';
					$out .= '<span style=" color: #b01010; font-size: 16pt; ">'.$n.'</span> '.$str;
				$out .= '</p>';

			$out .= '</div>';

		$out .= '</div>';
	}
	
	$out .= '</div>';
	
	//
	
	$out .= '<div style=" clear: both; " ></div>';
	
	//
	
	$out .= outhtml_addedon_items($param);
	
	return $out.PHP_EOL;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/item/addedon.php') > 0) {

	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>