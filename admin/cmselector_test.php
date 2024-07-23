<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/cmselector.php');


// =============================================================================
function outhtml_cmselector_test($param) {

	$out = '';
	
	$out .= outhtml_script_cmselector();
	
	$out .= '<div style=" background-color: #f8f8f8f; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Селектор классификации/проектов</h1>';
			
			$out .= '<div style=" padding-left: 20px; ">';
			
				//$out .= outhtml_shipclass_tree($param);
				$a = array();
				$a['sel'] = 'c0';
				$a['state'] = 'close';
				$out .= outhtml_cmselector_div($a);
			
			$out .= '</div>';
			
		$out .= '</div>';
		
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
		//

		$out .= '</div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_admin_cmselector_test($param) {

	if (!am_i_admin()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}
	
	$GLOBALS['pagetitle'] = 'outhtml_admin_cmselector_test / '.$GLOBALS['pagetitle'];
	
	$out = '';

	$out .= outhtml_cmselector_test($param);
	
	return $out.PHP_EOL;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>