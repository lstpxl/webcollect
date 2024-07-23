<?php

// =============================================================================
function outhtml_script_searchbar_slide() {

$str = <<<SCRIPTSTRING

var searchbar_elem = false;
var searchbar_elem_w = false;
var searchbar_elem_h = false;
var searchbar_stop_pos = false;

function get_elem_pos_y_2(ele) {
    var x=0;
    var y=0;
    while(true){
        x += ele.offsetLeft;
        y += ele.offsetTop;
        if(ele.offsetParent === null){
            break;
        }
        ele = ele.offsetParent;
    }
    return y;
}

function my_onscroll() {

	if (searchbar_elem == false) {
		var elem = document.getElementById('searchbar');
		if (elem) {
			searchbar_elem = elem;
			searchbar_elem_w = elem.clientWidth;
			searchbar_elem_h = elem.clientHeight;
		}
		
		var elem = document.getElementById('searchbarenv');
		if (elem) {
			searchbar_stop_pos = get_elem_pos_y_2(elem);
			elem.style.height = searchbar_elem_h + 'px';
		}
	}

	if (searchbar_elem) {
		
	
		var scrolled = window.pageYOffset || document.documentElement.scrollTop;
		
		var elempos = get_elem_pos_y_2(searchbar_elem);
		
		if (scrolled > searchbar_stop_pos) {
			
			return true;
			
			searchbar_elem.style.zIndex  = 22;
			searchbar_elem.style.top = '' + scrolled + 'px';
			searchbar_elem.style.position = 'absolute';
			searchbar_elem.style.width = searchbar_elem_w + 'px';
			searchbar_elem.style.height = searchbar_elem_h + 'px';
		}
		
		if (scrolled < searchbar_stop_pos) {
			searchbar_elem.style.zIndex  = 'auto';
			searchbar_elem.style.top = '';
			searchbar_elem.style.position = 'static';
		}
	
	}
}

function init_searchbar_slide() {

	window.onscroll = function () {	my_onscroll(); scroll_top_onscroll(); }
	return true;
}

SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;
return $str;
}

?>