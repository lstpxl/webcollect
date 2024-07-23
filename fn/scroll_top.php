<?php

// =============================================================================
function outhtml_script_scroll_top() {

$str = <<<SCRIPTSTRING

var scroll_top_elem = false;
var scroll_top_elem_w = false;
var scroll_top_elem_h = false;
var scroll_top_elem_pos = false;

function scroll_top_scroll() {
	window.scrollTo(0,0);
	if (scroll_top_elem) {
		scroll_top_elem.style.display  = 'none';
	}
}

function scroll_top_onscroll() {
	
	if (scroll_top_elem == false) {
		var elem = document.getElementById('scroll_top_div');
		if (elem) {
			scroll_top_elem = elem;
			scroll_top_elem_w = elem.clientWidth;
			scroll_top_elem_h = elem.clientHeight;
		}
	}
	
	if (scroll_top_elem) {
		
		var elem = document.getElementById('searchbar');
		if (elem) {
	
			var scrolled = window.pageYOffset || document.documentElement.scrollTop;
			
			if (scrolled > 340) {
				scroll_top_elem.style.display  = 'block';
			} else {
				scroll_top_elem.style.display  = 'none';
			}
		}
	}
}


SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;
return $str;
}

?>