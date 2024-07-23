<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_item_duplicate_group($crc) {

	$out = '';

	$qr = mydb_queryarray("".
		" SELECT item_id ".
		" FROM item ".
		" WHERE item.imgcrc = '".$crc."' ".
		" ORDER BY item.item_id ".
		"");
	if ($qr === false) {
		die('outhtml_item_duplicate_group() fatal error');
	}

	for ($i = 0; $i < sizeof($qr); $i++) {
		$out .= outhtml_item_inlist_smallth($qr[$i]['item_id'], 'ship model status');
	}
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_administration_duplicates_item($param) {

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.imgcrc, COUNT(*) c ".
		" FROM item ".
		" WHERE item.imgcrc > 0 ".
		" GROUP BY imgcrc ".
		" HAVING ( c > 1 ) ".
		" ORDER BY item_id ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	
	$out .= '<div style=" padding-left: 18px; margin-bottom: 20px; " >';
	
		for ($i = 0; $i < sizeof($qr); $i++) {
			$out .= outhtml_item_duplicate_group($qr[$i]['imgcrc']);
			
			$out .= '<div style=" clear: both; margin-bottom: 10px; " ></div>';
		}
		
		
		
	$out .= '</div>';
	
	
	return $out.PHP_EOL;
}

?>