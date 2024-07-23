<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_search_result.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist.php');


// =============================================================================
function outhtml_item_list($param) {

	// if (!am_i_registered_user()) {
	//		return outhtml_welcome_screen($param);
	// }
	
	$out = '';
	
	$out .= outhtml_script_item_search_result();
	$out .= outhtml_script_item_inlist();
	
	$out .= '<div id="item_search_result_div">';
	
		$out .= outhtml_item_search_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}

?>