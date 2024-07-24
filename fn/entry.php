<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/popup_zoom1.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/scroll_top.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');


// =============================================================================
function outhtml_servicepause_page() {

	$out = '';

	$out .= "<div class='msgrect msgerror'>";
	$out .= "<p>Сервер на обслуживании!</p>";
	$out .= "</div>";

	$out .= "</body>";
	$out .= "</html>";
	
	return $out.PHP_EOL;
}


// =============================================================================
function get_query_jqfn_name() {

	$prefix = '/xhr/';
	$suffix = '.php';
	$s = $_SERVER['SCRIPT_FILENAME'];
	$from = mb_strpos($s, $prefix);
	if ($from === false) return false;
	$from += mb_strlen($prefix);
	$to = mb_strpos($s, $suffix, $from);
	if ($to === false) return false;
	
	$r = mb_substr($s, $from, ($to - $from));

	$r = mb_str_replace($r, '/', '_');

	$z = mb_ereg_replace('[^a-z1-90_]', '', $r);
	
	if ($r != $z) return false;
	
	return $r;
}


// =============================================================================
function jqfn($func, $param) {

	// header('Content-Type: text/html; charset=utf-8');
	header('Cache-Control: no-cache, must-revalidate');
	header('Date: '.date('r'));
	// header('Expires: no-cache, must-revalidate');
	header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
	
	return $func($param);
}


// =============================================================================
function xhr($param) {

	
	
	$funcname = get_query_jqfn_name();
	if (function_exists($funcname)) {
		out_silent_error('xhrfn='.get_query_jqfn_name());
		return $funcname($param);
	}
	
	//out_silent_error('go behind');
	
	$funcname = 'localxhr';
	if (function_exists($funcname)) {
		// out_silent_error('fn='.get_query_jqfn_name());
		return $funcname($param);
		// out_silent_error('here behind');
	}

	if (!isset($param['fn'])) return false;
	if (mb_strlen($param['fn']) > 80) return false;
	$teststr = preg_replace('[^a-z0-9_]', '', $param['fn']);
	if ($teststr != $param['fn']) return false;

	$filename = $_SERVER["DOCUMENT_ROOT"].'/fn/'.$param['fn'].'.php';
	if (!is_readable($filename)) return false;
	//my_write_sys_log($filename);

	require_once($filename);

	$funcname = 'xhr_'.$param['fn'];
	if (!function_exists($funcname)) return false;
	return $funcname($param);
}

function outhtml_google_analytics_tag() {
return <<<SCRIPTSTRING
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-EMJ0NGFW0Q"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-EMJ0NGFW0Q');
</script>
SCRIPTSTRING;
}

