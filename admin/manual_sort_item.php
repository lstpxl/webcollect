<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/welcome_screen.php');
// require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/cmselector.php');


// =============================================================================
function outhtml_script_sorter_test() {

$str = <<<SCRIPTSTRING

function js_sorter_test_callback(aresp) {

	if (typeof aresp != 'object') return false;

	if ((typeof aresp['elemtoplace'] == 'string') && (typeof aresp['tail'] == 'string')) {
		var elem = document.getElementById(aresp['elemtoplace']);
		if (elem) {
			elem.innerHTML = aresp['tail'];
		}
	}

	if (typeof aresp['result'] == 'string') {
		if (aresp['result'] == 'ok') {
			var elem = document.getElementById('pagecontentdiv');
			if (elem) {
				elem.style.backgroundColor = '#ffffff';
			}
		}
		
	}
	
	return true;
}

function js_sorter_test_place_elem_after(id, previd) {
	// alert('Place item #' + id + ' after item #' + previd);
	var url = '/xhr/manual_sort_item.php?i=' + id + '&c=placeafter' + '&after=' + previd;
	var elem = document.getElementById('pagecontentdiv');
	if (elem) {
		elem.style.backgroundColor = '#e8eeff';
		// alert('bg');
	}
	return ajax_my_get_query(url);
}

function js_sorter_test_onupdate(elem) {
	var id = elem.id;
	var prevelem = elem.previousSibling;
	if (prevelem) {
		if (prevelem.nodeType != 1) {
			prevelem = false;
		}
	}
	if (prevelem) {
		var previd = prevelem.id;
	} else {
		var previd = 0;
	}
	return js_sorter_test_place_elem_after(id, previd);
}

(function (){

	var console = window.console;

	if( !console.log ){
		console.log = function (){
			alert([].join.apply(arguments, ' '));
		};
	}


});
	

		
SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}


// =============================================================================
function calc_items_sample_list() {
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.submitter_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.ti_parent = 'ab0b0b00b0c0d0' ".
		" ORDER BY item.ti_subsort, item.sortfield_c, item.sortfield_a ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr;
}



// =============================================================================
function outhtml_sorter_test_list($param) {

	$out = '';
	
	$l = calc_items_sample_list();

	$out .= '<ul id="items" style="  ">';
	
	for ($i = 0; $i < sizeof($l); $i++) {
		$out .= '<li id="'.$l[$i]['item_id'].'">';
			$out .= outhtml_item_inlist_small_moderation($l[$i]['item_id']);
			// $out .= '<div style=" clear: both; "></div>';
		$out .= '</li>';
	}
	
	$out .= '</ul>';
	
	$out .= '<script>';
	$out .= ' (function (){

			var slist = document.getElementById("items");

			new Sortable(slist, {
				group: "words",
				onAdd: function (evt){ console.log(\'onAdd.slist:\', evt.item); },
				onUpdate: function (evt){ console.log(\'onUpdate.slist:\', evt.item); window.js_sorter_test_onupdate(evt.item); },
				onRemove: function (evt){ console.log(\'onRemove.slist:\', evt.item); }
			});

		})();

	';
	
	$out .= '</script>';
			
	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_sorter_test($param) {

	$out = '';
	
	$out .= '<script src="/sortable/Sortable.js"></script>';
	$out .= outhtml_script_sorter_test();
	
	$out .= '<div style=" background-color: #f8f8f8f; padding-left: 0px; " >';
		
		$out .= '<div style=" padding: 20px 20px 20px 20px; color: #888888; line-height: 125%; ">';
			
			$out .= '<h1 style=" font-size: 20pt; margin-top: 20px; margin-bottom: 20px; padding-left: 18px; ">Ручная сортировка знаков</h1>';
			
			$out .= '<div style=" padding-left: 20px; ">';
			
				$out .= outhtml_sorter_test_list($param);
			
			$out .= '</div>';
			
		$out .= '</div>';
		
		
		//
		
		$out .= '<div style=" float: right; clear: none; width: 310px; ">';
		
		//

		$out .= '</div>';

	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_admin_manual_sort_item($param) {

	if (!am_i_admin()) {
		return outhtml_welcome_screen($param).PHP_EOL;
	}
	
	$GLOBALS['pagetitle'] = 'Ручная сортировка знаков / Админ / '.$GLOBALS['pagetitle'];
	
	$out = '';

	$out .= outhtml_sorter_test($param);
	
	return $out.PHP_EOL;
}

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/entry.php');

?>