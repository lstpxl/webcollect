<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/robo_upload.php');


// =============================================================================
function outhtml_script_batch_upload() {

$str = <<<SCRIPTSTRING

var do_robo_upload_query = false;

window.onload = my_batch_upload_on_load; 

function my_batch_upload_on_load() {
	setTimeout('batch_upload_refresh()', 1000);
}

function batch_upload_refresh() {
	setTimeout('batch_upload_refresh()', 1000);
	
	if (do_robo_upload_query) {
		js_batch_upload_query();
	}
}



function js_robo_upload_get(url, params) {
	
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


function js_batch_upload_query() {

	do_robo_upload_query = false;

	var url = '/xhr/robo_upload.php';
	
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
          var BlockNameIndex = (String(response).indexOf("blockid=") + 8);
          var BlockNameEnd = (String(response).indexOf(";", BlockNameIndex));
          var IndexClean = (String(response).indexOf("-->") + 3);
          var BlockName = String(response).substring(BlockNameIndex, BlockNameEnd);
          var htmlclean = String(response).substring(IndexClean, String(response).length);
		  
			if (BlockName == 'robo_upload_ok') {
				var elem = document.getElementById('batch_upload_status_div');
				if (elem) {
					// elem.innerHTML = elem.innerHTML + htmlclean;
					elem.innerHTML = htmlclean;
					do_robo_upload_query = true;
				} else {
					alert('Неизвестная ошибка 2');
				}
			} else {
				if (BlockName == 'robo_upload_fail') {
					var elem = document.getElementById('batch_upload_status_div');
					if (elem) {
						// elem.innerHTML = elem.innerHTML + htmlclean;
						elem.innerHTML = htmlclean;
						alert('Завершено');
					} else {
						alert('Неизвестная ошибка 3');
					}
				} else {
					if (BlockName == 'robo_upload_finished') {
						var elem = document.getElementById('batch_upload_status_div');
						if (elem) {
							// elem.innerHTML = elem.innerHTML + htmlclean;
							elem.innerHTML = htmlclean;
							alert('Завершено');
						} else {
							alert('Неизвестная ошибка 4');
						}
					} else {
						alert('Неизвестная ошибка 1');
					}
				}
			}
        }
      }
    } catch (e) {}
  }
  XMLHttpRequestObject.send(null);
}



function js_batch_upload_start() {
	do_robo_upload_query = true;
}

SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function outhtml_admin_batch_upload($param) {
	
	$out = '';
	
	if (!am_i_admin()) {
		return outhtml_welcome_screen($param);
	}
	
	$GLOBALS['pagetitle'] = 'Пакетная загрузка / '.$GLOBALS['pagetitle'];
	
	//
	
	$out .= outhtml_script_batch_upload();
	
	//
	
	$out .= '<div style=" background-color: #f8f8f8; padding-left: 0px; " >';
		
		$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 40px 5px 10px 0px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-bottom: 20px; padding-left: 18px; ">Пакетная загрузка (/batchuploadsrc/*)</h1>';
			
			$list = get_batch_upload_file_list();
			
			$out .= '<div id="batch_upload_status_div" style=" margin-left: 18px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; border: solid 1px #a0a0a0; min-height: 400px; font-size: 10pt; font-family: \'Courier New\', Courier, monospace; color: #606060; " >';
			
				$out .= '<p>Изображений для автозагрузки: <span style=" padding: 4px; background-color: #f0f0a0; color: #000000; font-weight: bold; ">'.sizeof($list).'<span></p>';
				$out .= '<p>Ожидание...</p>';
			
			$out .= '</div>';
			
			$out .= '<div style=" margin-left: 18px; margin-top: 15px; margin-bottom: 30px; vertical-align: top; ">';
			
				$out .= '<button class="hoverwhiteborder" type="none" name="delete_button" style="background-color: #d88d88; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #532026; padding: 2px 12px 3px 12px; min-width: 130px; " onclick=" js_batch_upload_start(); return false; ">Запуск</button>';
				
			$out .= '</div>';
		
		$out .= '</div>';

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>