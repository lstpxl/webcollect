<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_form_item_viewhead() {

$str = <<<SCRIPTSTRING

function js_form_item_viewhead_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}
	
	return true;
}


function form_item_viewhead_reload() {
	
	var elem = document.getElementById('form_item_viewhead_item_id');
	if (!elem) return false;
	var item_id = elem.value;
	if (!is_numeric(item_id)) return false;
	
	var elem = document.getElementById('form_item_viewhead_image_n');
	if (!elem) return false;
	var image_n = elem.value;
	if (!is_numeric(image_n)) return false;
	
	var elem = document.getElementById('form_item_viewhead_image_n_next');
	if (!elem) return false;
	var image_n_next = elem.value;
	if (!is_numeric(image_n_next)) return false;
		
	var url = '/xhr/form_item_viewhead.php?i=' + item_id + '&n=' + image_n_next + '';
	return ajax_my_get_query(url);
}

function form_item_viewhead_change() {
	return form_item_viewhead_reload();
}


function form_item_viewhead_zoom() {

	var elem = document.getElementById('form_item_viewhead_item_id');
	if (!elem) return false;
	var item_id = elem.value;
	if (!is_numeric(item_id)) return false;
	
	var elem = document.getElementById('form_item_viewhead_image_n');
	if (!elem) return false;
	var image_n = elem.value;
	if (!is_numeric(image_n)) return false;

	alert('Zoom zoom!');
}

SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function zzzzzzz($param) {

				
					if (am_i_admin()) {
						$zoomins = '';
						/*
						$zoomins = ' onmouseover=" js_popup_zoom1_show(event, '.$param['i'].'); return false; "  onmousemove =" js_popup_zoom1_move(event); return false; " onmouseout=" js_popup_zoom1_hide(event); return false; " ';
						*/
					} else {
						$zoomins = '';
					}
				
					$out .= '<div style=" position:relative; display: block; width: 186px; height: 186px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " '.$zoomins .' >';
					$out .= '</div>';
					
					// onmouseout=" js_popup_zoom1_hide(event); return false; "
				
			
}


