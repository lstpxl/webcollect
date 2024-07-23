<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/dec2any.php');


// =============================================================================
function send_headers_nocache() {
	header('Cache-Control: no-cache, must-revalidate');
	header('Date: '.date('r'));
	header('Expires: Sat, 11 May 2009 05:00:00 GMT');
	return true;
}


// =============================================================================
function send_headers_2weeks() {
	// header('Cache-Control: private, max-age=1209600');
	header('Cache-Control: max-age=1209600');
	// header('Cache-Control: public');
	header('Date: '.date('r'));
	header('Expires: '.date('r', (time() + 1209600)));
	return true;
}


// =============================================================================
function outhtml_error($str) {
	return '<p class="error_message">'.$str.'</p>';
}


// =============================================================================
function my_print_error($str) {
	print outhtml_error($str);
}


// =============================================================================
function out_silent_error($str) {
	return my_write_log($str, 'E');
}


// =============================================================================
function out_silent_error_userinput($str) {
	return my_write_log($str, 'W');
}


// =============================================================================
function my_write_log($s, $type='N') {

	$uv = 'V';
	$user_id = 0;
	$visitor_id = 0;
	
	if (isset($GLOBALS['user_id'])) {
		if ($GLOBALS['user_id'] > 0) {
			$user_id = $GLOBALS['user_id'];
			$uv = 'U';
		}
	}
	if (isset($GLOBALS['visitor_id'])) {
		if ($GLOBALS['visitor_id'] > 0) {
			$visitor_id = $GLOBALS['visitor_id'];
			$uv = 'V';
		}
	}
	
	// prepared query
	$a = array();
	$q = "".
		" INSERT INTO log ".
		" SET log.time = ?, ".
		" log.remote_ip = ?, ".
		" log.uv = ?, ".
		" log.user_id = ?, ".
		" log.visitor_id = ?, ".
		" log.type = ?, ".
		" log.message = ?; ".
		";";
		
	$a[] = date('Y-m-d H:i:s');
	$a[] = $_SERVER['REMOTE_ADDR'];
	$a[] = $uv;
	$a[] = $user_id;
	$a[] = $visitor_id;
	$a[] = $type;
	$a[] = $s;
	
	$t = 'sssiiss';
	$qres = mydb_prepquery($q, $t, $a);
	
	return $qres;
	
}


