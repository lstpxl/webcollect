<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/xhr/popup_zoom1_content.php');


// =============================================================================
function outhtml_script_popup_zoom1() {

$str = <<<SCRIPTSTRING

var popup_zoom1_item_id = 0;

function get_elem_pos_x(ele){
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
    return x;
}


function get_elem_pos_y(ele){
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

function js_popup_zoom1_show(e, item_id) {

	var elem, evt = e ? e:event;
	if (evt.srcElement)  elem = evt.srcElement;
	else if (evt.target) elem = evt.target;
	
	// calc popup position
	
	var popup = document.getElementById('popup_zoom1');
	if (!popup) return false;
	
	var popup_height = popup.offsetHeight;
	var top_scroll = window.pageYOffset || document.documentElement.scrollTop;
	var newy = Number(evt.clientY) + 20 + top_scroll - popup_height - 40;
	// if ((newy - 40) > popup_height) newy = newy - popup_height - 40;
	if (newy < top_scroll) newy =  Number(evt.clientY) + 20 + top_scroll;
	
	// w = window.innerWidth;h = window.innerHeight;
	var win_width = window.innerWidth;
	var popup_width = popup.offsetWidth;
	var newx = Number(evt.clientX) + 20;
	if ((newx + popup_width + 20) > win_width) newx = newx - popup_width + 20;
	
	popup.style.left = '' + newx + 'px';
	popup.style.top = '' + newy + 'px';
	// popup.style.top = '' + Number(evt.clientY + 20) + 'px';
	
	//
	
	popup.style.visibility = 'visible'; 
	
	popup.style.zIndex  = 21;
	popup.style.display = 'block';
	
	
	var elem_loaded_id = document.getElementById('popup_zoom1_content_item_id');
	if (elem_loaded_id) {
		if (elem_loaded_id == item_id) {
			return true;
		}
	}

	js_popup_zoom1_content_query(item_id);
}


function js_move_elem_bgimg(zoomedpic, thpx, thpy) {

	var elem = document.getElementById('zoomedpic_width');
	if (!elem) return false;
	var zpw = elem.value;
	
	var elem = document.getElementById('zoomedpic_height');
	if (!elem) return false;
	var zph = elem.value;
	
	var elem = document.getElementById('zoomedpic_max_width');
	if (!elem) return false;
	var mw = elem.value;
	
	var elem = document.getElementById('zoomedpic_max_height');
	if (!elem) return false;
	var mh = elem.value;
	
	var x = 0;
	var y = 0;
	
	var d = (zpw - mw);
	if (d > 0) x = Math.round(50 - (d + 100) * thpx);

	var d = (zph - mh);
	if (d > 0) y = Math.round(50 - (d + 100) * thpy);
	
	zoomedpic.style.backgroundPosition = '' + x + 'px ' + y + 'px';
	
	
	return true;
}


function js_popup_zoom1_move(e) {

	var elem, evt = e ? e:event;
	if (evt.srcElement)  elem = evt.srcElement;
	else if (evt.target) elem = evt.target;
	
	// calc popup position
	
	var popup = document.getElementById('popup_zoom1');
	if (!popup) return false;
	
	var popup_height = popup.offsetHeight;
	var top_scroll = window.pageYOffset || document.documentElement.scrollTop;
	var newy = Number(evt.clientY) + 20 + top_scroll - popup_height - 40;
	// if ((newy - 40) > popup_height) newy = newy - popup_height - 40;
	if (newy < top_scroll) newy =  Number(evt.clientY) + 20 + top_scroll;
	
	// w = window.innerWidth;h = window.innerHeight;
	var win_width = window.innerWidth;
	var popup_width = popup.offsetWidth;
	var newx = Number(evt.clientX) + 20;
	if ((newx + popup_width + 20) > win_width) newx = newx - popup_width + 20;
	
	popup.style.left = '' + newx + 'px';
	popup.style.top = '' + newy + 'px';
	// popup.style.top = '' + Number(evt.clientY + 20) + 'px';
	
	// zoom fragment position
	
	var thpx = 0;
	var thpy = 0;
	
	var tx = Number(evt.clientX) - get_elem_pos_x(elem);
	if (tx < 0) tx = 'L';
	if (tx > elem.offsetWidth) tx = 'R';
	if ((tx != 'L') && (tx != 'R')) {
		thpx = (tx / elem.offsetWidth);
	} else {
		js_popup_zoom1_hide();
		return true;
	}
	
	var ty = Number(evt.clientY) - get_elem_pos_y(elem);
	if (ty < 0) ty = 'T';
	if (ty > elem.offsetHeight) ty = 'B';
	if ((ty != 'T') && (ty != 'B')) {
		thpy = (ty / elem.offsetWidth);
	} else {
		js_popup_zoom1_hide();
		return true;
	}
	
	// move zoomed image inside popup
	
	var zoomedpic = document.getElementById('zoomedpic');
	if (!zoomedpic) return false;
	
	js_move_elem_bgimg(zoomedpic, thpx, thpy);
	
	// popup.innerHTML = elem.id;
	
	// debug values
	
	
	
	return true;
}

function js_popup_zoom1_hide() {
	
	var popup = document.getElementById('popup_zoom1');
	if (popup) {
		popup.style.display = 'none';
	}
}

SCRIPTSTRING;

$str = '<script type="text/javascript" language="JavaScript">'.$str.'</script>'.PHP_EOL;

return $str;

}

?>