// =============================================================================
function outhtml_head($param) {

	$out = '';
	
	$out .= "<head>".PHP_EOL;
	$out .= "<title>".$GLOBALS['pagetitle']."</title>".PHP_EOL;
	$out .= "<meta HTTP-EQUIV='Content-Type' CONTENT='text/html; charset=utf-8'>".PHP_EOL;
	
	// $out .= "<link rel='shortcut icon' href='/images/icon00.ico' type='image/x-icon'>".PHP_EOL;
	// $out .= "<link rel='shortcut icon' href='/images/icon00.png' type='image/x-icon'>".PHP_EOL;
	$out .= "<link rel='shortcut icon' href='/images/icon_flags.png' type='image/x-icon'>".PHP_EOL;
	//$out .= "<link rel='icon' href='/favicon.ico' type='image/x-icon'>".PHP_EOL;
	// $out .= "<link rel='icon' type='image/png' href='/images/icon00.png'>".PHP_EOL;
	$out .= "<link rel='icon' href='/images/icon_flags.png' type='image/png'>".PHP_EOL;
	
	$out .= '<link rel="apple-touch-icon" href="/images/touch-icon-iphone-ipad-57px.png" />'.PHP_EOL;
	$out .= '<link rel="apple-touch-icon" sizes="72x72" href="/images/touch-icon-iphone-ipad-72px.png" />'.PHP_EOL;
	$out .= '<link rel="apple-touch-icon" sizes="114x114" href="/images/touch-icon-iphone-ipad-114px.png" />'.PHP_EOL;
	$out .= '<link rel="apple-touch-icon" sizes="144x144" href="/images/touch-icon-iphone-ipad-144px.png" />'.PHP_EOL;
	
	$out .= "<link rel='stylesheet' href='".'/base.css'."' type='text/css'>".PHP_EOL;

	$out .= '<meta name="description" content="Каталог знаков кораблей России и СССР. Сервис для коллекционеров значков и знаков морской и кораблестроительной тематики. Полный список.">'.PHP_EOL;
	
	// $out .= '<script src="/jquery-1.9.1.js"></script>';
	foreach ($GLOBALS['head_scripts'] as $scripturl) {
		$out .= '<script src="'.$scripturl.'" type="text/javascript"></script>'.PHP_EOL;
	}

	$out .= "</head>".PHP_EOL;

	$out .= outhtml_google_analytics_tag().PHP_EOL;

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_special_divs($param) {
	
	$out = '';
	
	$out .= '<div id="popup_zoom1" style=" display: none; z-index: 21; position: absolute; left: 0px; top: 0px; width: auto; height: auto; background: #f0f0f0; color: #909090; border: solid 1px #e0e0e0; padding: 12px; border-radius: 4px; box-shadow: 0 2px 4px rgba(0,0,0,0.5); " >popup_zoom1</div>';
	
	$out .= '<div id="scroll_top_div" style=" " onclick=" scroll_top_scroll(); " ><div>наверх</div></div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_body_scripts($param) {

	$out = '';
	
	$out .= outhtml_script_ajax_base();
	
	$out .= outhtml_script_popup_zoom1();
	$out .= outhtml_script_popup_zoom1_content();
	
	$out .= outhtml_script_scroll_top();
	
	if ($GLOBALS['is_registered_user']) {

		require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_gotit_picto.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_wantit_picto.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/iurel_sellit_picto.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist_label.php');

		$out .= outhtml_script_iurel_gotit_picto();
		$out .= outhtml_script_iurel_wantit_picto();
		$out .= outhtml_script_iurel_sellit_picto();
		$out .= outhtml_script_item_inlist_label();
	}
	
	$out .= $GLOBALS['body_script_str'];
	
	$out .= '<script type="text/javascript" language="JavaScript"> window.onload = my_onload(); </script>'.PHP_EOL;
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_toptitle_2($param) {

	$out = '';
	
	$out .= '<div id="toptitle" style=" position: relative; margin-top: 0px; margin-bottom: 0px; height: 110px; ">';
	
		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '<div style=" position: absolute; width: 958px; height: 40px; top: 48px; left: 0px; text-align: center; z-index: 9999;  ">';
			$out .= '<a id="siteheadertitle" href="http://'.my_get_http_domain().'" style=" color: #3e6880; ">';
				$out .= 'Каталог знаков кораблей РФ и СССР';
			$out .= '</a>';
		$out .= '</div>';
	
		$out .= '<div style=" position: absolute; top: 6px; left: 421px; width: 118px; height: 102px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/anchors_z2.png\');  "></div>';
		
		$out .= '<div style=" float: right; text-align: right; padding-right: 20px; padding-top: 10px; ">';
			$out .= '<a class="header_title_href_link" href="http://'.my_get_http_domain().'">'.my_get_http_domain().'</a>';
		$out .= '</div>';
			
		$out .= '<div style=" clear: both; "></div>';
		
	$out .= '</div>';
	
	return $out.PHP_EOL;
}

// =============================================================================
function outhtml_toptitle($param) {

	$href = 'http://'.my_get_http_domain();

	$out = '';
	
	$out .= '<div id="toptitle" style=" position: relative; margin-top: 0px; margin-bottom: 0px; height: 110px; ">';
	
		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '<div style=" position: absolute; width: 410px; height: 60px; top: 32px; left: 0px; text-align: right; z-index: 9999;  ">';
			$out .= '<a id="siteheadertitle" href="'.$href.'" style="  ">';
				$out .= 'Каталог <br>знаков';
			$out .= '</a>';
		$out .= '</div>';
		
		$out .= '<div style=" position: absolute; width: 410px; height: 60px; top: 32px; right: 0px; text-align: left; z-index: 9999;  ">';
			$out .= '<a id="siteheadertitle" href="'.$href.'" style="  ">';
				$out .= 'кораблей <br>России и СССР';
			$out .= '</a>';
		$out .= '</div>';
	
		$out .= '<a style=" display: block; cursor: pointer; position: absolute; top: 6px; left: 421px; width: 118px; height: 102px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/anchors_z2.png\'); " href="'.$href.'"></a>';
		
		$out .= '<div style=" float: right; text-align: right; padding-right: 20px; padding-top: 10px; ">';
			$out .= '<a class="header_title_href_link" href="'.$href.'" >'.my_get_http_domain().'</a>';
		$out .= '</div>';
			
		$out .= '<div style=" clear: both; "></div>';
		
	$out .= '</div>';
	
	return $out.PHP_EOL;
	
	//

	$out = '';
	
	$out .= '<div id="toptitle" style=" position: relative; margin-top: 0px; margin-bottom: 0px; height: 110px; ">';
	
		$out .= '<div style=" clear: both; "></div>';
		
		$out .= '<div style=" position: absolute; width: 958px; height: 40px; top: 48px; left: 0px; text-align: center; z-index: 9999;  ">';
			$out .= '<a id="siteheadertitle" href="http://'.my_get_http_domain().'" style="  ">';
				$out .= 'Каталог знаков кораблей России и СССР';
			$out .= '</a>';
		$out .= '</div>';
	
		$out .= '<div style=" position: absolute; top: 6px; left: 421px; width: 118px; height: 102px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/anchors_z2.png\');  "></div>';
		
		$out .= '<div style=" float: right; text-align: right; padding-right: 20px; padding-top: 10px; ">';
			$out .= '<a class="header_title_href_link" href="http://'.my_get_http_domain().'">'.my_get_http_domain().'</a>';
		$out .= '</div>';
			
		$out .= '<div style=" clear: both; "></div>';
		
	$out .= '</div>';
	
	return $out.PHP_EOL;
	
	//

	if ($GLOBALS['user_id'] == 2) return outhtml_toptitle_2($param);
	
	$out = '';
	
	//
	$out .= '<div id="toptitle">';
	
		// $out .= '<div style=" background-repeat: no-repeat; background-position: 661px 0px; background-image: url(\'/images/knot.png\'); min-height: 104px; ">';
		$out .= '<div style=" background-repeat: no-repeat; background-position: 625px 9px; background-image: url(\'/images/anchors_z1.png\'); min-height: 104px; ">';

			//
			$out .= '<div style=" float: left; padding-left: 18px; margin-top: 67px; ">';
				/*
				$out .= '<a class="header_title_image_link" href="http://'.my_get_http_domain().'">';
			
					$out .= '<h1>';
						$out .= '<img src="/images/kzkcr_title.png" alt="'.'Каталог знаков кораблей РФ и СССР'.'" />';
					$out .= '</h1>';

				$out .= '</a>';
				*/
				$out .= '<a id="siteheadertitle" href="http://'.my_get_http_domain().'">';
					$out .= 'Каталог знаков кораблей РФ и СССР';
				$out .= '</a>';
			$out .= '</div>';

			//
			$out .= '<div style=" float: right; text-align: right; padding-right: 20px; padding-top: 80px; ">';
				$out .= '<a class="header_title_href_link" href="http://'.my_get_http_domain().'">'.my_get_http_domain().'</a>';
			$out .= '</div>';

		$out .= '</div>';

		// red bar
		//$out .= '<div style=" background-color: #682b30; min-height: 8px; ">';
		//$out .= '</div>';
		
	$out .= '</div>';
	
	// $out .= '</div>';
	
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_topmenu($param) {
	
	$out = '';
	
	$out .= '<div id="topmenu" >';

		$out .= '<div style=" float: left;">';

		if (!isset($param['m'])) $param['m'] = '0';
		
		// $out .= '<a '.(($param['m'] == '0')?'class="current"':'').' href="/index.php">'.'Главная'.'</a>';
		
		
			$out .= '<a '.(($param['m'] == 'c')?'class="current"':'').' href="/index.php?m=c">'.'Каталог'.'</a>';
			
		// var_dump($GLOBALS['is_registered_user']);
			
		if ($GLOBALS['is_registered_user']) {
			$out .= '<a '.(($param['m'] == 'i')?'class="current"':'').' href="/index.php?m=i">'.'Ваша помощь'.'</a>';

		
			$out .= '<a '.(($param['m'] == 'p')?'class="current"':'').' href="/index.php?m=p">'.'Личный кабинет'.'</a>';
			
			$out .= '<a '.(($param['m'] == 'u')?'class="current"':'').' href="/item/add.php?m=u" style="  ">'.'Загрузить'.'</a>';

			if (am_i_moderator() || am_i_lim_moderator()) {
				$out .= '<a '.(($param['m'] == 'm')?'class="current"':'').' href="/index.php?m=m">'.'М'.'</a>';
			}
			
			if (am_i_admin()) {
				$out .= '<a '.(($param['m'] == 'a')?'class="current"':'').' href="/index.php?m=a">'.'А'.'</a>';
			}

			

		}
		
		$out .= '<a '.(($param['m'] == 'h')?'class="current"':'').' href="/index.php?m=h">'.'Справка'.'</a>';
		
		$out .= '</div>';

		$out .= '<div style=" float: right;">';

			if ($GLOBALS['is_registered_user']) {

				$str = '';

				$link = '/index.php';
				$out .= '<form method="GET" enctype="multipart/form-data" action="'.$link.'" style=" display: inline; padding-right: 5px; white-space: nowrap; ">';

					$out .= '<input type="hidden" name="m" value="c" />';
					$out .= '<input type="hidden" id="prevsearch" value="text" />';

					$out .= '<input class="hoverwhiteborder" style=" text-align: left;  font-size: 11px; padding: 1px 4px 0px 4px; border-radius: 3px; -moz-border-radius: 3px; width: 80px; margin-right: 15px; " size="28" name="q" id="search_input" value="'.$str.'" />';

					$out .= '<button class="hoverwhiteborder" type="submit" name="search" value="text" style=" margin-right: 15px; background-color: #2e4e62; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #9eb2bf; padding: 1px 12px 0px 12px; width: 50px; " >Найти</button>';

				$out .= '</form>';
					
				
			}
			
			if (am_i_emailverified_user()) {
			
				$out .= '<a href="/index.php?logout=1" style=" margin-right: 0px; ">'.'Выход'.'</a>';
			}

		$out .= '</div>';
		
		$out .= '<div style=" clear: both; "></div>';
		
		if ($GLOBALS['submenu_html'] != '') {
			$out .= outhtml_submenu_real();
		}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_submenu($list) {
	
	$out = '';
	


		$out .= '<div style=" float: left; border: 0; margin: 0px; ">';

			foreach ($list as $e) {
				$out .= '<a '.(($e['current'] == true)?'class="current"':'').' href="'.$e['href'].'">'.$e['text'].'</a>';
			}
		
		$out .= '</div>';
		
		$out .= '<div style=" clear: left; "></div>';


	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_submenu_real() {

	$out = '';
	
	$out .= '<div id="submenu" >';

		$out .= '<div style=" margin-top: 4px; margin-bottom: 4px; height: 0px; border-top: 1px solid #2e4c5e; border-bottom: 1px solid #8295a2; "></div>';
		
	
	
		$out .= $GLOBALS['submenu_html'];
	
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_foot($param) {
	
	$out = '';
	
	$out .= '<div id="pagefooter" style=" height: 48px; font-size: 8pt; color: #a0a0a0; text-align: right; ">';
		$out .= '<div style=" padding: 24px 1px 36px 20px; ">';
			$out .= '&copy; 2013&ndash;2015 Коллектив webcollect.ru ';
		$out .= '</div>';
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_page_by_name($param) {

	$out = '';

	if (!isset($_SERVER['SCRIPT_NAME'])) return false;
	if (mb_strlen($_SERVER['SCRIPT_NAME']) > 200) return false;

	$filename = $_SERVER["DOCUMENT_ROOT"].$_SERVER['SCRIPT_NAME'];

	// print $filename;

	if (!is_readable($filename)) return false;
	//my_write_sys_log($filename);

	require_once($filename);

	$fn = $_SERVER['SCRIPT_NAME'];
	$fn = mb_str_replace($fn, '/', '_');
	$fn = mb_str_replace($fn, '.php', '');

	// print $fn;

	$funcname = 'outhtml'.$fn;
	if (!function_exists($funcname)) return false;
	
	$out = $funcname($param);
	if ($out === false) {
		return outhtml_welcome_screen($param);
	} else {
		return $out;
	}
}


// =============================================================================
function outhtml_content($param) {

	$out = outhtml_page_by_name($param);
	
	// если не указана страница сайта
	if ($out === false) {
		
		if (!isset($param['m'])) $param['m'] = '0';
		
		if ($param['m'] == 'c') {
			require_once($_SERVER['DOCUMENT_ROOT'].'/item/list.php');
			$out .= outhtml_item_list($param);
		}
		if ($param['m'] == 'i') {
			require_once($_SERVER['DOCUMENT_ROOT'].'/item/unidentified.php');
			$out .= item_unidentified($param);
		}
		if ($param['m'] == 'p') {
			require_once($_SERVER['DOCUMENT_ROOT'].'/personal/index.php');
			$out .= outhtml_personal_index($param);
		}
		if ($param['m'] == 'm') {
			require_once($_SERVER['DOCUMENT_ROOT'].'/moderation/index.php');
			$out .= outhtml_moderation_index($param);
		}
		if ($param['m'] == 'a') {
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/index.php');
			$out .= outhtml_administration_index($param);
		}
		if ($param['m'] == 'h') {
			require_once($_SERVER['DOCUMENT_ROOT'].'/help/index.php');
			$out .= outhtml_help_index($param);
		}
		if ($out === false) {
			require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
			$out .= outhtml_welcome_screen($param);
		}
	}

	return $out.PHP_EOL;
}

// =============================================================================
function print_html($param) {

	$GLOBALS['pagetitle'] = 'Каталог знаков кораблей России и СССР';
	
	$content = outhtml_content($param);

	$out = '';
	$out .= "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>".PHP_EOL;
	$out .= "<html>".PHP_EOL;
	$out .= outhtml_head($param);
	$out .= "<body>";
		$out .= outhtml_body_scripts($param);
		$out .= outhtml_special_divs($param);
		if (!isset($param['hidemenu'])) $out .= '<div id="wrapper">';
			if (!isset($param['hidemenu'])) $out .= outhtml_toptitle($param);
			if (!isset($param['hidemenu'])) $out .= outhtml_topmenu($param);
			if (!isset($param['hidemenu'])) $out .= '<div id="pagecontentdiv" >';
				$out .= $content;
				if (!isset($param['hidemenu'])) $out .= '<div style=" clear: both; "></div>';
			if (!isset($param['hidemenu'])) $out .= '</div>';
			if (!isset($param['hidemenu'])) $out .= outhtml_foot($param);
		if (!isset($param['hidemenu'])) $out .= '</div>';
	$out .= '</body></html>';

	//
	
	header('Content-Type: text/html; charset=utf-8');
	send_headers_nocache();
	print $out;

	return true;
}


// =============================================================================
function myentry_check_loggedin(&$param) {

	if (!isset($_COOKIE['ch'])) return false;
	if (!is_valid_hash($_COOKIE['ch'])) {
		// кривой хэш
		my_write_log('Bad cookie detected from IP '.$_SERVER['REMOTE_ADDR'].'', 'Q');
		return false;
	}

	//my_write_log('Entry line '.__LINE__.'');

	$qr = mydb_queryarray("".
		" SELECT user.user_id, user.time_last_request, ".
		" user.is_registered_user, user.email_verified, user.ip_address ".
		" FROM user ".
		" WHERE user.cookie_hash = '".$_COOKIE['ch']."'; ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) < 1) return false;
	
	if (sizeof($qr) > 1) {
		// несколько записей с одинаковым хэшем
		$strlist = '';
		for ($i = 0; $i < sizeof($qr); $i++) {
			$strlist .= ' (user_id='.$qr[$i]['user_id'];
			$strlist .= ', time_last_request='.$qr[$i]['time_last_request'];
			$strlist .= ', ip_address='.$qr[$i]['ip_address'].') ';
		}
		my_write_log('Duplicate cookie found for multiple users: '.$strlist, 'Q');
		my_write_log('Deleting cookie for multiple users', 'Q');
		$qr = mydb_query("".
			"UPDATE user ".
			"SET user.cookie_hash = '' ".
			"WHERE user.cookie_hash = '".$_COOKIE['ch']."'; ".
			"");
		return false;
	}
	
	// есть запись с таким хэшем
	//my_write_log('Entry line '.__LINE__.'');
	
	if (($qr[0]['is_registered_user'] == 'Y') || ($qr[0]['email_verified'] == 'Y')) {

		$session_age = ( time() - strtotime($qr[0]['time_last_request']) );
		
		if ($session_age > (my_get_session_lifetime() + (60 * 5))) {
			// сессия устарела
			my_write_log('User session expired '.__LINE__.'', '');
			return false;
		}
		
		if ($qr[0]['ip_address'] != $_SERVER['REMOTE_ADDR']) {
			// изменился адрес клиента
			//my_write_log('Entry line '.__LINE__.'');
			my_write_log('User changed ip address (user_id='.$qr[0]['user_id'].', from '.$qr[0]['ip_address'].', to '.$_SERVER['REMOTE_ADDR'].') on line '.__LINE__.'', 'Q');
			return false;
		}
	
	} else {
		// ??
	}
	
	//my_write_log('Entry line '.__LINE__.'');
	$GLOBALS['user_id'] = $qr[0]['user_id'];
	$GLOBALS['is_registered_user'] = ($qr[0]['is_registered_user'] == 'Y');
	//$GLOBALS['is_first_request'] = false;
	$qr = mydb_query("".
		"UPDATE user ".
		"SET user.time_last_request = '".date('Y-m-d H:i:s')."', ".
		" user.ip_address = '".$_SERVER['REMOTE_ADDR']."' ".
		"WHERE user.cookie_hash = '".$_COOKIE['ch']."'; ".
		"");
	if ($qr === false) return false;
	
	setcookie('ch', $_COOKIE['ch'], (time() + my_get_session_lifetime()), '/', 'webcollect.ru');

	// got thru it
	
	return true;
}


// =============================================================================
function myentry_try_login(&$param) {

	if (!isset($param['action'])) return false;
	if ($param['action'] != 'login') return false;
	
	if (!isset($param['email'])) return false;
	
	if (!isset($param['password'])) return false;
	

	//my_write_log('Entry line '.__LINE__.'');  // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	

	$loginkey = '';
	if (is_email_exist_in_db($param['email'])) {
		// go ahead
		// my_write_log('Is valid email');
		$loginkey = 'email';
	// } elseif (is_valid_username($param['email'])) {
	 } elseif (is_username_exist_in_db($param['email'])) {
		// go ahead
		// my_write_log('Is valid username');
		$loginkey = 'username';
	} else {
		// неверный имэйл или псевдоним или пароль
		return false;
	}
	
	
	if (is_valid_password($param['password'])) {
		// go ahead
		//my_write_log('Entry line '.__LINE__.'');
	} else {
		// неверный имэйл или псевдоним или пароль
		//my_write_log('Entry line '.__LINE__.'');
		return false;
	}
	
	
	$password_hash = hash('sha256', 'SaLt'.$param['password']);
	
	
	// prepared query
	
	$a = array();
	$q = "".
		"SELECT user.user_id, user.login_count ".
		"FROM user ";
	if ($loginkey == 'email') {
		$q .= "WHERE ( user.email_address = ? ) ";
	} 
	if ($loginkey == 'username') {
		$q .= "WHERE ( user.username = ? ) ";
	} 
	$q .= "AND ( ( user.is_registered_user = 'Y' ) ";
	$q .= " OR ( user.email_verified = 'Y' ) ) ";
	$q .= " AND ( user.password_hash = ? ) ";
	$a[] = $param['email'];
	$a[] = $password_hash;
	$t = 'ss';
	
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) return false;
	// prepared query END
	
	if (sizeof($qres) < 1) {
		// у нас таких нет
		// print 'no such user found';
		my_write_log('Incorrect login/password from '.$_SERVER['REMOTE_ADDR'].' ('.$param['email'].','.$param['password'].')', 'Q');
		return false;
	}
	
	if (sizeof($qres) > 1) {
		// несколько таких
		my_write_log('Duplicate users found using login/password '.' ('.$param['email'].','.$param['password'].')', 'Q');
		return false;
	}
	

	
	// prepared query 2
	$a = array();
	$q = "".
		" SELECT user.user_id, ".
		" user.time_last_request, ".
		" user.is_registered_user, ".
		" user.login_count, ".
		" user.ip_address, ".
		" user.cookie_hash ".
		" FROM user ".
		" WHERE ( user.user_id = ? ) ".
		"";
	$a[] = $qres[0]['user_id'];
	$t = 'i';
	$testqres = mydb_prepquery($q, $t, $a);
	if ($testqres === false) return false;
	if (sizeof($testqres) < 1) {
		my_write_log('No user found ('.$param['email'].','.$param['password'].')', 'Q');
		return false;
	}
	// prepared query 2 END
	

	if ((strtotime($testqres[0]['time_last_request']) + my_get_session_lifetime()) > time()) {
		if ($testqres[0]['cookie_hash'] != '') {
			my_write_log('Login at non-expired session '.' (victim_user_id='.$testqres[0]['user_id'].', victim_ip_address='.$testqres[0]['ip_address'].',  agressor_ip_address='.$_SERVER['REMOTE_ADDR'].')', 'Q');
		}
	}
	if ($testqres[0]['ip_address'] != $_SERVER['REMOTE_ADDR']) {
		my_write_log('User changed ip address '.' (user_id='.$testqres[0]['user_id'].',  from='.$testqres[0]['ip_address'].', to='.$_SERVER['REMOTE_ADDR'].') on line '.__LINE__.'', 'Q');
	}

	$GLOBALS['user_id'] = intval($testqres[0]['user_id']);
	$GLOBALS['login_count'] = intval($testqres[0]['login_count']);
	$GLOBALS['is_registered_user'] = ($testqres[0]['is_registered_user'] == 'Y');
	
	my_write_log('fetched login_count='.$testqres[0]['login_count'].''); 
	
	// check for ban

	if (am_i_banned()) {
		$GLOBALS['is_registered_user'] = false;
		$GLOBALS['user_id'] = false;
		return false;
	}

	// congratulate
	
	// my_write_log('Entry line '.__LINE__.''); 
	
	$s = 'SALT'.intval(time()).$_SERVER['REMOTE_ADDR'].$GLOBALS['user_id'];
	$hash = hash('sha256', $s);
	$GLOBALS['login_count']++;
	
	// my_write_log('new login_count='.$GLOBALS['login_count'].''); 
	
	$qr = mydb_query("".
		"UPDATE user ".
		"SET user.cookie_hash = '".$hash."', ".
		" user.time_last_request = '".date('Y-m-d H:i:s')."', ".
		" user.login_count = '".$GLOBALS['login_count']."', ".
		" user.ip_address = '".$_SERVER['REMOTE_ADDR']."' ".
		"WHERE user.user_id = '".$GLOBALS['user_id']."' ".
		"");
		
		
	if ($qr) {
		//
		$qr = mydb_query("".
			" DELETE FROM visitor ".
			" WHERE visitor.hash = '".$_COOKIE['ch']."'; ".
		"");
		//
		$qr = mydb_query("".
			" DELETE FROM visitor ".
			" WHERE visitor.hash = '".$hash."'; ".
		"");
		//
		setcookie('ch', $hash, (time() + my_get_session_lifetime()), '/', 'webcollect.ru');
		//
		my_write_log('Успешный вход пользователя в систему ('.$GLOBALS['user_id'].') в '.$GLOBALS['login_count'].' раз.', 'Q');
		//$GLOBALS["user_id"] = $param['user_id'];
		my_purge_old_sessions();
		
	} else {
		my_write_log('Ошибка (user_id='.$GLOBALS['user_id'].') на строке '.__LINE__.'', 'Q');
		//my_write_log('Entry line '.__LINE__.'');
		$GLOBALS['is_registered_user'] = false;
		$GLOBALS['user_id'] = false;
		return false;
	}
	
	return true;
}


// =============================================================================
function myentry_logout(&$param) {

	if (!isset($param['logout'])) return false;
	if ($param['logout'] != '1') return false;
	if (!am_i_emailverified_user()) return false;
	if (!$GLOBALS['user_id']) return false;

	// my_write_log('Entry line '.__LINE__.''); // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
	my_write_log('Выход пользователя из системы', 'Q');
	
	$qr = mydb_query("".
		"UPDATE user ".
		"SET user.cookie_hash = '', ".
		"user.time_last_request = '".date('Y-m-d H:i:s')."' ".
		"WHERE user.user_id = '".$GLOBALS['user_id']."'; ".
		"");
	if ($qr === false) return false;
		
	
	
	$GLOBALS['user_id'] = false;
	$GLOBALS['is_registered_user'] = false;
		
	return true;
}


// =============================================================================
/*
function myentry_firsttime_visitor(&$param) {

	if ($GLOBALS['user_id']) return false;
	
	//my_write_log('Entry line '.__LINE__.'');
	$qr = mydb_query("".
		"INSERT INTO user ".
		"SET user.ip_address = '".$_SERVER['REMOTE_ADDR']."', ".
		"user.time_last_request = '".date('Y-m-d H:i:s')."' ".
		"");
	if ($qr === false) return false;
	$GLOBALS['user_id'] = mydb_insert_id();
	
	$s = 'SALT'.intval(time()).$_SERVER['REMOTE_ADDR'].$GLOBALS['user_id'];
	$hash = hash('sha256', $s);
	$qr = mydb_query("".
		"UPDATE user ".
		"SET user.cookie_hash = '".$hash."' ".
		"WHERE user.user_id = '".$GLOBALS['user_id']."'; ".
		"");
	if ($qr === false) return false;
	setcookie('ch', $hash, (time() + my_get_cookie_lifetime()), '/', 'webcollect.ru');

	return true;
}
*/


// =============================================================================
function myentry_visitor_firsttime(&$param) {
	
	if ($GLOBALS['user_id']) return false;
	
	//
	
	$s = 'SALT'.intval(time()).$_SERVER['REMOTE_ADDR'].'';
	$hash = hash('sha256', $s);
	
	$qr = mydb_query("".
		" INSERT INTO visitor ".
		" SET visitor.ip_address = '".$_SERVER['REMOTE_ADDR']."', ".
		" visitor.time_last_request = '".date('Y-m-d H:i:s')."', ".
		" visitor.hash = '".$hash."' ".
		"");
	if ($qr === false) return false;
	
	$GLOBALS['visitor_id'] = mydb_insert_id();
	
	setcookie('ch', $hash, (time() + my_get_visitor_cookie_lifetime()), '/', 'webcollect.ru');

	return true;
}


// =============================================================================
function myentry_visitor_check(&$param) {

	if ($GLOBALS['user_id']) return false;

	if (!isset($_COOKIE['ch'])) return false;
	
	if (!is_valid_hash($_COOKIE['ch'])) {
		// кривой хэш
		my_write_log('Bad cookie detected from IP '.$_SERVER['REMOTE_ADDR'].'', 'Q');
		return false;
	}

	//my_write_log('Entry line '.__LINE__.'');

	$qr = mydb_queryarray("".
		"SELECT visitor.visitor_id, visitor.time_last_request, visitor.ip_address ".
		"FROM visitor ".
		"WHERE visitor.hash = '".$_COOKIE['ch']."'; ".
		"");
	if ($qr === false) return false;
	
	if (sizeof($qr) < 1) return false;
	
	if (sizeof($qr) > 1) {
		// несколько записей с одинаковым хэшем
		my_write_log('Duplicate cookie found at '.__LINE__.'');
		$qr = mydb_query("".
			" DELETE FROM visitor ".
			" WHERE visitor.hash = '".$_COOKIE['ch']."'; ".
			"");
		return false;
	}
	
	// есть запись с таким хэшем

	/*
	$session_age = ( time() - strtotime($qr[0]['time_last_request']) );
	
	if ($session_age > (my_get_visitor_cookie_lifetime() + (60 * 5))) {
		// сессия устарела
		return false;
	}
	*/
	
	
	$GLOBALS['visitor_id'] = $qr[0]['visitor_id'];

	//$GLOBALS['is_first_request'] = false;
	$qr = mydb_query("".
		" UPDATE visitor ".
		" SET visitor.time_last_request = '".date('Y-m-d H:i:s')."', ".
		" visitor.ip_address = '".$_SERVER['REMOTE_ADDR']."' ".
		" WHERE visitor.visitor_id = '".$GLOBALS['visitor_id']."'; ".
		"");
	if ($qr === false) return false;
	
	setcookie('ch', $_COOKIE['ch'], (time() + my_get_session_lifetime()), '/', 'webcollect.ru');

	// got thru it
	
	return true;
}


// =============================================================================
function myentry_visitor(&$param) {

	if ($GLOBALS['user_id']) return false;
	
	myentry_visitor_check($param);
	
	if (!$GLOBALS['visitor_id']) {
		myentry_visitor_firsttime($param);
	} else {
		$qr = mydb_query("".
		" UPDATE visitor ".
		" SET visitor.request_count = (visitor.request_count + 1) ".
		" WHERE visitor.visitor_id = '".$GLOBALS['visitor_id']."'; ".
		"");
		if ($qr === false) return false;
	}

	return true;
}


// =============================================================================
function myentry() {
	
	if (false) {
		// затычка
		header('Content-Type: text/html; charset=utf-8');
		print outhtml_servicepause_page(array());
		return true;
	}
	
	$_SERVER['REMOTE_ADDR'] = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);

	if ($_SERVER['REQUEST_METHOD'] == 'POST') { $param = $_POST; }
	elseif ($_SERVER['REQUEST_METHOD'] == 'GET') { $param = $_GET; }
	else { $param = array(); }
	
	$GLOBALS['user_id'] = false;
	$GLOBALS['visitor_id'] = false;
	$GLOBALS['is_registered_user'] = false;
	$GLOBALS['head_scripts'] = array();
	$GLOBALS['body_script_str'] = '';
	$GLOBALS['submenu_html'] = '';
	
	//
	if (isset($param['logout'])) {
		if ($param['logout'] != '1') {
			$param['logout'] = '0';
		} else {
			if (isset($param['action'])) {
				$param['action'] = 'logout';
			}
		}
	}
	if (isset($param['nh'])) {
		if ($param['nh'] != '1') {
			$param['nh'] = '0';
		}
	}
	
	//my_write_log('Entry line '.__LINE__.'');
	
	// проверка что это за пользователь
	myentry_check_loggedin($param);
	
	if (isset($param['action'])) {
		if ($param['action'] == 'login') {
			// Попытка аутентификации вводом пароля
			myentry_try_login($param);
		}
	}
	
	//my_write_log('Entry line '.__LINE__.'');
	

	
	if ($GLOBALS['user_id'] == false) {
		// незарегистрированный посетитель
		myentry_visitor($param);
		// myentry_firsttime_visitor($param);
	}
	
	if ($param['logout'] == '1') {
		// логаут
		myentry_logout($param);
	} else {
		unset($param['logout']);
	}

	//my_write_log('Entry line '.__LINE__.'');
	// my_write_log('http request');
	
	
	$jqfn_func = get_query_jqfn_name();
	if ($jqfn_func !== false) {
	
		$jqfn_func = 'jqfn_'.$jqfn_func;
		my_write_log('jqfn_func = '.$jqfn_func);
		if (function_exists($jqfn_func)) {
			return jqfn($jqfn_func, $param);
		}
	
	} else {
	
		$funcname = 'localxhr';
		//my_write_log('Entry line '.__LINE__.'');
		if (function_exists($funcname)) {
			//my_write_log('Entry line '.__LINE__.'');
			$basicnh = false;
			if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/') > 0) $basicnh = true;
			if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/item/image.php') > 0) $basicnh = true;
			if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/item/image_fresh.php') > 0) $basicnh = true;
			if ($basicnh) {
				//my_write_log('Entry line '.__LINE__.'');
				$param['nh'] = '1';
				//my_write_log('localxhr in');
			}
			//my_write_log('localxhr out');
		}
		
		if ($param['nh'] == '1') {
			xhr($param);
		} else {
			my_write_log('Page request '.$_SERVER['REQUEST_URI'], '');
			print_html($param);
		}
	
	}
	
	return true;
}


// =============================================================================
myentry();
	
?>