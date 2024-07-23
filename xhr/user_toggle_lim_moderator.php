<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function outhtml_script_user_toggle_lim_moderator() {

$str = <<<SCRIPTSTRING

function js_user_toggle_lim_moderator_query(user_id, modify) {

	var url = '/xhr/user_toggle_lim_moderator.php?user_id=' + user_id + '&modify=' + modify + '';
	
	var XMLHttpRequestObject = false;
  if (window.XMLHttpRequest) {
    try {
      XMLHttpRequestObject = new XMLHttpRequest();
    } catch (e) {}
  } else if (window.ActiveXObject) {
    try {
      XMLHttpRequestObject = new ActiveXObject('Msxml2.XMLHTTP');
    } catch (e) {
      try {
        XMLHttpRequestObject = new ActiveXObject('Microsoft.XMLHTTP');
      } catch (e) {}
    }
  }
  if (!XMLHttpRequestObject) return false;
  XMLHttpRequestObject.open('GET', url, true);
  XMLHttpRequestObject.onreadystatechange = function() {
    try {
      if (XMLHttpRequestObject.readyState == 4) {
        if (XMLHttpRequestObject.status == 200) {
          var response = XMLHttpRequestObject.responseText;
          delete XMLHttpRequestObject;
          XMLHttpRequestObject = null;
		  if (String(response).indexOf("blockid=") > 0) {
			  var BlockNameIndex = (String(response).indexOf("blockid=") + 8);
			  var BlockNameEnd = (String(response).indexOf(";", BlockNameIndex));
			  var IndexClean = (String(response).indexOf("-->") + 3);
			  var BlockName = String(response).substring(BlockNameIndex, BlockNameEnd);
			  var htmlclean = String(response).substring(IndexClean, String(response).length);
			  document.getElementById(BlockName).innerHTML = htmlclean;
			  document.getElementById(BlockName).style.visibility = 'visible';
		  }
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_user_toggle_lim_moderator_result($param) {

	$out = '';
	
	if (!isset($param['user_id'])) return false;
	if (!ctype_digit($param['user_id'])) return false;
	$param['user_id'] = ''.intval($param['user_id']);
		
	// текущее значение
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, user.is_superadmin, user.is_lim_moderator ".
		" FROM user ".
		" WHERE user.user_id = '".$param['user_id']."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	//
	
	$out .= '<!--';
	$out .= 'blockid=user_toggle_lim_moderator_div_id'.$param['user_id'].';';
	$out .= '-->';
	
	//
	
	$modify = am_i_admin();
	
	//
	
	if ($qr[0]['is_lim_moderator'] == 'Y') {
		$str = 'да';
		$style = ' background-color: #009db0; color: #ffffff; ';
	} else {
		$str = 'нет';
		$style = ' color: #808080; ';
	}
	
	//
	
	$out .= '<div>';
	
	if ($modify) {
		$onclick_insert = ' onclick="js_user_toggle_lim_moderator_query('.$param['user_id'].', \'y\'); return false; " ';
		$out .= '<a href="#" style=" display: block; '.$style.' font-size: 11px; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding: 0px 3px 0px 3px; min-width: 30px; text-align: center; " '.$onclick_insert.' >';
		$out .= '<nobr>'.$str.'</nobr>';
		$out .= '</a>';
	} else {
		$out .= '<span style=" display: block; '.$style.' font-size: 11px; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding: 0px 3px 0px 3px; min-width: 30px; text-align: center; " >';
		$out .= '<nobr>'.$str.'</nobr>';
		$out .= '</span>';
	}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_user_toggle_lim_moderator_div($param) {

	$out = '';
	
	$out .= '<div id="user_toggle_lim_moderator_div_id'.$param['user_id'].'">';
	
	$out .= outhtml_user_toggle_lim_moderator_result($param);
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function try_update_user_toggle_lim_moderator(&$param) {

	if (!am_i_admin()) return false;

	if (!isset($param['user_id'])) return false;
	if (!ctype_digit($param['user_id'])) return false;
	$param['user_id'] = ''.intval($param['user_id']);
	
	if (!isset($param['modify'])) return false;
	if ($param['modify'] != 'y') return false;
	
	// текущее значение
	
	$qr = mydb_queryarray("".
		" SELECT user.user_id, user.is_superadmin, user.is_lim_moderator ".
		" FROM user ".
		" WHERE user.user_id = '".$param['user_id']."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	if ($qr[0]['is_superadmin'] == 'Y') return false;
	
	//
	
	if ($qr[0]['is_lim_moderator'] == 'Y') {
		$newval = 'N';
	} else {
		$newval = 'Y';
	}
	
	
	$qru = mydb_query("".
		" UPDATE user ".
		" SET user.is_lim_moderator = '".$newval."' ".
		" WHERE user.user_id = '".$param['user_id']."' ". 
		"");
	if (!$qru) {
		my_print_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return true;
}


// =============================================================================
function jqfn_user_toggle_lim_moderator($param) {

	$out = '';

	try_update_user_toggle_lim_moderator(&$param);

	$out .= outhtml_user_toggle_lim_moderator_result($param);

	header('Content-Type: text/html; charset=utf-8');
		
	print $out;

	return true;
}


// =============================================================================
if (!function_exists('localxhr')) {
	function localxhr($param) {
		jqfn_user_toggle_lim_moderator($param);
		return true;
	}
}


if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/user_toggle_lim_moderator.php') > 0) {
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>