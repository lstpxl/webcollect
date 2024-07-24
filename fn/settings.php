<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

function my_get_session_lifetime() { return (60*60*3); }

function my_get_cookie_lifetime() { return (60*60*48); }

function my_get_visitor_cookie_lifetime() { return (60*60*24*31); }


// =============================================================================
function my_get_http_domain() {
	return 'navy.webcollect.ru';
}

// =============================================================================
function my_get_robot_email() {
	return 'noreply@'.my_get_http_domain();
}

// =============================================================================
function my_get_reply_email() {
	return 'admin@'.my_get_http_domain();
}

// =============================================================================
function my_get_siteroot_dir() {
	return '/home/wkh/1.wkh.z8.ru/docs';
}

// =============================================================================
function my_get_picture_storage_dir() {
	return my_get_siteroot_dir().'/itemimages';
}


// =============================================================================
function my_get_blueprint_storage_dir() {
	return my_get_siteroot_dir().'/blueprints';
}

// =============================================================================
function my_get_blueprint_export_dir() {
	return my_get_siteroot_dir().'/blueprintexport';
}

// =============================================================================
function my_get_blueprint_http_path() {
	return '/blueprints';
}


?>