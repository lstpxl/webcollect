<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');



// =============================================================================
function count_unidentified_items() {
	
	$qr = mydb_queryarray("".
		" SELECT COUNT(item.item_id) AS n ".
		" FROM item ".
		" WHERE item.status = 'H' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr[0]['n'];
}

// =============================================================================
function outhtml_unidentified_items($param) {
	
	$out = '';
	
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_start ".
		" FROM item ".
		" WHERE item.status = 'H' ".
		" ORDER BY time_submit_finish ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
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
	
	$out .= '<div style=" margin-left: 18px; margin-bottom: 20px; ">';
	
	for ($i = 0; $i < $z; $i++) {
		$out .= outhtml_item_inlist_smallth($qr[$i]['item_id'], 'submittime');
	}
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function item_unidentified($param) {

	if (!am_i_registered_user()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}
	
	$GLOBALS['pagetitle'] = 'Ваша помощь с идентификацией / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<div style=" float: left;  clear: none; width: 425px; margin-left: 48px; color: #606060; margin-bottom: 30px; ">';

		$out .= '<div style=" margin-top: 24px; width: 512px;  ">';
		
			$out .= '<h1 class="grayemb" style=" margin-bottom: 20px; ">';
				$out .= 'Ваша помощь с идентификацией';
			$out .= '</h1>';
			
			$out .= '<p class="grayeleg" style=" text-align: justify; ">';
				$out .= 'Мы не смогли выяснить достаточно о некоторых знаках, чтобы правильно их классифицировать.';
			$out .= '</p>';
			$out .= '<p class="grayeleg" style=" text-align: justify; ">';
				$out .= 'Если вам известно о них больше, пожалуйста, сообщите нам через форму «Сообщить об ошибке в описании». ';
			$out .= '</p>';
			
		$out .= '</div>';
		
	$out .= '</div>';
	
	//
	
	$out .= '<div style=" float: right; clear: none; margin-top: 58px; ">';

	if (true) {
	
		$n = count_unidentified_items();

	
		// width: 310px;
		$out .= '<div style=" width: 308px; margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; color: #808080; ">';

			$out .= '<div style=" padding: 10px 20px 10px 20px;  text-align: left; ">';
			
				$str = get_item_count_str_case($n);
				$out .= '<p class="grayeleg">';
					$out .= '<span style=" color: #b01010; font-size: 16pt; ">'.$n.'</span> '.get_item_count_unid_str_case($n).' '.$str;
				$out .= '</p>';

			$out .= '</div>';

		$out .= '</div>';
	}
	
	$out .= '</div>';
	
	//
	
	$out .= '<div style=" clear: both; " ></div>';
	
	//
	
	$out .= outhtml_unidentified_items($param);
	
	return $out.PHP_EOL;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/item/unidentified.php') > 0) {

	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>