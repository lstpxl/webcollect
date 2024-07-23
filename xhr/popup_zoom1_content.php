<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_popup_zoom1_content() {

$str = <<<SCRIPTSTRING

function js_popup_zoom1_content_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
			
		}
	}

	if (typeof aresp['display'] == 'string') {
		var elem = document.getElementById('popup_zoom1');
		if (elem) {
			if (aresp['display'] == 'yes') {
				elem.style.display = 'block';
			}
			if (aresp['display'] == 'no') {
				elem.style.display = 'none';
			}
		}
	}
	
	return true;
}


function js_popup_zoom1_content_query(item_id) {
	var url = '/xhr/popup_zoom1_content.php?i=' + item_id + '';
	return ajax_my_get_query(url);
}


function popup_zoom1_content_close() {
	var elem = document.getElementById('popup_zoom1');
	if (elem) {
		elem.style.display = 'none';
		elem.innerHTML = '';
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function my_get_itemimage_size($param) {

	// i = item_id
	// n = image index
	// s = size (o - original, m - list, l - large, s - small)
	
	if (!isset($param['i'])) return pass_denied_image($param['s']);
	if (!ctype_digit($param['i'])) return pass_denied_image($param['s']);
	
	$sizelist = array('m', 'l', 's', 'o');
	if (!isset($param['s'])) $param['s'] = $sizelist[0];
	if (!in_array($param['s'], $sizelist)) $param['s'] = $sizelist[0];
	
	// my_write_log('Image line '.__LINE__.'');
	
	if (!isset($param['n'])) $param['n'] = '1';
	if (!ctype_digit($param['n'])) $param['n'] = '1';
	
	$filename = my_get_item_picture_filepath($param['i'], $param['n'], $param['s']);
	
	if ($filename === false) return false;
	
	$is = getimagesize($filename);

	return $is;
}


// =============================================================================
function outhtml_popup_zoom1_content_result(&$param) {
	
	$out = '';
	
	$out .= '<input type="hidden" id="popup_zoom1_content_item_id" value="'.$param['i'].'" />';
	
	$p = array('i' => $param['i'], 'n' => 1, 's' => 'o');
	$is = my_get_itemimage_size($p);
	$zpw = $is[0];
	$zph = $is[1];
	
	$out .= '<input type="hidden" id="zoomedpic_width" value="'.$zpw.'" />';
	$out .= '<input type="hidden" id="zoomedpic_height" value="'.$zph.'" />';
	
	$out .= '<input type="hidden" id="zoomedpic_max_width" value="'.'200'.'" />';
	$out .= '<input type="hidden" id="zoomedpic_max_height" value="'.'300'.'" />';
	
	$out .= '<div id="zoomedpic" style=" width: 400px; height: 300px; background-color: #ffffff; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/item/image.php?i='.$param['i'].'&n=1&s=o\'); ">';
	
		$out .= '<div style=" position:relative; display: block; width: 400px; height: 300px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " >';
		$out .= '</div>';
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_popup_zoom1_content($param) {

	if (!am_i_registered_user()) return false;
	if (!am_i_admin()) return false;
	
	if (!isset($param['i'])) return false;
	if (!ctype_digit($param['i'])) return false;
	$param['i'] = ''.intval($param['i']);
	if (my_get_item_status($param['i']) === false) return false;

	$out = '';
	
	$param['ajp'] = array();
	$param['ajp']['elemtoplace'] = 'popup_zoom1';
	$param['ajp']['callback'] = 'js_popup_zoom1_content_callback';
	$param['ajp']['display'] = 'yes';

	header('Content-Type: text/html; charset=utf-8');
	
	$html = outhtml_popup_zoom1_content_result($param);
	
	$out .= ajax_encode_prefix($param['ajp']);
	$out .= $html;
	print $out;

	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/popup_zoom1_content.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_popup_zoom1_content($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>