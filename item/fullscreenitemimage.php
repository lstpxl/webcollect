<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_item_fullscreenitemimage_content($param) {

	$out = '';
	
	$href = '/item/'.$param['return'].'.php?i='.$param['i'].'';
	
	$out .= '<h1 class="grayemb" style=" cursor: pointer; padding-top: 10px; margin-bottom: 10px; margin: 0 auto;  font-size: 18px; " onclick=" window.location = \''.$href.'\'; return false; " >';
		$out .= 'Знак';
		$out .= ' <span style=" color: #b0b0b0; ">#'.$param['i'].'</span>';
	$out .= '</h1>';
	
	$out .= '<img style=" padding: 20px; background-color: #ffffff; margin: 10px; border: solid 1px #ffffff; border-radius: 3px; box-shadow: 0 2px 4px rgba(0,0,0,0.3); " src="/item/image.php?i='.$param['i'].'&n='.$param['n'].'&s=o" onclick=" window.location = \''.$href.'\'; return false; " />';
	
	$out .= '<form method="GET" action="/item/'.$param['return'].'.php">';
		$out .= '<button class="lightbluegradient hoverlightblueborder" type="submit" name="i" value="'.$param['i'].'" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; width: 150px; margin: 0 auto; margin-bottom: 20px; ">Назад</button>';
	$out .= '</form>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_fullscreenitemimage($param) {

	if (!(am_i_superadmin() || am_i_vipcollector())) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	if (!isset($param['n'])) $param['n'] = '1';
	if (!ctype_digit($param['n'])) $param['n'] = '1';
	$param['n'] = ''.intval($param['n']);
	$existing_images_count = my_get_item_picture_count($param['i']);
	if ($param['n'] > $existing_images_count) $param['n'] = '1';
	
	if (!isset($param['return'])) $param['return'] = 'view';
	if (!(($param['return'] == 'view') || ($param['return'] == 'edit'))) $param['return'] = 'view';

	//
	
	return outhtml_item_fullscreenitemimage_content($param);
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/item/fullscreenitemimage.php') > 0) {
	$_GET['hidemenu'] = '1';
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_ic');
			jqfn_fullscreenitemimage($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>