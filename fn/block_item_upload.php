<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_item_upload_sidebar_block() {

	if (!$GLOBALS['is_registered_user']) return '';
	if (!can_i_submit_item()) return '';

	$out = '';

	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';
		$out .= '<form method="POST" action="/item/add.php">';
			
			$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
				
				$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Добавить знак</strong></p>';
				
				$out .= '<p style=" margin-bottom: 6px; " ><strong>Загрузите</strong> картинку знака, которого у нас нет.</p>';
				$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="action" value="login" style="background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; ">Загрузить</button></div>';
			$out .= '</div>';
		$out .= '</form>';
	$out .= '</div>';
		
	return $out.PHP_EOL;
}


?>