// =============================================================================
function outhtml_form_item_viewhead_content($param) {

	$status = my_get_item_status($param['i']);
	$statusstr = my_decode_item_status($status);
	$statuscolor = my_get_color_item_status($status);
	$existing_images_count = my_get_item_picture_count($param['i']);

	$out = '';
	
	$out .= '<div id="item_images_block" style=" ">';
	
		$out .= '<input type="hidden" id="form_item_viewhead_image_n" value="'.$param['n'].'" />';
		
		if ($existing_images_count > 1) {
			$next = $param['n'] + 1;
			if ($next > $existing_images_count) $next = 1;
			$out .= '<input type="hidden" id="form_item_viewhead_image_n_next" value="'.$next.'" />';
		}
	
		$out .= '<div style=" position: relative; width: 508px; height: 190px; padding: 10px; background-repeat: no-repeat; background-position: 175px 15px; background-image: url(\'/item/image.php?i='.$param['i'].'&n='.$param['n'].'&s=m\'); ">';
		
			$out .= '<div style=" float: left; width: 220px; ">';
			
				// title
				$out .= '<h1 class="grayemb" style=" margin-top: 5px; margin-left: 5px;  margin-bottom: 10px; font-size: 18px; ">';
					$out .= 'Знак';
					$out .= ' <span style=" color: #b0b0b0; ">#'.$param['i'].'</span>';
				$out .= '</h1>';

			$out .= '</div>';

			$out .= '<div style=" float: right; width: 220px; ">';

				
				$out .= '<h2 class="grayemb" style=" float: right; margin: 4px 0px 0px 5px; color: #'.$statuscolor.'; ">';
					$out .= ''.$statusstr;
				$out .= '</h2>';

				// status
				$radius = 6;
				$out .= '<div style=" display: block; margin: 5px; float: right; background-color: #'.$statuscolor.'; border-radius: '.($radius).'px; -moz-border-radius: '.($radius).'px;   width: '.($radius * 2 - 2).'px; height: '.($radius * 2 - 2).'px; border: solid 1px #'.$statuscolor.'; box-shadow:  0px -1px 0px rgba(87,99,105,0.1)" ></div>';
				// 0 2px 1px rgba(0,0,0,0.3);
				//
				$out .= '<div style=" clear: both; "></div>';
				
				
			
			$out .= '</div>';

			if (can_i_edit_item($param['i']) || can_i_moderate_item($param['i'])) {
				$out .= '<div style=" display: block; position: absolute; bottom: 10px; right: 10px; ">';
					$out .= '<form method="GET" action="/item/edit.php">';
						$out .= '<div style=" margin-top: 0px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" name="i" value="'.$param['i'].'" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 50px; ">Править</button></div>';
					$out .= '</form>';
				$out .= '</div>';
			}
			
			if ($existing_images_count > 1) {
				$out .= '<div style=" display: block; position: absolute; bottom: 10px; left: 10px; ">';
					$out .= '<div style=" margin-top: 0px; vertical-align: top; "><button class="hoverlightblueborder" style=" background-color: #d9e1e7; text-align: right; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 5px 3px 12px; width: 68px; height: 25px; background-repeat: no-repeat; background-position: 3px 2px; background-image: url(\'/images/layer_icon_blue_20px.png\'); " onclick=" form_item_viewhead_change(); " >'.$param['n'].' / '.$existing_images_count.'</button></div>';
				$out .= '</div>';
			}
			
			if (am_i_superadmin() || am_i_vipcollector()) {
				$out .= '<form method="GET" action="/item/fullscreenitemimage.php">';
					$out .= '<input type="hidden" name="i" value="'.$param['i'].'" />';
					$out .= '<input type="hidden" name="n" value="'.$param['n'].'" />';
					$out .= '<div style=" display: block; position: absolute; bottom: 40px; left: 10px; ">';
						$out .= '<div style=" margin-top: 0px; vertical-align: top; "><button class="hoverlightblueborder" type="submit" name="return" value="view" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; width: 25px; height: 25px; background-color: #d9e1e7; background-repeat: no-repeat; background-position: 3px 2px; background-image: url(\'/images/zoom_icon_blue_20px.png\'); " ></button></div>';
					$out .= '</div>';
				$out .= '</form>';
			}
		
		$out .= '</div>';
	
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_form_item_viewhead_div($param) {

	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	if (!isset($param['n'])) $param['n'] = '1';
	if (!ctype_digit($param['n'])) $param['n'] = '1';
	$param['n'] = ''.intval($param['n']);
	$existing_images_count = my_get_item_picture_count($param['i']);
	if ($param['n'] > $existing_images_count) $param['n'] = '1';
	
	//

	$out = '';
	
	$out .= '<input type="hidden" id="form_item_viewhead_item_id" value="'.$param['i'].'" />';
	
	$out .= '<div id="form_item_viewhead_div" >';
	
		$out .= outhtml_form_item_viewhead_content($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_form_item_viewhead_process(&$param) {

	$param['ajp']['elemtoplace'] = 'form_item_viewhead_div';
	$param['html'] = outhtml_form_item_viewhead_content($param);
		
	return true;
}


// =============================================================================
function jqfn_form_item_viewhead($param) {

	if (!am_i_registered_user()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;
	
	if (!isset($param['n'])) $param['n'] = '1';
	if (!ctype_digit($param['n'])) $param['n'] = '1';
	$param['n'] = ''.intval($param['n']);
	$existing_images_count = my_get_item_picture_count($param['i']);
	if ($param['n'] > $existing_images_count) $param['n'] = '1';

	$out = '';
	
	$param['html'] = '';
	$param['ajp'] = array();
	$param['ajp']['callback'] = 'js_form_item_viewhead_callback';

	header('Content-Type: text/html; charset=utf-8');
	
	jqfn_form_item_viewhead_process(&$param);
		
	$out .= ajax_encode_prefix($param['ajp']);
	
	if (isset($param['ajp']['elemtoplace'])) {
		$out .= $param['html'];
	}

	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/form_item_viewhead.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			out_silent_error('here_ic');
			jqfn_form_item_viewhead($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>