// =============================================================================
/*
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email) {

	// mb_ereg_match ??????????????????????????????

	$isValid = true;
	$atIndex = strrpos($email, "@");
	if (is_bool($atIndex) && !$atIndex) {
		$isValid = false;
	} else {
		$domain = substr($email, ($atIndex+1));
		$local = substr($email, 0, $atIndex);
		$localLen = strlen($local);
		$domainLen = strlen($domain);
		if ($localLen < 1 || $localLen > 64) {
			// local part length exceeded
			$isValid = false;
		} else if ($domainLen < 1 || $domainLen > 255) {
			// domain part length exceeded
			$isValid = false;
		} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
			// local part starts or ends with '.'
			$isValid = false;
		} else if (preg_match('/\\.\\./', $local)) {
			// local part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
			// character not valid in domain part
			$isValid = false;
		} else if (preg_match('/\\.\\./', $domain)) {
			// domain part has two consecutive dots
			$isValid = false;
		} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
			// character not valid in local part unless 
			// local part is quoted
			if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
				$isValid = false;
			}
		}
		if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
			// domain not found in DNS
			$isValid = false;
		}
	}
	return $isValid;
}


// =============================================================================
function is_valid_http_link($s) {
	return (filter_var($url, FILTER_VALIDATE_URL) !== FALSE);
}


// =============================================================================
function is_valid_email($s) {
	//return (preg_match("/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/", $s) > 0);
	return validEmail($s);
}


// =============================================================================
function is_valid_hash($s) {
	if (mb_strlen($s) != 64) return false;
	return (ctype_alnum($s));
}


// =============================================================================
function is_valid_password($s) {
	if (mb_strlen($s) < 5) return false;
	if (mb_strlen($s) > 80) return false;
	return true;
}


// =============================================================================
function is_username_exist_in_db($username) {

	// prepared query
	$a = array();
	$q = "".
		" SELECT user.user_id ".
		" FROM user ".
		" WHERE ( user.username = ? ) ".
		" AND ( user.is_registered_user = 'Y' ) ".
		"";
	$a[] = $username;
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) {
		die('is_username_exist_in_db() fatal error');
	}
	
	return (sizeof($qres) > 0);
}



// =============================================================================
function is_email_exist_in_db($email) {

	// prepared query
	$a = array();
	$q = "".
		" SELECT user.user_id ".
		" FROM user ".
		" WHERE ( user.email_address = ? ) ".
		" AND ( user.email_verified = 'Y' ); ".
		"";
	$a[] = $email;
	$t = 's';
	$qres = mydb_prepquery($q, $t, $a);
	
	if ($qres === false) {
		die('is_email_exist_in_db() fatal error');
	}
	
	return (sizeof($qres) > 0);
}


//==============================================================================
function mb_str_replace($haystack, $search, $replace, $offset=0, $encoding='UTF-8') {
  
  $len_sch = mb_strlen($search,$encoding);
  $len_rep = mb_strlen($replace,$encoding);
  
  while (($offset=mb_strpos($haystack,$search,$offset,$encoding))!==false){
      $haystack=mb_substr($haystack,0,$offset,$encoding)
          .$replace
          .mb_substr($haystack,$offset+$len_sch,1000,$encoding);
      $offset=$offset+$len_rep;
      if ($offset>mb_strlen($haystack,$encoding))break;
  }
  return $haystack;
}


// =============================================================================
function my_clean_string_ac($str) {

	mb_regex_encoding('UTF-8');
	$str = mb_ereg_replace('[^А-ЯA-Zа-яa-z1-90]', ' ', $str);
	$str = str_replace('    ', ' ', $str);
	$str = str_replace('  ', ' ', $str);
	$str = trim($str);
	return $str;
}



// =============================================================================
function my_simplify_text_string($str) {
	$str = mb_strtolower($str);
	$str = str_replace('ё', 'е', $str);
	mb_regex_encoding('UTF-8');
	$str = mb_ereg_replace('[^а-яa-z1-90]', ' ', $str);
	$str = str_replace('    ', ' ', $str);
	$str = str_replace('  ', ' ', $str);
	$str = trim($str);
	return $str;
}


// =============================================================================
function prep_string_for_search($str) {
	
	$str = trim($str);
	$str = mb_strtolower($str);

	$str = str_replace('ё', 'е', $str);
	
	mb_regex_encoding('UTF-8');
	$str = mb_ereg_replace('[^а-яёa-z1-90]', ' ', $str);
	
	$str = str_replace('    ', ' ', $str);
	$str = str_replace('  ', ' ', $str);
	$str = trim($str);
	
	//$str = mb_str_replace($str, 'ё', 'е');
	// $str = str_replace('ё', 'е', $str);
	
	// remove very short lexem
	$arr = explode(' ', $str, 20);
	$z = '';
	for ($i = 0; $i < sizeof($arr); $i++) {
		if (mb_strlen($arr[$i]) >= 1) $z .= $arr[$i].' ';
	}
	$z = trim($z);
	$str = $z;
	
	return $str;
}


// ============================================================================
function my_remove_folder_recurse($dirpath, $deletecontainer = true) {

	$overallresult = true;

	if (!is_dir($dirpath)) return true;
	$dirlist = array_diff(scandir($dirpath), array('..', '.'));
	if ($dirlist === false) return false;

	//$clean = true;
	foreach ($dirlist as $element) {
		$fp = $dirpath.'/'.$element;
		if (is_dir($fp)) {
			//print outhtml_message('%'.$fp);
			$r = my_remove_folder_recurse($fp, true);
			if (!$r) $overallresult = false;
		}
		if (is_file($fp)) {
			$ur = unlink($fp);
			if (!$ur) $overallresult = false;
		}
	}
	
	if ($deletecontainer) {
		$r = rmdir($dirpath);
		if (!$r) $overallresult = false;
	}
	
	return $overallresult;
}


// =============================================================================
function unlinkRecursive($dir, $deleteRootToo) {
    if (!$dh = @opendir($dir)) return false;
    while (false !== ($obj = readdir($dh))) {
        if($obj == '.' || $obj == '..') continue;
        if (!@unlink($dir . '/' . $obj)) unlinkRecursive($dir.'/'.$obj, true);
    }
    closedir($dh);
    if ($deleteRootToo) @rmdir($dir);
   
    return true;
} 



function my_month_text($i, $case=2) {
	$i = intval($i);
	if (($i < 1) || ($i > 12)) return false;
	// Именительный
	if ($case == 1) {
		if ($i == 1) return "январь";
		if ($i == 2) return "февраль";
		if ($i == 3) return "март";
		if ($i == 4) return "апрель";
		if ($i == 5) return "май";
		if ($i == 6) return "июнь";
		if ($i == 7) return "июль";
		if ($i == 8) return "август";
		if ($i == 9) return "сентябрь";
		if ($i == 10) return "октябрь";
		if ($i == 11) return "ноябрь";
		if ($i == 12) return "декабрь";
	}
	// Родительный
	if ($case == 2) {
		if ($i == 1) return "января";
		if ($i == 2) return "февраля";
		if ($i == 3) return "марта";
		if ($i == 4) return "апреля";
		if ($i == 5) return "мая";
		if ($i == 6) return "июня";
		if ($i == 7) return "июля";
		if ($i == 8) return "августа";
		if ($i == 9) return "сентября";
		if ($i == 10) return "октября";
		if ($i == 11) return "ноября";
		if ($i == 12) return "декабря";
	}
	// Предложный
	if ($case == 6) {
		if ($i == 1) return "январе";
		if ($i == 2) return "феврале";
		if ($i == 3) return "марте";
		if ($i == 4) return "апреле";
		if ($i == 5) return "мае";
		if ($i == 6) return "июне";
		if ($i == 7) return "июле";
		if ($i == 8) return "августе";
		if ($i == 9) return "сентябре";
		if ($i == 10) return "октябре";
		if ($i == 11) return "ноябре";
		if ($i == 12) return "декабре";
	}
}

?>