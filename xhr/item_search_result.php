<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/item_inlist.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/searchbar_slide_script.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/cmselector.php');


// =============================================================================
function outhtml_script_item_search_result() {

$str = <<<SCRIPTSTRING

var item_search_result_str = '';
var TimeToUpdate = 0;

function js_item_search_result_query(byid, pn, mode) {

	if (typeof(pn) === 'undefined') pn = 0;
	if (typeof(mode) === 'undefined') mode = 'search';

	if (byid == 'y') {
		var elem = document.getElementById('searchnum_input');
		if (elem) {
			var str = elem.value;
		} else {
			var str = '';
		}
	} else if (byid == 'n') {
		var elem = document.getElementById('search_input');
		if (elem) {
			var str = elem.value;
		} else {
			var str = '';
		}
	} else {
		alert('Что это?');
	}
	
	var sort = 'c';
	var elem = document.getElementById('resultsort_selector');
	if (elem) {
		var z = elem.value;
		if (z == 'c') sort = 'c';
		if (z == 'a') sort = 'a';
	}
	
	// byid, sort
	var url = '/xhr/item_search_result.php?pn=' + pn + '&byid=' + byid + '&sort=' + sort + '&mode=' + mode + '&q=' + str + '';
	
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


function item_search_result_gotopn(pn) {

	var mode = 'search';

	var elem = document.getElementById('item_search_result_jq_mode_elem');
	if (elem) {
		var str = elem.value;
		if (str == 'browse') {
			mode = 'browse';
		}
	}
	
	var searchform = document.getElementById('searchform');
	if (searchform) {
		
		var pnelem = document.createElement('input');
		pnelem.name = 'pn';
		pnelem.value = pn;
		pnelem.style.display = 'none';
		searchform.appendChild(pnelem);
		
		js_item_search_do();
	}
	
	return true;
}


function js_item_search_do() {

	var searchform = document.getElementById('searchform');
	if (searchform) {
	
		var prevsearch = document.getElementById('prevsearch');
		if (prevsearch) {
			var opt = document.createElement('input');
			opt.name = 'search';
			opt.value = prevsearch.value;
			opt.style.display = 'none';
			searchform.appendChild(opt);
			
			prevsearch.parentNode.removeChild(prevsearch);
			
			searchform.submit();
		}
	}
	
	return true;
}


function validateSearchNumInput() {

	var elem = document.getElementById('searchnum_input');
	if (elem) {
	
		var text = elem.value;
		var res = '';
		var numbers = '_0123456789';
		var arr = text.split('');
		var l = arr.length;
		if (l > 5) l = 5;
		for (var i=0; i < l; i++) {
			if (String(numbers).indexOf(arr[i]) > 0) res += arr[i];
		}
		if (text != res) {
			elem.value = res;
		}
	}
	
	return true;
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function get_classify_section_list() {
	
	$q = "SELECT shipmodelclass.shipmodelclass_id AS id, ".
		" shipmodelclass.text ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.parent_id = '0' ".
		" ORDER BY shipmodelclass.ti_subsort, shipmodelclass.text ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}


// =============================================================================
function outhtml_item_list_sidebar($param) {

	$out = '';
	
	$out .= outhtml_script_item_search_result();
	
	//

	$out .= '<div style=" background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';
		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
		
		$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Поиск</strong></p>';
		
		$out .= '</div>';
		
		$out .= '<div style=" vertical-align: top; padding-right: 5px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="search_input" id="search_input" onkeypress=" if (event.keyCode == 13) { js_item_search_result_query(\'n\', 0); return false; } " value="'.''.'" /></div>';
		
		/*
		$out .= '<p style=" margin-bottom: 6px; margin-top: 15px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Фильтр</strong></p>';
		
		$out .= '<p style=" margin-bottom: 6px; " >Параметры</p>';
		
		*/
		
		
			
		
		$out .= '<div style=" padding-top: 24px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="dosearch" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 130px; " onclick=" js_item_search_result_query(\'n\', 0); return false; " >Искать</button></div>';

	$out .= '</div>';
	
	
	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
		
		$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Поиск по каталожному номеру</strong></p>';
		
		$out .= '</div>';
		
		$out .= '<div style=" ">';
		
		$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; padding-right: 5px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 80px; " size="10" name="searchnum_input" id="searchnum_input" onkeypress=" if (event.keyCode == 13) { js_item_search_result_query(\'y\', 0); return false; } " value="'.''.'" /></div>';
				
		$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; "><button class="hoverwhiteborder" type="none" name="dosearchnum" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #205326; padding: 2px 12px 3px 12px; min-width: 100px; " onclick=" js_item_search_result_query(\'y\', 0); return false; " >Искать</button></div>';
		
		$out .= '<div style=" clear: left; "></div>';
		
		$out .= '</div>';

	$out .= '</div>';
	
	//

	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
		
			$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Упорядочить</strong></p>';
		
		$out .= '</div>';
		
		$out .= '<div style=" ">';
		
			$setlist = array();
			$setlist[] = array('v' => 'c', 'text' => 'Упорядочить по классификации');
			$setlist[] = array('v' => 'a', 'text' => 'Упорядочить по алфавиту');
			$out .= '<select name="sort_selector" style=" width: 230px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; border: solid 1px #000000; font-size: 9pt; ">';
			for ($i = 0; $i < sizeof($setlist); $i++) {
				$out .= '<option value="'.$setlist[$i]['v'].'">'.$setlist[$i]['text'].'</option>';
			}
			$out .= '</select>';
			
			/*
		
			$out .= '<input type="hidden" name="resultsort_selector" id="resultsort_selector" value="'.'a'.'" />';
		
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; "><button class="hoverwhiteborder" id="button_sort_a" type="submit" name="button_sort_a" value="1" style="background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #ffffff; padding: 2px 6px 3px 6px; min-width: 30px; " onclick=" js_item_search_sort_switch(\'a\'); return false; " >по алфавиту</button></div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; vertical-align: top; "><button class="hoverwhiteborder"  id="button_sort_c" type="submit" name="button_sort_c" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #205326; padding: 2px 6px 3px 6px; min-width: 30px; " onclick=" js_item_search_sort_switch(\'c\'); return false; " >по классификации</button></div>';
			
			$out .= '<div style=" clear: left; "></div>';
			
			*/
		
		$out .= '</div>';
		
	$out .= '</div>';
	
	//
	
	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
		
			$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Все / Моя коллекция</strong></p>';
		
		$out .= '</div>';
		
		$out .= '<div style=" ">';
		
			$setlist = array();
			$setlist[] = array('id' => 0, 'text' => 'Все');
			$setlist[] = array('id' => 1, 'text' => 'Мои интересы');
			$setlist[] = array('id' => 2, 'text' => 'У меня есть');
			$setlist[] = array('id' => 3, 'text' => 'Ищу');
			$setlist[] = array('id' => 4, 'text' => 'Меняю/продаю');
			$out .= '<select name="mycoll_selector" style=" width: 200px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; border: solid 1px #000000; font-size: 9pt; ">';
			for ($i = 0; $i < sizeof($setlist); $i++) {
				$out .= '<option value="'.$setlist[$i]['id'].'">'.$setlist[$i]['text'].'</option>';
			}
			$out .= '</select>';
		
			/*
		
			$out .= '<input type="hidden" name="mycoll_filter_selector" id="mycoll_filter_selector" value="'.'a'.'" />';
		
			$out .= '<div style=" float: left; margin-right: 10px; margin-bottom: 8px; vertical-align: top; "><button class="hoverwhiteborder" id="button_sort_a" type="submit" name="mycoll_a" value="1" style="background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #ffffff; padding: 2px 6px 3px 6px; min-width: 30px; " onclick=" js_item_search_sort_switch(\'a\'); return false; " >все</button></div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; margin-bottom: 8px; vertical-align: top; "><button class="hoverwhiteborder"  id="button_sort_c" type="submit" name="mycoll_b" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #205326; padding: 2px 6px 3px 6px; min-width: 30px; " onclick=" js_item_search_sort_switch(\'c\'); return false; " >все мои</button></div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; margin-bottom: 8px; vertical-align: top; "><button class="hoverwhiteborder"  id="button_sort_c" type="submit" name="mycoll_b" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #205326; padding: 2px 6px 3px 6px; min-width: 30px; " onclick=" js_item_search_sort_switch(\'c\'); return false; " >у меня есть</button></div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; margin-bottom: 8px; vertical-align: top; "><button class="hoverwhiteborder"  id="button_sort_c" type="submit" name="mycoll_b" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #205326; padding: 2px 6px 3px 6px; min-width: 30px; " onclick=" js_item_search_sort_switch(\'c\'); return false; " >ищу</button></div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; margin-bottom: 8px; vertical-align: top; "><button class="hoverwhiteborder"  id="button_sort_c" type="submit" name="mycoll_b" value="1" style="background-color: #bdc8b8; border-radius: 3px; -moz-border-radius: 3px; font-size: 10px; vertical-align: bottom; color: #205326; padding: 2px 6px 3px 6px; min-width: 30px; " onclick=" js_item_search_sort_switch(\'c\'); return false; " >меняю/продаю</button></div>';
			
			$out .= '<div style=" clear: left; "></div>';
			
			*/
		
		$out .= '</div>';
		
	$out .= '</div>';
	
	//
	
	$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';

		$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
		
			$out .= '<p style=" margin-bottom: 6px; " ><strong style=" font-size: 11pt; color: #f0f0f0; ">Раздел</strong></p>';
		
		$out .= '</div>';
		
		$out .= '<div style=" ">';
		
			$tcllist = get_classify_section_list();
			$out .= '<select name="section_selector" style=" width: 240px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; border: solid 1px #000000; font-size: 9pt; ">';
			$out .= '<option value="0">'.'Все'.'</option>';
			for ($i = 0; $i < sizeof($tcllist); $i++) {
				$out .= '<option value="'.$tcllist[$i]['id'].'">'.$tcllist[$i]['text'].'</option>';
			}
			$out .= '</select>';
		
		$out .= '</div>';
		
	$out .= '</div>';
	
	//
		
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_item_list_searchbar($param) {

	$out = '';
	
	//
	
	if (!am_i_registered_user()) {
		$link = '/index.php';
		$out .= '<form method="GET" id="searchform" enctype="multipart/form-data" action="'.$link.'">';
			$out .= '<input type="hidden" name="m" value="c" />';
			$out .= '<input type="hidden" id="prevsearch" value="browse" />';
			$out .= '<input type="hidden" name="q" id="search_input" value="" />';
			$out .= '<input type="hidden" name="num" id="searchnum_input" value="" />';
			$out .= '<input type="hidden" name="my" id="" value="0" />';
			$out .= '<input type="hidden" name="tcl" id="" value="0" />';
			$out .= '<input type="hidden" name="sort" id="" value="c" />';
			$out .= '<input type="hidden" name="unknown" id="" value="a" />';
		$out .= '</form>';
		return $out;
	};

	// border-top: solid 4px #000000;
	$out .= '<div id="searchbar" class="graysearchbar" style=" color: #c0c0c0; font-size: 11pt; color: #f0f0f0; ">';
	
	$out .= '<div style=" padding: 20px; ">';
	
		//
		
		$link = '/index.php';
		$out .= '<form method="GET" id="searchform" enctype="multipart/form-data" action="'.$link.'">';
		
		if (isset($param['m'])) {
			$out .= '<input type="hidden" name="m" value="'.$param['m'].'" />';
		}
		
		$allowed = array('browse', 'text', 'num');
		if (!isset($param['search'])) $param['search'] = $allowed[0];
		if (!in_array($param['search'], $allowed)) $param['search'] = $allowed[0];
		$out .= '<input type="hidden" id="prevsearch" value="'.$param['search'].'" />';
		if ($param['search'] != 'text') $param['q'] = '';
		if ($param['search'] != 'num') $param['num'] = '';
		if ($param['search'] == 'num') {
			$param['my'] = '0';
			$param['tcl'] = '0';
			$param['sort'] = 'c';
		}
	
		// текстовый поиск
	
		$out .= '<div style=" float: left; width: 70px; padding-top: 2px; padding-right: 10px; ">';
		$out .= '<p style=" text-align: right; ">Поиск</p>';
		$out .= '</div>';
		
		$out .= '<div style=" float: left; margin-right: 10px; ">';
			
			$str = htmlspecialchars($param['q'], ENT_QUOTES);
			$out .= '<input class="hoverwhiteborder" style=" text-align: left;  font-size: 12px; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 277px; " size="28" name="q" id="search_input" value="'.$str.'" />';
			// onkeypress=" if (event.keyCode == 13) { js_item_search_result_query(\'n\', 0); return false; } "
			
		$out .= '</div>';
		
		$out .= '<div style=" float: left; ">';
		
			$out .= '<button class="hoverwhiteborder" type="submit" name="search" value="text" style="background-color: #2e4e62; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #9eb2bf; padding: 2px 12px 3px 12px; width: 80px; " >Найти</button>';
			// onclick=" js_item_search_result_query(\'n\', 0); return false; "
			
		$out .= '</div>';
		
		// поиск по номеру
		
		$out .= '<div style=" float: right; margin-left: 10px; ">';
		
			$out .= '<button class="hoverwhiteborder" type="none" name="search" value="num" style="background-color: #2e4e62; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #9eb2bf; padding: 2px 12px 3px 12px; width: 80px; " >Найти</button>';
			// onclick=" js_item_search_result_query(\'y\', 0); return false; "
			
		$out .= '</div>';

		$out .= '<div style=" float: right; margin-left: 10px; ">';
		
			$str = ''.intval($param['num']);
			if ($str == '0') $str = '';
			$out .= '<input class="hoverwhiteborder" type="number" style=" text-align: left;  font-size: 12px; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; width: 60px; " size="8" name="num" id="searchnum_input" value="'.$str.'" onkeyup=" validateSearchNumInput(); " />';
			// onkeypress=" if (event.keyCode == 13) { js_item_search_result_query(\'y\', 0); return false; } "
			
		$out .= '</div>';
		
		$out .= '<div style=" float: right; padding-top: 2px; ">';
		$out .= '<p style=" ">Поиск по каталожному номеру</p>';
		$out .= '</div>';
		
		// eol
		
		$out .= '<div style=" clear: both; height: 15px; "></div>';
		
		// все / моя коллекция
	
		$out .= '<div style=" float: left; width: 80px; padding-top: 0px; ">';
		$out .= '<p style=" ">Все / Мои</p>';
		$out .= '</div>';
		
		$out .= '<div style=" float: left; margin-right: 10px; ">';
		
			$setlist = array();
			$setlist[] = array('id' => 0, 'text' => 'Все');
			$setlist[] = array('id' => 1, 'text' => 'Мои интересы');
			$setlist[] = array('id' => 2, 'text' => 'У меня есть');
			$setlist[] = array('id' => 3, 'text' => 'Ищу');
			$setlist[] = array('id' => 4, 'text' => 'Меняю/продаю');
			$setlist[] = array('id' => 5, 'text' => 'Неидентифицированные');
			
			$allowed = array();
			for ($i = 0; $i < sizeof($setlist); $i++) $allowed[] = $setlist[$i]['id'];
		
			if (!isset($param['my'])) $param['my'] = '0';
			if (!in_array($param['my'], $allowed)) $param['my'] = '0';

			$out .= '<select class="hoverwhiteborder" name="my" style=" width: 180px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 9pt; " onChange=" js_item_search_do(); return false; " >';
			for ($i = 0; $i < sizeof($setlist); $i++) {
				$ins = ($param['my'] == $setlist[$i]['id'])?' selected ':'';
				$out .= '<option '.$ins.' value="'.$setlist[$i]['id'].'">'.$setlist[$i]['text'].'</option>';
			}
			$out .= '</select>';
		
		$out .= '</div>';
		
		// раздел классификации
		
		/*
		
		if ($GLOBALS['user_id'] != 2) {
		
			$out .= '<div style=" float: left; padding-top: 0px; margin-left: 50px; margin-right: 10px;">';
			$out .= '<p style=" ">Раздел</p>';
			$out .= '</div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; ">';
			
				$tcllist = get_classify_section_list();
				array_unshift($tcllist, array('id' => '0', 'text' => 'Все'));
				
				$allowed = array();
				for ($i = 0; $i < sizeof($tcllist); $i++) $allowed[] = $tcllist[$i]['id'];
				
				if (!isset($param['tcl'])) $param['tcl'] = '0';
				if (!in_array($param['tcl'], $allowed)) $param['tcl'] = '0';
				
				$out .= '<select class="hoverwhiteborder" name="tcl" style=" width: 240px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 9pt; " onChange=" js_item_search_do(); return false; " >';
				for ($i = 0; $i < sizeof($tcllist); $i++) {
					$ins = ($param['tcl'] == $tcllist[$i]['id'])?' selected ':'';
					$out .= '<option '.$ins.' value="'.$tcllist[$i]['id'].'">'.$tcllist[$i]['text'].'</option>';
				}
				$out .= '</select>';
				
			$out .= '</div>';
		
		}
		*/
		
		//
		
		// раздел классификации v2
		
		//if ($GLOBALS['user_id'] == 2) {
		
			$out .= outhtml_script_cmselector();
		
			$out .= '<div style=" float: left; padding-top: 0px; margin-left: 50px; margin-right: 10px;">';
				$out .= '<p style=" ">Раздел</p>';
			$out .= '</div>';
			
			
			$out .= '<div style=" float: left; margin-right: 10px; ">';
			
				if (!isset($param['cms'])) $param['cms'] = 'c0';
			
				$out .= '<input type="hidden" id="cmselectorinput" name="cms" value="'.$param['cms'].'" />';
				
				$a = array();
				$a['element'] = $param['cms'];
				$a['sel'] = $param['cms'];
				$a['state'] = 'close';
				$out .= outhtml_cmselector_div($a);
			
			$out .= '</div>';
		
		//}
		
		//
		
		// сортировка
		
		$out .= '<div style=" float: right; margin-left: 10px; ">';
		
			// $out .= '<input type="hidden" name="sort" id="resultsort_selector" value="'.'c'.'" />';
			
			$allowed = array('c', 'a', 's');
			if (!isset($param['sort'])) $param['sort'] = 'c';
			if (!in_array($param['sort'], $allowed)) $param['sort'] = 'c';
			
			$setlist = array();
			$setlist[] = array('v' => 'c', 'text' => 'По классификации');
			$setlist[] = array('v' => 'a', 'text' => 'По алфавиту');
			$setlist[] = array('v' => 's', 'text' => 'По сериям');

			$out .= '<select class="hoverwhiteborder" name="sort" id="resultsort_selector" style=" width: 150px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 9pt; " onChange=" js_item_search_do(); return false; " >';
			for ($i = 0; $i < sizeof($setlist); $i++) {
				$ins = ($param['sort'] == $setlist[$i]['v'])?' selected ':'';
				$out .= '<option '.$ins.' value="'.$setlist[$i]['v'].'">'.$setlist[$i]['text'].'</option>';
			}
			$out .= '</select>';
			
		$out .= '</div>';
		
		// Неидентифицированные
		
		$out .= '<div style=" float: right; margin-left: 10px; ">';
		
			$allowed = array('a', 'u');
			if (!isset($param['unknown'])) $param['unknown'] = 'a';
			if (!in_array($param['unknown'], $allowed)) $param['unknown'] = 'a';
			
			$setlist = array();
			$setlist[] = array('v' => 'a', 'text' => 'Все');
			$setlist[] = array('v' => 'u', 'text' => 'Неидентифицированные');

			$out .= '<select class="hoverwhiteborder" name="unknown" id="unknown_selector" style=" width: 55px; overflow: hidden; background-color: #f0f0f0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 9pt; " onChange=" js_item_search_do(); return false; " >';
			for ($i = 0; $i < sizeof($setlist); $i++) {
				$ins = ($param['unknown'] == $setlist[$i]['v'])?' selected ':'';
				$out .= '<option '.$ins.' value="'.$setlist[$i]['v'].'">'.$setlist[$i]['text'].'</option>';
			}
			$out .= '</select>';
			
		$out .= '</div>';
		
		// eol
		
		$out .= '<div style=" clear: both; "></div>';
		
		//
		
		if (am_i_superadmin()) {
		// if ($GLOBALS['user_id'] == 2) {
		
			// Чужие
			
			$out .= '<div style=" float: left; width: 80px; padding-top: 0px; ">';
				$out .= '<div style=" text-align: right; padding-right: 12px; ">ЛК</div>';
				// $out .= 'ЛК';
			$out .= '</div>';
			
			$out .= '<div style=" float: left; margin-right: 10px; ">';
			
				$ulist = mydb_queryarray("".
					" SELECT user.user_id, ".
					" user.email_address, user.username ".
					" FROM user ".
					" WHERE ( user.is_registered_user = 'Y' ) ".
					" AND ( user.user_id != 0 ) ".
					" ORDER BY (user_id != ".$GLOBALS['user_id']."), username ".
					"");
				if ($ulist === false) {
					my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
				if (sizeof($ulist) < 1) {
					my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
					return false;
				}
				
				$ulist[0]['user_id'] = '0';
				$ulist[0]['username'] = 'Свой ЛК';
				
				$out .= '<select class="hoverwhiteborder" name="extuser" style=" width: 180px; overflow: hidden; background-color: #a0a0a0; color: #303030; border-radius: 3px; -moz-border-radius: 3px; font-size: 9pt; " onChange=" js_item_search_do(); return false; " >';
				for ($i = 0; $i < sizeof($ulist); $i++) {
					$ins = ($param['extuser'] == $ulist[$i]['user_id'])?' selected ':'';
					$out .= '<option '.$ins.' value="'.$ulist[$i]['user_id'].'">'.$ulist[$i]['username'];
					if ($ulist[$i]['user_id'] > 0) {
						$out .= ' ('.$ulist[$i]['email_address'];
						$out .= ') ';
					}
					$out .= '</option>';
				}
				$out .= '</select>';
			
			$out .= '</div>';
			
			$out .= '<div style=" clear: both; "></div>';
		
		}
		
		//
		
		$out .= '</form>';
		
	$out .= '</div>';
	
	$out .= '</div>';

		
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_search_result_input_result($param) {
	
	$out = '';
	
	$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="shipclass_input" id="shipclass_input"  onchange="item_search_result_test()" onkeydown="item_search_result_test()" onkeyup="item_search_result_test()" value="" /></div>';
	
	return $out.PHP_EOL;
}

// =============================================================================
function get_item_search_result_page_size() {
	return (5*32);
}


// =============================================================================
function outhtml_item_search_result_paginator($param, $total) {

	if (!isset($param['pn'])) $param['pn'] = 0;
	if (!ctype_digit($param['pn'])) $param['pn'] = 0;
	
	$pagesize = get_item_search_result_page_size();
	
	// 
	
	if (isset($param['byid'])) {
		if ($param['byid'] == 'y') {
			$out .= '<input type="hidden" id="item_search_result_jq_byid_elem" value="y" />';
		}
	}
	
	if (isset($param['mode'])) {
		if ($param['mode'] == 'browse') {
			$out .= '<input type="hidden" id="item_search_result_jq_mode_elem" value="browse" />';
		}
	}
	
	$out .= outhtml_uni_paginator_v2($pagesize, 8, 'item_search_result_gotopn', $param['pn'], $total).PHP_EOL;
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_search_result_selector_result($param) {

	$out = '';
	
	$out .= '<!--';
	$out .= 'blockid=item_search_result_selector_div;';
	$out .= '-->';
	
	if (!isset($param['shipclass_id'])) $param['shipclass_id'] = '0';
	$param['shipclass_id'] = ''.intval($param['shipclass_id']);
	if (!isset($param['pn'])) $param['pn'] = '0';
	$param['pn'] = ''.intval($param['pn']);
	
	$psize = get_item_search_result_page_size();
	
	$q = "SELECT COUNT(shipmodelclass.shipmodelclass_id) AS n ".
		" FROM shipmodelclass ".
		" WHERE shipmodelclass.parent_id = '".$param['shipclass_id']."' ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$totalsize = $qr[0]['n'];
	
	if ($totalsize == 0) {
		$out .= '<div style=" ">';
		$out .= 'нет элементов';
		$out .= '</div>';
		return $out.PHP_EOL;
	}
	
	$totalpages = ceil($totalsize / $psize);
	if ($totalpages == 0) $totalpages = 1;
	
	if ($param['pn'] >= $totalpages) {
		$param['pn'] = ($totalpages - 1);
	}
	
	$from = intval($param['pn']) * get_item_search_result_page_size();
	
	$q = "SELECT shipmodelclass.shipmodelclass_id, ".
		" shipmodelclass.text, shipmodelclass.parent_id ".
		"FROM shipmodelclass ".
		"WHERE shipmodelclass.parent_id = '".$param['shipclass_id']."' ".
		" ORDER BY shipmodelclass.text ".
		" LIMIT ".$from.", ".$psize." ".   
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) < 1) {
		my_print_error("Ошибка запроса к БД! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	$out .= '<div style=" ">';
	
	for ($i = 0; $i < sizeof($qr); $i++) {
		// class="hovergrayborder"
		// background-color: #f8f8f8;
		$out .= '<a href="#" id="item_search_result_id'.$qr[$i]['shipmodelclass_id'].'"  class="hovernounderline hovergrayborder hoverwhitebackground" style=" display: block; color: #606060; font-size: 11px; border-radius: 2px; -moz-border-radius: 2px;  margin-top: 1px; margin-right: 1px; padding-left: 3px;  width: 180px; " onclick="item_search_result_use('.$qr[$i]['shipmodelclass_id'].'); return false;">';
		$s = ''.$qr[$i]['text'];
		if (mb_strlen($s) > 27) $s = mb_substr($s, 0, 27).'...';
		$out .= $s;
		$out .= '</a>';
	}

	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_search_result_input_div($param) {

	$out = '';
	
	if (isset($param['i'])) {
		$param['i'] = ''.intval($param['i']);
		$qr = mydb_queryarray("".
			" SELECT item.item_id, item.shipmodelclass_id, item.shipmodelclass_str ".
			" FROM item ".
			" WHERE item.item_id = '".$param['i']."' ".
			"");
		if ($qr === false) {
			my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		if (sizeof($qr) != 1) {
			my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
			return false;
		}
		$str = $qr[0]['shipmodelclass_str'];
		$id = $qr[0]['shipmodelclass_id'];
		$item_id = $param['i'];
		$color = '#fff4ae'; // yellow
		if ($qr[0]['shipmodelclass_id'] > 0) {
			$basename = my_get_shipclass_name($qr[0]['shipmodelclass_id']);
			if ($basename !== false) {
				if ($basename == trim($str)) {
					$color = '#d6ffd5'; // green
				}
			}
		}
	} else {
		$str = '';
		$id = 0;
		$item_id = 0;
		$color = '#ffffff';
		$basename = '';
	}
	
	$out .= '<div id="item_search_result_input_div">';
	
	$out .= '<input type="hidden" name="item_search_result_item_id" id="item_search_result_item_id" value="'.$item_id.'" />';
	$out .= '<input type="hidden" name="item_search_resultected_id" id="item_search_resultected_id" value="'.$id.'" />';
	
	$out .= '<table><tr><td style=" vertical-align: top; ">';
	
	$out .= '<div style=" vertical-align: top; padding-right: 5px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: '.$color.'; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="30" name="shipclass_input" id="shipclass_input"  onchange="item_search_result_test()" onkeydown="item_search_result_test()" onkeyup="item_search_result_test()" value="'.$str.'" /></div>';
	
	$out .= '</td><td>';
	
	$out .= '<img src="/images/find.png" onclick=" item_search_result_open(); " style=" margin-top: 4px; margin-right: 15px; " alt="выбрать" />';
	
	// $out .= $id;
	
	if ($id != 0) {
		$out .= '<img src="/images/resultset_previous.png" onclick=" item_search_result_goup(); " style=" margin-top: 4px; margin-right: 15px; " alt="на уровень выше" />';
	}
	
	// добавить в список
	if (can_i_moderate_item($param['i'])) {
		$out .= '<img src="/images/database_add.png" onclick=" item_search_result_store(); " style=" margin-top: 4px; " alt="добавить в список" />';
	}
	

	
	$out .= '</td></tr></table>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_search_result_selector_div($param) {

	$out = '';
	
	$out .= '<div id="item_search_result_selector_div" style=" font-size: 11px; ">';
	
	if ($param['c'] != '') {
		$out .= outhtml_item_search_result_selector_result($param);
	}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_item_search_result_div($param) {

	$out = '';
	
	$out .= '<div id="item_search_result_div">';
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function get_item_search_query_where($param, &$qd) {

	// $qd = array('q' => '', 'p' => array(), 'l' => '');
	
	$qd['where'] = '';

	$qd['where'] = " ( ";
	$qd['where'] .= " ( item.item_id != 0 ) ";
	
	
	
	if ($param['sort'] == 's') {
	
		// print 'w';
	
		$qd['where'] .= " AND ( ";
			$qd['where'] .= " ( item.status = 'K' ) ";
		$qd['where'] .= " OR ";
			$qd['where'] .= " ( ";
				$qd['where'] .= " ( item.status = 'U' ) ";
				$qd['where'] .= " AND ( item.is_itemset_title = 'Y') ";
			$qd['where'] .= " ) ";
		$qd['where'] .= " ) ";
		
	} else {
	
		$qd['where'] .= " AND ( item.status = 'K' ) ";
		
	}
	
	if (!isset($param['search'])) $param['search'] = 'browse';
	
	if (!isset($param['my'])) $param['my'] = '0';
	
	if ($param['search'] == 'num') {
	
	
		if (isset($param['num'])) {
			
			$s = $param['num'];
			$s = trim($s);
			mb_regex_encoding("UTF-8");
			$s = mb_ereg_replace('[^1-90]', ' ', $s);
			$s = str_replace('    ', ' ', $s);
			$s = str_replace('  ', ' ', $s);
			$s = trim($s);
			
			if (mb_strlen($s) > 0) {
			
				$arr = explode(' ', $s, 8);
				if (sizeof($arr) > 0) {
					$suff = ' ( ';
					$suff .= " ( ? = item.item_id ) ";
					$qd['p'][] = intval($arr[0]);
					$qd['l'] .= 'i';
					for ($i = 1; $i < sizeof($arr); $i++) {
						$arr[$i] = ''.intval($arr[$i]);
						$qd['where'] .= "AND ( ? = item.item_id ) ";
						$qd['p'][] = intval($arr[$i]);
						$qd['l'] .= 'i';
					}
					$suff .= ' ) ';
					$qd['where'] .= ' AND '.$suff;
				}
				
			} else {
			
				$qd['where'] .= ' AND '." ( item.item_id = 0 ) ";
				
			}
		}
		
	
	} elseif ($param['search'] == 'text') {
	
		if (isset($param['q'])) {
		
			$s = prep_string_for_search($param['q']);

			// print $s;
			
			// $s = $param['q'];
			/*
			$s = trim($s);
			$s = mb_strtolower($s);
			// $s = str_replace('ё', 'е', $s);
			$s = mb_str_replace($s, 'ё', 'е');
			mb_regex_encoding("UTF-8");
			$s = mb_ereg_replace('[^а-яa-z1-90]', ' ', $s);
			$s = str_replace('    ', ' ', $s);
			$s = str_replace('  ', ' ', $s);
			$s = trim($s);
			*/

			// $s = '+'.str_replace(' ', ' +', $s);
			
			if (mb_strlen($s) > 0) {

				/*
				$suff = ' ( ';

				$suff .= " ( MATCH(searchstring) AGAINST(? IN BOOLEAN MODE) ) ";
				$qd['p'][] = $s;
				$qd['l'] .= 's';

				$suff .= ' OR ';

				$suff .= " ( MATCH(iusearchstring) AGAINST(? IN BOOLEAN MODE) ) ";
				$qd['p'][] = $s;
				$qd['l'] .= 's';

				$suff .= ' ) ';

				$qd['where'] .= ' AND '.$suff;
				*/


				$arr = explode(' ', $s, 8);
				
				
				if (sizeof($arr) > 0) {
				
					$suff = ' ( ';
					
					$suff .= ' ( ';
					
						// IN BOOLEAN MODE
						// $suff .= " ( MATCH (searchstring) AGAINST ('?') ) ";
						$suff .= " ( LOCATE(?, item.searchstring ) > 0 ) ";
						$qd['p'][] = $arr[0];
						$qd['l'] .= 's';
						
						$suff .= ' OR ';
						//$suff .= " ( MATCH (iusearchstring) AGAINST ('?') ) ";
						$suff .= " ( LOCATE(?, iurel.iusearchstring ) > 0 ) ";
						$qd['p'][] = $arr[0];
						$qd['l'] .= 's';
						
					$suff .= ' ) ';
					
					for ($i = 1; $i < sizeof($arr); $i++) {
						$suff .= "AND ( ";
							
							//$suff .= " ( MATCH (searchstring) AGAINST ('?') ) ";
							$suff .= " ( LOCATE(?, item.searchstring ) > 0 ) ";
							$qd['p'][] = $arr[$i];
							$qd['l'] .= 's';
						
							$suff .= " OR ";
							//$suff .= " ( MATCH (iusearchstring) AGAINST ('?') ) ";
							$suff .= " ( LOCATE(?, iurel.iusearchstring ) > 0 ) ";
							$qd['p'][] = $arr[$i];
							$qd['l'] .= 's';
						
						$suff .= " ) ";
					}
					
					$suff .= ' ) ';
					
					$qd['where'] .= ' AND '.$suff;
				}

			}
		}
		
	
	}
	
	$qd['where'] .= " ) ";
	
	//
	if (!isset($param['tcl'])) $param['tcl'] = '0';
	$param['tcl'] = ''.intval($param['tcl']);
	if ($param['tcl'] > 0) {
		$qd['where'] .= " AND ( item.top_shipmodelclass_id = ? ) ";
		$qd['p'][] = intval($param['tcl']);
		$qd['l'] .= 'i';
	}
	
	//
	if (!isset($param['cms'])) $param['cms'] = 'c0';
	if ($param['cms'] != 'c0') {
		$treeindex = get_cmselector_element_treeindex($param['cms']);
		// print $treeindex;
		$qd['where'] .= " AND ( LOCATE(?, item.ti_parent ) = 1 ) ";
		$qd['p'][] = $treeindex;
		$qd['l'] .= 's';
	}
	
	//
	if ($param['sort'] == 's') {
		$qd['where'] .= " AND ( item.itemset_id > 0 ) ";
	}

	//
	if ($param['my'] > 0) {

		if ($param['my'] == 2) {
			$qd['where'] .= " AND ( iurel.gotit = 'Y' ) ";
		}
		if ($param['my'] == 3) {
			$qd['where'] .= " AND ( iurel.wantit = 'Y' ) ";
		}
		if ($param['my'] == 4) {
			$qd['where'] .= " AND ( iurel.sellit = 'Y' ) ";
		}
		if ($param['my'] == 1) {
			$qd['where'] .= " AND ( ".
				" ( iurel.gotit = 'Y' ) ".
				" OR ".
				" ( iurel.wantit = 'Y' ) ".
				" OR ".
				" ( iurel.sellit = 'Y' ) ".
				" ) ";
		}
		if ($param['my'] == 204) {
			$qd['where'] .= " AND ( ".
				" ( iurel.gotit = 'Y' ) ".
				" AND ".
				" ( iurel.sellit = 'N' ) ".
				" ) ";
		}
	
		$qd['where'] .= " AND ( iurel.user_id = ? ) ";
		$canextuser = (($GLOBALS['customlist'] == 'c0fa5acf83cdb6c5c8fd5e5f45517a4b') || ($GLOBALS['customlist'] == '4f966d300a2f7be8d9d923a18206cfe0') || (am_i_superadmin()));
		if ($canextuser && ($param['extuser'] > 0)) {
			$qd['p'][] = intval($param['extuser']);
		} else {
			$qd['p'][] = intval($GLOBALS['user_id']);
		}
		$qd['l'] .= 'i';
		
	}
	
	if ($param['unknown'] == 'u') {
		$qd['where'] .= " AND ( ".
		" ( item.ship_id = 0 ) ".
		" OR ( item.shipmodel_id = 0 ) ".
		" OR ( item.shipmodelclass_id = 0 ) ".
		" ) ";
	}
	
	//print $qd['where'];
	
	return $q;
}


// =============================================================================
function get_item_search_result_count($param) {

	$qd = array('q' => '', 'p' => array(), 'l' => '');
	
	$wq = get_item_search_query_where($param, &$qd);
	
	$qd['q'] = "".
		" SELECT COUNT( DISTINCT item.item_id ) AS n ".
		" FROM item ";
	
	
	//if ($param['my'] > '0') {
		$qd['q'] .= " LEFT JOIN iurel ON iurel.item_id = item.item_id ";
	//}
		
	$qd['q'] .= " WHERE ".$qd['where']." ";
	
	/*
	if ($param['my'] > '0') {
		$q .= " AND ( iurel.user_id = '".$GLOBALS['user_id']."' ) ";
	}
	*/
	
	// print '«'.$qd['q'].'»';
	
	// prepared query
	$qres = mydb_prepquery($qd['q'], $qd['l'], $qd['p']);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	if (sizeof($qres) != 1) {
		out_silent_error("Ошибка базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qres[0]['n'];
}


// =============================================================================
function get_item_search_result_list($param) {

	if (!isset($param['pn'])) $param['pn'] = '0';
	if (!isset($param['sort'])) $param['sort'] = 'c';
	
	//
	
	$totalsize = get_item_search_result_count($param);
	if ($totalsize == 0) {
		return array();
	}
	
	$psize = get_item_search_result_page_size();
	
	
	
	$totalpages = ceil($totalsize / $psize);
	if ($totalpages == 0) $totalpages = 1;
	
	if ($param['pn'] >= $totalpages) {
		$param['pn'] = ($totalpages - 1);
	}
	
	$from = intval($param['pn']) * $psize;
	
	//
	
	$qd = array('q' => '', 'p' => array(), 'l' => '');

	$wq = get_item_search_query_where($param, &$qd);
	
	//

	if ($param['sort'] == 'a') {
		$ss = 'item.sortfield_a, item.sortfield_c';
	} elseif ($param['sort'] == 'c') {
		$ss = 'item.sortfield_c, item.sortfield_a';
	} elseif ($param['sort'] == 's') {
		$ss = ' (item.itemset_id < 1), item.itemset_str, (item.is_itemset_title = "N"), item.sortfield_a';
	} else {
		$ss = ' item.sortfield_c, item.sortfield_a';
	}
	
	//
	
	$qd['q'] = "".
		" SELECT DISTINCT item.item_id, item.sortfield_c ".
		// " item.shipmodel_str, item.ship_str, item.notes  ".
		" FROM item ";
	
		
	//if ($param['my'] > '0') {
		$qd['q'] .= " LEFT JOIN iurel ON iurel.item_id = item.item_id ";
	//}
	
	$qd['q'] .= " WHERE ".$qd['where']." ";
	
	/*
	if ($param['my'] > '0') {
		$q .= " AND ( iurel.user_id = '".$GLOBALS['user_id']."' ) ";
	}
	*/
		
	$qd['q'] .= " ORDER BY ".$ss.", item.time_submit_start DESC ".
		" LIMIT ".$from.", ".$psize." ".   
		"";
		
	// print '«'.$q.'»';
	
	
	// prepared query
	$qres = mydb_prepquery($qd['q'], $qd['l'], $qd['p']);
	if ($qres === false) {
		out_silent_error("Ошибка записи в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	// end of prepared query
	
	return $qres;
}


// =============================================================================
/*
function calc_search_result_rows(&$param, &$list) {

	for ($i = 0; $i < sizeof($list); $i++) {
		$list[$i]['prefix_model'] = false;
		$list[$i]['prefix_ship'] = false;
		$list[$i]['prefix_alpha'] = false;
		
		$list[$i]['model_g'] = 0;
		$list[$i]['ship_g'] = 0;
		$list[$i]['alpha_g'] = 0;
	}

	// по алфавиту
	
	if ($param['sort'] == 'a') {
	
		// shipname = 'разные названия кораблей' ???
		// shipname = 'названия кораблей не указаны' ???
		
		$alpha = '';
		$ship = '';
		$alpha_i = 0;
		$ship_i = 0;
		
		for ($i = 0; $i < sizeof($list); $i++) {
		
			$thisshipname = get_item_ship_name($list[$i]['item_id']);
			$thisshipalpha = mb_substr($thisshipname, 0, 1);
			
			if ($alpha == $thisshipalpha) {
				$list[$alpha_i]['alpha_g']++;
			} else {
				if ($list[$alpha_i]['alpha_g'] >= 4) {
					$list[$i]['alpha_g'] += 4;
				} else {
					
				}
				$alpha_i = $i;
				$list[$i]['alpha_g']++;
				$alpha = $thisshipalpha;
			}
			
			if ($ship == $thisshipname) {
				$list[$ship_i]['ship_g']++;
			} else {
				$ship_i = $i;
				$list[$i]['ship_g']++;
				$ship = $thisshipname;
			}
		
		}
		
	}
	
	// по классификации
	
	if ($param['sort'] == 'c') {
		
		
		$shipstr = '';
		$shipmodel_head = '';
		$shipmodelclass_str = '';
		
		for ($i = 0; $i < sizeof($list); $i++) {
		}
	}
	
	//

	return true;
}
*/


// =============================================================================
function calc_item_search_result_groups_classify(&$param, &$list) {

	for ($i = 0; $i < sizeof($list); $i++) {
		$list[$i]['print_class'] = false;
		$list[$i]['print_model'] = false;
		$list[$i]['print_ship'] = false;
	}
	
	$class_str = '';
	$model_head = '';
	$ship_str = '';
	$group_sortfield = '';

	for ($i = 0; $i < sizeof($list); $i++) {
		$a = explode('i', $list[$i]['sortfield_c'], 3);
		$item_sortfield = $a[0];
		
		if ($sortfield != $list[$i]['sortfield_c']) {
			//
			$ship_id = get_item_ship_id($list[$i]['item_id']);
			if ($ship_id > 0) {
				$shipmodel_id = my_get_ship_model_id($ship_id);
				if ($shipmodel_id > 0) {
					$shipmodel_str = my_get_shipmodel_name($shipmodel_id);
				} else {
					$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
				}
			} else {
				$shipmodel_id = get_item_shipmodel_id($list[$i]['item_id']);
				$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
			}
			
			if ($shipmodel_id > 0) {
				$shipmodelclass_id = my_get_shipmodel_class($shipmodel_id);
				$shipmodelclass_str = my_get_shipclass_name($shipmodelclass_id);
			} else {
				$shipmodelclass_str = get_item_shipclass_name($list[$i]['item_id']);
			}
			
			$model_html = outhtml_item_list_head_shiptype($shipmodel_id, $shipmodel_str, $shipmodelclass_str, true, true);
			$model_sub_str = get_item_list_head_shiptype_str($shipmodel_id, $shipmodel_str, $shipmodelclass_str);
		
			$printmodelhtml = false;
			if ($shipmodel_head != $model_html) {
			
				$shipmodel_head = $model_html;
				
				$inrow = 0;
				
				$modelhtml = $shipmodel_head;
				
				$printmodelhtml = true;
			}
			
			// ship head
			
			if ($ship_id > 0) {
				$cur_ship_str = my_get_ship_name($ship_id);
				$cur_ship_facnum = my_get_ship_factoryserialnum($ship_id);
			} else {
				$cur_ship_str = get_item_ship_name($list[$i]['item_id']);
				$cur_ship_facnum = my_get_item_ship_factoryserialnum($list[$i]['item_id']);
			}
			if ($cur_ship_facnum != '') $cur_ship_str .= ' <span class="shipserialnum">(зав. '.$cur_ship_facnum.')</span>';

			$printshiphtml = false;
			if ($shipstr != $cur_ship_str) {
			
				$shipstr = $cur_ship_str;
				if ($shipstr == '') {
					$shipstrd = 'Корабль неидентифицирован';
				} else {
					$shipstrd = $shipstr;
				}
				$detail = '';
				if ($model_sub_str != '') $detail = $model_sub_str;
				$shiphtml = outhtml_item_list_head_ship($shipstrd, $detail);
				$inrow = 0;
				
				$printshiphtml = true;
			}
			
			
			if ($printmodelhtml) $out .= $modelhtml;
			if ($printmodelhtml || $printshiphtml) $out .= $shiphtml;
			
			//
			$group_sortfield = $item_sortfield;
		}
	}

	return true;
}


// =============================================================================
function outhtml_item_search_result($param) {

	$out = '';
	
	//
	
	if (!am_i_registered_user()) {
		$param['q'] = '';
		$param['num'] = '';
		$param['my'] = '0';
		$param['tcl'] = '0';
		$param['sort'] = 'c';
		$param['unknown'] = 'a';
		$param['extuser'] = '0';
	}
	
	//
	$allowed = array('browse', 'text', 'num');
	if (!isset($param['search'])) $param['search'] = $allowed[0];
	if (!in_array($param['search'], $allowed)) $param['search'] = $allowed[0];
	if (!isset($param['q'])) $param['q'] = '';
	if (!isset($param['my'])) $param['my'] = '0';
	if (!isset($param['unknown'])) $param['unknown'] = 'a';
	
	if (isset($param['extuser'])) {
		if (!am_i_superadmin()) {
			$param['extuser'] = '0';
		} else {
			if (!ctype_digit($param['extuser'])) $param['extuser'] = '0';
			$param['extuser'] = ''.intval($param['extuser']);
		}
	} else {
		$param['extuser'] = '0';
	}
	
	//
	if ($param['search'] == 'browse') {
		$titlestr = 'Каталог';
	} else {
		$titlestr = 'Результаты поиска';
		$param['q'] = prep_string_for_search($param['q']);
	}
	

	// sidebar
	$out .= '<div id="searchbarenv" style=" ">';
		$out .= outhtml_item_list_searchbar(&$param);
		$GLOBALS['body_script_str'] .= outhtml_script_searchbar_slide();
	$out .= '</div>';
	
	//
	$total_count = get_item_search_result_count($param);
	
	// print $total_count;
	

	if ($param['my'] > 0) $titlestr = 'Моя коллекция. ';
	if ($param['my'] == '1') $titlestr .= '<span style=" color: #3f6b86; " >'.'Мои интересы'.'</span>';
	if ($param['my'] == '2') $titlestr .=  '<span style=" color: #3f6b86; " >'.'У меня есть'.'</span>';
	if ($param['my'] == '3') $titlestr .=  '<span style=" color: #3f6b86; " >'.'Ищу'.'</span>';
	if ($param['my'] == '4') $titlestr .=  '<span style=" color: #3f6b86; " >'.'Меняю/продаю'.'</span>';
	
	// $out .= '<h1 style=" float: left; clear: left; font-size: 20pt; margin-bottom: 20px; margin-top: 20px; padding-left: 18px; ">'.$titlestr.'</h1>';
	
	$out .= '<h1 class="grayemb" style=" margin-left: 20px; margin-bottom: 20px; margin-top: 25px; font-size: 24px; ">';
		$out .= $titlestr;
	$out .= '</h1>';

	//
	if ($param['my'] > 0) {
		$out .= '<p style=" float: right; font-size: 10pt; margin-top: 20px; margin-right: 20px; ">'.$total_count.' '.get_item_count_str_case($total_count).'</p>';
	} elseif (($param['search'] != 'browse') || ($total_count == 0)) {
		$out .= '<p style=" float: right; font-size: 10pt; margin-top: 20px; margin-right: 20px; ">Найдено '.$total_count.' '.get_item_count_str_case($total_count).'</p>';
	}
	
	//
	$out .= '<div style=" float: left; clear: both; margin-top: 20px; ">';
		$out .= outhtml_item_search_result_paginator($param, $total_count);
	$out .= '</div>';
	
	//
	$out .= '<div style=" clear: left; "></div>';
	
	//
	$list = get_item_search_result_list($param);
	
	//print_r($list);
	
	// calc_search_result_rows($param, &$list);
	
	$shipstr = '_';
	$shipstrfull = '_';
	$shipmodel_head = '';
	$shipmodelclass_str = '_';
	$alpha = '_';
	
	$block_shipname = '_';
	$block_alpha = '_';
	
	$inrow = 0;
	$columns = 5;
	
	$block_itemset_id = -1;
	
	// sortfield_c

	for ($i = 0; $i < sizeof($list); $i++) {
	
		// !!!!
		// update_item_searchstring($list[$i]['item_id']);
		// !!!!!!!
	
		// print '('.$list[$i]['alpha_g'].'-'.$list[$i]['ship_g'].')';
		
		if ($param['sort'] == 's') {
		
			$cur_itemset_id  = get_item_itemset_id($list[$i]['item_id']);
		
			if ($block_itemset_id != $cur_itemset_id) {
				$block_itemset_id = $cur_itemset_id;
				if ($block_itemset_id > 0) {
					$str = my_get_itemset_name($block_itemset_id);
				} else {
					$str = '<span style=" font-size: 20px; padding-bottom: 10px; ">не входят в серию</span>';
				}
				$out .= outhtml_item_list_head_itemset($str);
				$inrow = 0;
			}
		}
		
		if ($param['sort'] == 'c') {
		
			// model head
		
			// next item classify
			
			$ship_id = get_item_ship_id($list[$i]['item_id']);
			if ($ship_id > 0) {
				$shipmodel_id = my_get_ship_model_id($ship_id);
				if ($shipmodel_id > 0) {
					$shipmodel_str = my_get_shipmodel_name($shipmodel_id);
				} else {
					$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
				}
			} else {
				$shipmodel_id = get_item_shipmodel_id($list[$i]['item_id']);
				$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
			}
			
			if ($shipmodel_id > 0) {
				$shipmodelclass_id = my_get_shipmodel_class($shipmodel_id);
				$shipmodelclass_str = my_get_shipclass_name($shipmodelclass_id);
			} else {
				$shipmodelclass_str = get_item_shipclass_name($list[$i]['item_id']);
			}
			
			$hasmodel = (get_item_ship_has_model($list[$i]['item_id']) == 'Y');
			if (!$hasmodel) {
				$shipmodel_id = 0;
				$shipmodel_str = '';
			}
			// $hasship = get_item_ship_has_ship($list[$i]['item_id']);
			
			$model_html = outhtml_item_list_head_shiptype($shipmodel_id, $shipmodel_str, $shipmodelclass_str, true, $hasmodel);
			
			$model_sub_str = get_item_list_head_shiptype_str($shipmodel_id, $shipmodel_str, $shipmodelclass_str, $hasmodel);
			
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
			/*
			if ($GLOBALS['user_id'] == 2) {
				$out .= '('.$shipmodel_id.','.$shipmodel_str.','.$shipmodelclass_str.','.$hasmodel.','.$model_sub_str.') ';
				
			}
			*/
			// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		
			$printmodelhtml = false;
			if ($shipmodel_head != $model_html) {
			
				$shipmodel_head = $model_html;
				
				$inrow = 0;
				
				$modelhtml = $shipmodel_head;
				
				$printmodelhtml = true;
			}
			
			// ship head
			
			
			
			if ($ship_id > 0) {
				$cur_ship_str = my_get_ship_name($ship_id);
				$cur_ship_facnum = my_get_ship_factoryserialnum($ship_id);
			} else {
				$cur_ship_str = get_item_ship_name($list[$i]['item_id']);
				$cur_ship_facnum = my_get_item_ship_factoryserialnum($list[$i]['item_id']);
			}
			if ($cur_ship_facnum != '') $cur_ship_str .= ' <span class="shipserialnum">(зав. '.$cur_ship_facnum.')</span>';

			$printshiphtml = false;
			if ($shipstrfull != ($cur_ship_str.$model_sub_str)) {
			
				$shipstr = $cur_ship_str;
				if ($shipstr == '') {
					$shipstrd = 'Корабль неидентифицирован';
				} else {
					$shipstrd = $shipstr;
				}
				$detail = '';
				if ($model_sub_str != '') $detail = $model_sub_str;
				$shiphtml = outhtml_item_list_head_ship($shipstrd, $detail);
				/*
				if ($GLOBALS['user_id'] == 2) {
					$out .= '['.$detail.'] ';
				}
				*/
				$inrow = 0;
				
				$printshiphtml = true;
				$shipstrfull = ($cur_ship_str.$model_sub_str);
			}
			
			
			if ($printmodelhtml) $out .= $modelhtml;
			if ($printmodelhtml || $printshiphtml) {
				$out .= $shiphtml;
				/*
				if ($GLOBALS['user_id'] == 2) {
					$out .= '['.$model_sub_str.'] ';
				
				}
				*/
			}
			
		}
		
		if ($param['sort'] == 'a') {
		
			$ship_id = get_item_ship_id($list[$i]['item_id']);
			// $thisshipname = get_item_ship_name($list[$i]['item_id']);
			
			if ($ship_id > 0) {
				$cur_ship_str = my_get_ship_name($ship_id);
				$cur_ship_facnum = my_get_ship_factoryserialnum($ship_id);
			} else {
				$cur_ship_str = get_item_ship_name($list[$i]['item_id']);
				$cur_ship_facnum = my_get_item_ship_factoryserialnum($list[$i]['item_id']);
			}
			if ($cur_ship_facnum != '') $cur_ship_str .= ' <span class="shipserialnum">(зав. '.$cur_ship_facnum.')</span>';
			
			$thisshipalpha = mb_substr($cur_ship_str, 0, 1);
			
			if ($block_alpha != $thisshipalpha) {
				if ($thisshipalpha != '') {
					$out .= outhtml_item_list_head_alpha($thisshipalpha);
				} else {
					$out .= outhtml_item_list_head_alpha('<span style=" font-size: 20px; padding-bottom: 10px; ">Без названия</span>');
				}
				$inrow = 0;
				$block_alpha = $thisshipalpha;
			}
			if ( ($thisshipalpha != '') && ($block_shipname != $cur_ship_str) ) {
				$out .= outhtml_item_list_head_ship($cur_ship_str);
				$inrow = 0;
				$block_shipname = $cur_ship_str;
			}

		
			/*
			if ($list[$i]['alpha_g'] > 3) {
				//print 'ddfdslfhdfl'.$thisshipalpha;
				$thisshipname = get_item_ship_name($list[$i]['item_id']);
				$thisshipalpha = mb_substr($thisshipname, 0, 1);
				$out .= outhtml_item_list_head_alpha($thisshipalpha);
				$inrow = 0;
			}
		
			if ($list[$i]['ship_g'] > 3) {
				$thisshipname = get_item_ship_name($list[$i]['item_id']);
				$out .= outhtml_item_list_head_ship($thisshipname);
				$inrow = 0;
			}
			*/
		
		}
		
		if ($param['sort'] == 'c') {
		
			if ($list[$i]['model_g'] > 3) {
				$shipmodel_id = get_item_shipmodel_id($list[$i]['item_id']);
				$shipmodel_str = get_item_shipmodel_name($list[$i]['item_id']);
				$shipmodelclass_str = get_item_shipclass_name($list[$i]['item_id']);
				$model_html = outhtml_item_list_head_shiptype($shipmodel_id, $shipmodel_str, $shipmodelclass_str, true, true);
				
				$shipmodel_head = $model_html;
				$out .= $shipmodel_head;
				
				$inrow = 0;
			}
		
			if ($list[$i]['ship_g'] > 3) {
				$thisshipname = get_item_ship_name($list[$i]['item_id']);
				$out .= outhtml_item_list_head_ship($thisshipname);
				$inrow = 0;
			}
		
		}
		
		//
		// $out .= outhtml_item_inlist($list[$i]['item_id']);
		$out .= outhtml_item_inlist_div(array('i' => $list[$i]['item_id']));
		$inrow++;

		//
		if ($inrow >= $columns) {
			$out .= '<div style=" clear: left; "></div>';
			$inrow = 0;
		}
	}

	//
	$out .= '<div style=" clear: left; " ></div>';
	
	//
	$out .= '<div style=" float: left; clear: left; ">';
	$out .= outhtml_item_search_result_paginator($param, $total_count);
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function jqfn_item_search_result($param) {

	$out = '';
	header('Content-Type: text/html; charset=utf-8');
	
	$out .= '<!--';
	$out .= 'blockid=item_search_result_div;';
	$out .= '-->';
	
	$out .= outhtml_item_search_result($param);
	
	print $out;
	return true;
}


// =============================================================================
if (mb_strpos('_'.$_SERVER['SCRIPT_FILENAME'], '/xhr/item_search_result.php') > 0) {
	if (!function_exists('localxhr')) {
		function localxhr($param) {
			jqfn_item_search_result($param);
			return true;
		}
	}
	require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');
}

?>