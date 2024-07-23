<?php

//global $mydb_link_object;
global $mysqli;
//$mydb_link_object = null;
$mysqli = null;

// =============================================================================
function mydb_init() {

	global $mysqli;
	if ($mysqli) return true;

	$filename = '/home/wkh/db_conf_navy';
	
	$fhandle = fopen($filename, "rb")
		or die('Could not read db config file');
	$contents = fread($fhandle, filesize($filename))
		or die('Could not read db config file');
	fclose($fhandle);
	parse_str($contents, $arr);
	
	if (!isset($arr['server_address'])) die('Could not read db config');
	if (!isset($arr['user'])) die('Could not read db config');
	if (!isset($arr['password'])) die('Could not read db config');
	if (!isset($arr['dbname'])) die('Could not read db config');
	
	
	$mysqli = new mysqli($arr['server_address'], $arr['user'], $arr['password'], $arr['dbname']);
	if ($mysqli->connect_errno) {
		die('Could not connect to database.'.$mysqli->connect_errno.") ".$mysqli->connect_error);
	}
	if (!$mysqli->set_charset('utf8')) {
		die('Could not select database charset.'.$mysqli->error);
	}
	if (!$mysqli->query('SET character_set_client="utf8";')) {
		die('Could not select database character_set_client.'.$mysqli->error);
	}
	if (!$mysqli->query('SET character_set_results="utf8";')) {
		die('Could not select database character_set_results.'.$mysqli->error);
	}
	if (!$mysqli->query('SET collation_connection="utf8_unicode_ci";')) {
		die('Could not select database collation_connection.'.$mysqli->error);
	}
	
	return true;
}


// =============================================================================
function mydb_query($q) {

	if (mb_strlen($q) < 1) return false;

	global $mysqli;
	if (!$mysqli) mydb_init();
	$result = $mysqli->query($q);
	if (!$result) {
		die( "DB query error: ".$mysqli->error);
	}
	return $result;
}

// =============================================================================
function mydb_insert_id() {

	global $mysqli;
	if (!$mysqli) return false;

	return $mysqli->insert_id;
}


// =============================================================================
function mydb_queryarray($q) {

	if (mb_strlen($q) < 1) return false;
	
	$result = mydb_query($q);
	if ($result === false) return false;
	$d = array();
	while($row = $result->fetch_array(MYSQLI_ASSOC)) $d[] = $row;
	$result->free();
	return $d;
}


// =============================================================================
function mydb_prepquery($q, $t, $a) {

	if (!is_string($q)) return false;
	if (mb_strlen($q) < 1) return false;
	if (!is_string($t)) return false;
	if (!is_array($a)) return false;
	
	//print '_1';

	global $mysqli;
	if (!$mysqli) mydb_init();
	if (!$mysqli) return false;
	$stmt = $mysqli->prepare($q);
	if (!$stmt) return false;
	
	//out_silent_error("PQE ok 2");
	//print '_2';
	
	$x = mb_strlen($t);
	for ($i = 0; $i < $x; $i++) if (!isset($a[$i])) {
		//print '('.mb_strlen($t).','.sizeof($a).','.$i.')';
		//print_r($a);
		return false;
	}
	
	//out_silent_error("PQE ok 3");
	//print '_3';
	
	if ($x == 0) {
		// no params
	} elseif ($x == 1) {
		$stmt->bind_param($t, $a[0]);
	} elseif ($x == 2) {
		$stmt->bind_param($t, $a[0], $a[1]);
	} elseif ($x == 3) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2]);
	} elseif ($x == 4) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3]);
	} elseif ($x == 5) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4]);
	} elseif ($x == 6) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5]);
	} elseif ($x == 7) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6]);
	} elseif ($x == 8) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7]);
	} elseif ($x == 9) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8]);
	} elseif ($x == 10) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9]);
	} elseif ($x == 11) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10]);
	} elseif ($x == 12) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11]);
	} elseif ($x == 13) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12]);
	} elseif ($x == 14) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13]);
	} elseif ($x == 15) {
		$stmt->bind_param($t, $a[0], $a[1], $a[2], $a[3], $a[4], $a[5], $a[6], $a[7], $a[8], $a[9], $a[10], $a[11], $a[12], $a[13], $a[14]);
	} else {
		// too many params
		return false;
	}
	
	//out_silent_error("PQE ok 4");
	//print '_4';
	
    $stmt->execute();
	
	$rz = explode(' ', trim($q), 2);
	
	if (mb_strtolower($rz[0]) == 'select') {
		$result = $stmt->get_result();
		if ($result === false) return false;
		if ($result === true) return true;
		$d = array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) $d[] = $row;
		$result->free();
		return $d;
	}
	
	//out_silent_error("PQE ok 5");
	//print '_5';
	
	if (mb_strtolower($rz[0]) == 'delete') {
		// if ($stmt->affected_rows > 0);
		return ($stmt->errno == 0);
	}
	
	//out_silent_error("PQE ok 6");
	//print '_6';
	
	if (mb_strtolower($rz[0]) == 'insert') {
		// if ($stmt->affected_rows > 0);
		// printf("Errorn: %s\n", $stmt->errno);
		return ($stmt->errno == 0);
	}
	
	//out_silent_error("PQE ok 7");
	//print '_7';
	
	if (mb_strtolower($rz[0]) == 'update') {
		// if ($stmt->affected_rows > 0);
		return ($stmt->errno == 0);
	}	
	
	return false;
}


// =============================================================================
/*
function mydb_real_escape_string($str) {
	global $mysqli;
	if (!$mysqli) mydb_init();
	$s = stripslashes($str);
	$r = $mysqli->real_escape_string($s);
	return $r;
}
*/

?>