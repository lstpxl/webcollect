 <?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/treeindex.php');


// =============================================================================
function outhtml_admin_refresh_recursive_step($param) {

	$GLOBALS['pagetitle'] = 'Обновить структуру / '.$GLOBALS['pagetitle'];
	
	$out = '';
	
	$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; line-height: 100%; ">Обновить структуру</h1>';

	// $r = treeindex_rebuld_group_recursive_z('shipmodelclass', 0);
	
	treeindex_rebuld_group_recursive_ztop();
	
	//$str = $r?'yes':'no';

	

	$out .= '<div id="batch_upload_status_div" style=" margin-left: 18px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; border: solid 1px #a0a0a0; min-height: 100px; font-size: 10pt; font-family: \'Courier New\', Courier, monospace; color: #606060; " >';
	
		$out .= $str;
			
		$out .= '<p>Обработано: <span style=" padding: 4px; background-color: #f0f0a0; color: #000000; font-weight: bold; ">'.sizeof($qr).'<span></p>';
	
	$out .= '</div>';
			
	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>