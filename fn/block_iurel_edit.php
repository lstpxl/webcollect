<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_personalnote.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_gotit.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_storageplace.php');


// =============================================================================
function outhtml_iurel_block_div($param) {

	if (!isset($param['i'])) return '';
	if (!ctype_digit($param['i'])) return '';
	
	if (!$GLOBALS['is_registered_user']) return '';
	
	//

	$out = '';
	
	$out .= '<div id="iurel_block_div">';
	
	//
		
		$out .= outhtml_script_iurel_personalnote();
		$out .= outhtml_script_iurel_gotit();
		$out .= outhtml_script_iurel_storageplace();
	
	//
	
		$out .= '<h2 style=" font-size: 12pt;  margin-top: 20px; margin-bottom: 20px; color: #3f6b86; padding-left: 18px; ">Моя коллекция</h2>';
	
		$out .= '<div style=" background-color: #ffffff; border: solid 1px #b0b0b0; margin-top: 20px; border-radius: 4px; -moz-border-radius: 4px;  margin-top: 5px; margin-right: 5px; padding: 15px 5px 15px 15px; ">';
		
		//
		
		$out .= '<table><tr><td style=" vertical-align: top; font-size: 9pt; padding: 2px 5px 4px 5px; ">';
		
		$out .= '<nobr>У меня </nobr>';
		
		$out .= '</td><td style=" min-width: 80px; " >';
		
		$out .= outhtml_iurel_gotit_div(array('i' => $param['i']));
		
		$out .= '</td><td style=" vertical-align: top; font-size: 9pt; padding: 2px 5px 4px 15px; ">';
		
		$out .= '<nobr>Место хранения: </nobr>';
		
		$out .= '</td><td style=" vertical-align: top; " >';
		
		$out .= outhtml_iurel_storageplace_div(array('i' => $param['i']));
		
		$out .= '</td></tr></table>';
		
		//
		
		
		$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Личные заметки:</div>';
		$out .= outhtml_iurel_personalnote_div(array('i' => $param['i']));
		
		//$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Ищу:</div>';
		//$out .= outhtml_item_batchsize_div(array('i' => $param['i']));
		
		//$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Продаю:</div>';
		//$out .= outhtml_item_factory_div(array('i' => $param['i']));
		
		//$out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Цена продажи:</div>';
		
		// $out .= '<div style=" margin-top: 15px; padding-bottom: 2px; padding-left: 4px; font-size: 10pt; color: #888888; ">Куплен за:</div>';
		
		$out .= '</div>';
	
	//
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


?>