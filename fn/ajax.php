<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');


// =============================================================================
function ajax_encode_prefix($arr) {

	$out = '';
	$out .= '<!--';
	foreach ($arr as $key => $value) {
		$out .= ''.$key.'='.$value.';';
	}
	$out .= '-->';
	return $out;
}


// =============================================================================
function outhtml_script_ajax_base() {

	$str = <<<SCRIPTSTRING
	
function ajax_my_get_query(url) {

	if (typeof url != 'string') return false;

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
					if (String(response).length < 1) return false;
					var z = ajax_my_decode_prefix(response);
					if (!z) return false;
					if (typeof z != 'object') return false;
					if (typeof z['callback'] == 'string') {
						var funcstr = z['callback'];
						var func = window[funcstr];
						if (func) {
							if (typeof func == 'function') {
								func(z);
							}
						}
					}
				}
			}
		} catch (e) {}
	}
	XMLHttpRequestObject.send(null);
}


function ajax_my_post_query(url, params) {

	if (typeof url != 'string') return false;
	if (typeof params != 'object') params = [];
	
	var count = 0;
	var encstr = '';
	for (var v in params) {
		if (count > 0) encstr += '&';
		encstr += (v + '=');
		encstr += encodeURIComponent(params[v]);
		count++;
	}

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
	
	XMLHttpRequestObject.open('POST', url, true);
	XMLHttpRequestObject.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	
	XMLHttpRequestObject.onreadystatechange = function() {
		try {
			if (XMLHttpRequestObject.readyState == 4) {
				if (XMLHttpRequestObject.status == 200) {
					var response = XMLHttpRequestObject.responseText;
					delete XMLHttpRequestObject;
					XMLHttpRequestObject = null;
					if (String(response).length < 1) return false;
					var z = ajax_my_decode_prefix(response);
					if (!z) return false;
					if (typeof z != 'object') return false;
					if (typeof z['callback'] == 'string') {
						var funcstr = z['callback'];
						var func = window[funcstr];
						if (func) {
							if (typeof func == 'function') {
								func(z);
							}
						}
					}
				}
			}
		} catch (e) {}
	}
	
	XMLHttpRequestObject.send(encstr);
}


function ajax_my_decode_prefix(response) {

	if (typeof response != 'string') return false;
	
	var io = String(response).indexOf('<!--');
	if (io < 0) return false;
	
	var ic = String(response).indexOf('-->', io);
	if (ic < 1) return false;
	
	var result = new Array();
	result['tail'] = String(response).substring((ic + 3), String(response).length);
	
	var pairstr = String(response).substring((io + 4), ic);
	
	var pairs = pairstr.split(';');
	
	var paircount = pairs.length;
	var element = null;
	for (var i = 0; i < paircount; i++) {
		element = pairs[i];
		var ear = element.split('=');
		if (ear.length == 2) {
			if (String(ear[0]).length > 0) {
				result[String(ear[0])] = String(ear[1]);
			}
		}
	}
	
	return result;
}


function my_get_url(url, params) {
	// my_get_url('/file.php', {a:'value', b:value});
	var temp = document.createElement('form');
	temp.action = url;
	temp.method = 'GET';
	temp.style.display = 'none';
	for (var x in params) {
		var opt = document.createElement('textarea');
		opt.name = x;
		opt.value = params[x];
		temp.appendChild(opt);
	}
	document.body.appendChild(temp);
	temp.submit();
	return temp;
}


function is_numeric(text) {

	if (typeof text == 'number') {
		var t = '' + text;
	} else if (typeof text == 'string') {
		var t = '' + text;
	} else {
		return false;
	}
	
	if (t.length < 1) return false;
	var res = '';
	var numbers = '_0123456789';
	var arr = t.split('');
	var l = arr.length;
	if (l > 5) l = 5;
	for (var i=0; i < l; i++) {
		if (String(numbers).indexOf(arr[i]) > 0) res += arr[i];
	}
	return (t == res);
}

function my_onload() {

	if (typeof init_searchbar_slide === 'function') {
		init_searchbar_slide();
	}
	
	return true;
}

SCRIPTSTRING;

	$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

	return $str;
}


?>