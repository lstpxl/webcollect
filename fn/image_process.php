<?php


// =============================================================================
function resize_jpeg_from_file($filename_src, $filename_dest, $dest_width, $dest_height, $quality = 94) {

	$img_src = imagecreatefromjpeg($filename_src);

	$width = imagesx($img_src);
	$height = imagesy($img_src);

	if ($width > $height) {
	    $newwidth = $dest_width;
	    $divisor = $width / $dest_width;
	    $newheight = floor($height / $divisor);
	}
	else {
	    $newheight = $dest_height;
	    $divisor = $height / $dest_height;
	    $newwidth = floor($width / $divisor);
	}

	$tmpimg = imagecreatetruecolor($newwidth, $newheight);

	imagecopyresampled($tmpimg, $img_src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	imagejpeg($tmpimg, $filename_dest, $quality);

	imagedestroy($img_src);
	imagedestroy($tmpimg);

	return true;
}


// =============================================================================
function my_make_image_square_resized($fromfile, $tofile, $xsize, $ysize, $bgcolor) {
	
	$r = imagecreatefromjpeg($fromfile);
	if ($r === false) return false;
	
	$d = imagecreatetruecolor($xsize, $ysize);
	if ($d === false) return false;
	
	$color = imagecolorallocate($d, 0xFF, 0xFF, 0xFF);
	if ($d === false) return false;
	
	$res = imagefilledrectangle($d, 0, 0, $xsize - 1, $ysize - 1, $color);
	if ($res === false) return false;
	
	// $is = getimagesize($fromfile);

	//print_r($is);

	$src_w = imagesx($r);
	$src_h = imagesy($r);
	
	if ($src_w >= $src_h) {
		// source is horizontal
		$scale = ((double)$xsize / (double)$src_w);
		$dst_x = 0;
		$dst_w = $xsize;
		$dst_h = round($scale * (double)$src_h);
		$dst_y = round((double)($ysize - $dst_h) / (double)2);
	} else {
		// source is vertical
		$scale = ((double)$ysize / (double)$src_h);
		$dst_y = 0;
		$dst_h = $xsize;
		$dst_w = round($scale * (double)$src_w);
		$dst_x = round((double)($xsize - $dst_w) / (double)2);
	}
	
	// print '-'.$dst_x.'-'.$dst_y.'-'.$dst_w.'-'.$dst_h.'-'.$src_w.'-'.$src_h;
	
	/*
	-0-100-200-0-850-661
	-0--81-500-661-850
	-661-0-50-100-0-850-661 
	
	850*661
	*/
	
	$res = imagecopyresampled($d, $r, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	
	$res = imagejpeg($d, $tofile, 92);
	if ($res === false) return false;
	
	return true;
}


// =============================================================================
function img_frame_is_white($img) {

	$w = imagesx($img);
	$h = imagesy($img);
	
	$threshold = (double)245;
	
	$lightest = (double)0;
	for ($x = 0; $x < $w; $x++) {
		$rgb = imagecolorat($img, $x, 0);
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v > $lightest) $lightest = $v;	
	}
	$top_ok = ($lightest > (double)$threshold);
	
	//print 'top_is_white='.$lightest.' ';
	
	$lightest = (double)0;
	for ($x = 0; $x < $w; $x++) {
		$rgb = imagecolorat($img, $x, ($h - 1));
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v > $lightest) $lightest = $v;	
	}
	$bottom_ok = ($lightest > (double)$threshold);
	
	//print 'bottom_is_white='.$lightest.' ';
	
	$lightest = (double)0;
	for ($y = 0; $y < $h; $y++) {
		$rgb = imagecolorat($img, 0, $y);
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v > $lightest) $lightest = $v;	
	}
	$left_ok = ($lightest > (double)$threshold);
	
	//print 'left_is_white='.$lightest.' ';
	
	$lightest = (double)0;
	for ($y = 0; $y < $h; $y++) {
		$rgb = imagecolorat($img, ($w - 1), $y);
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v > $lightest) $lightest = $v;	
	}
	$right_ok = ($lightest > (double)$threshold);
	
	//print 'right_is_white='.$lightest.' ';
	
	return ($top_ok && $bottom_ok && $left_ok && $right_ok);
}



// =============================================================================
function img_is_lr_side_white($img, $treshold = 245) {

	$w = imagesx($img);
	$h = imagesy($img);
	
	$threshold = (double)$treshold;
		
	$darkest = (double)300;
	for ($y = 0; $y < $h; $y++) {
		$rgb = imagecolorat($img, 0, $y);
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v < $darkest) $darkest = $v;	
	}
	$left_is_white = ($darkest > (double)$threshold);
	
	//print 'left_is_white='.$darkest.' ';
	
	$darkest = (double)300;
	for ($y = 0; $y < $h; $y++) {
		$rgb = imagecolorat($img, ($w - 1), $y);
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v < $darkest) $darkest = $v;	
	}
	$right_is_white = ($darkest > (double)$threshold);
	
	
	//print 'right_is_white='.$darkest.' ';
	
	return ($left_is_white && $right_is_white);
}


// =============================================================================
function img_is_tb_side_white($img, $treshold = 245) {

	$w = imagesx($img);
	$h = imagesy($img);
	
	$threshold = (double)$treshold;
	
	$darkest = (double)300;
	for ($x = 0; $x < $w; $x++) {
		$rgb = imagecolorat($img, $x, 0);
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v < $darkest) $darkest = $v;	
	}
	$top_is_white = ($darkest > (double)$threshold);
	
	//print 'top_is_white='.$darkest.' ';
	
	$darkest = (double)300;
	for ($x = 0; $x < $w; $x++) {
		$rgb = imagecolorat($img, $x, ($h - 1));
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v < $darkest) $darkest = $v;	
	}
	$bottom_is_white = ($darkest > (double)$threshold);
	
	//print 'bottom_is_white='.$darkest.' ';
	
	
	return ($top_is_white && $bottom_is_white);
}


// =============================================================================
function img_is_t_side_white($img, $treshold = 245) {

	$threshold = (double)$treshold;
	
	if ($threshold < 1) return false;
	if ($threshold > 255) return false;

	$w = imagesx($img);
	$h = imagesy($img);
	
	$darkest = (double)300;
	for ($x = 0; $x < $w; $x++) {
		$rgb = imagecolorat($img, $x, 0);
		$colors = imagecolorsforindex($img, $rgb);
		$v = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)3);
		if ($v < $darkest) $darkest = $v;	
	}
	$top_is_white = ($darkest > (double)$threshold);
	
	
	return ($top_is_white);
}


// =============================================================================
function img_crop_symm_lr(&$img) {

	$h = imagesy($img);
	$w = imagesx($img);
	
	//print 'here.';
	
	do {
	
		// print ' '.memory_get_usage();
		my_write_log('mem='.memory_get_usage());
	
		$hasborder = img_is_lr_side_white($img);
		//print 'there.';
		if ($hasborder) {
			//print 'pass. ';
			$tmp = imagecreatetruecolor(($w-2), $h);
			if (!$tmp) return false;
			$r = imagecopy($tmp, $img, 0, 0, 1, 0, ($w-2), $h);
			if (!$r) {
				imagedestroy($tmp);
				$tmp = null;
				return false;
			}
			$r = imagedestroy($img);
			if (!$r) {
				$r = null;
				return false;
			}
			$img = $tmp;
			$tmp = null;
			$w = imagesx($img);
		}
		$repeat = (($w > 100) && $hasborder);
	} while ($repeat);
	
	//print 'check'.imagesx($img).'.';
	
	return $img;
}


// =============================================================================
function img_crop_symm_tb(&$img) {

	$h = imagesy($img);
	$w = imagesx($img);
	
	//print 'heretd.';
	
	do {
		$hasborder = img_is_tb_side_white($img);
		//print 'theretd.';
		if ($hasborder) {
			//print 'passtd. ';
			$tmp = imagecreatetruecolor($w, ($h-2));
			//print 'passtd2. ';
			if (!$tmp) return false;
			//print 'passtd2a. ';
			$r = imagecopy($tmp, $img, 0, 0, 0, 1, $w, ($h-2));
						if (!$r) {
				imagedestroy($tmp);
				$tmp = null;
				return false;
			}
			$r = imagedestroy($img);
			if (!$r) {
				$r = null;
				return false;
			}
			$img = $tmp;
			$tmp = null;
			$h = imagesy($img);
		}
		$repeat = (($h > 100) && $hasborder);
	} while ($repeat);
	
	return $img;
}


// =============================================================================
function img_get_trim_box($img, $hex=null){

	if (!ctype_xdigit($hex)) $hex = imagecolorat($img, 0,0);
	
	$b_top = $b_lft = 0;
	$b_rt = $w1 = $w2 = imagesx($img);
	$b_btm = $h1 = $h2 = imagesy($img);

	do {
		//top
		for(; $b_top < $h1; ++$b_top) {
			for($x = 0; $x < $w1; ++$x) {
				if(imagecolorat($img, $x, $b_top) != $hex) {
					break 2;
				}
			}
		}

		// stop if all pixels are trimmed
		if ($b_top == $b_btm) {
			$b_top = 0;
			$code = 2;
			break 1;
		}

		// bottom
		for(; $b_btm >= 0; --$b_btm) {
			for($x = 0; $x < $w1; ++$x) {
				if(imagecolorat($img, $x, $b_btm-1) != $hex) {
					break 2;
				}
			}
		}

		// left
		for(; $b_lft < $w1; ++$b_lft) {
			for($y = $b_top; $y <= $b_btm; ++$y) {
				if(imagecolorat($img, $b_lft, $y) != $hex) {
					break 2;
				}
			}
		}

		// right
		for(; $b_rt >= 0; --$b_rt) {
			for($y = $b_top; $y <= $b_btm; ++$y) {
				if(imagecolorat($img, $b_rt-1, $y) != $hex) {
					break 2;
				}
			}

		}

		$w2 = $b_rt - $b_lft;
		$h2 = $b_btm - $b_top;
		$code = ($w2 < $w1 || $h2 < $h1) ? 1 : 0;
		
	} while (0);

	// result codes:
	// 0 = Trim Zero Pixels
	// 1 = Trim Some Pixels
	// 2 = Trim All Pixels
	return array(
		'#'     => $code,   // result code
		'l'     => $b_lft,  // left
		't'     => $b_top,  // top
		'r'     => $b_rt,   // right
		'b'     => $b_btm,  // bottom
		'w'     => $w2,     // new width
		'h'     => $h2,     // new height
		'w1'    => $w1,     // original width
		'h1'    => $h1,     // original height
	);
	
}



// =============================================================================
function img_crop_symm_box(&$img) {

	$box = img_get_trim_box($img, 0xFFFFFF);
	
	if ($box['#'] == '2') return false;
	if ($box['#'] == '0') return true;
	
	$trim_top = $box['t'];
	$trim_bottom = ($box['h1'] - $box['b']);
	
	if (($trim_top > 0) && ($trim_bottom > 0)) {
		if ($trim_top > $trim_bottom) {
			$trim_top = $trim_bottom;
		} else {	
			$trim_bottom = $trim_top;
		}
	} else {
		if ($trim_top == 0) {
			if ($trim_bottom > 8) {
				$trim_bottom -= 4;
			}
		}
		if ($trim_bottom == 0) {
			if ($trim_top > 8) {
				$trim_top -= 4;
			}
		}
	}
	
	$trim_left = $box['l'];
	$trim_right = ($box['w1'] - $box['r']);
	
	if (($trim_left > 0) && ($trim_right > 0)) {
		if ($trim_left > $trim_right) {
			$trim_left = $trim_right;
		} else {	
			$trim_right = $trim_left;
		}
	} else {
		if ($trim_left == 0) {
			if ($trim_right > 8) {
				$trim_right -= 4;
			}
		}
		if ($trim_right == 0) {
			if ($trim_left > 8) {
				$trim_left -= 4;
			}
		}
	}
	
	my_write_log('Pixels to trim: T='.$trim_top.', B='.$trim_bottom.', L='.$trim_left.', R='.$trim_right);
	
	if (($trim_top + $trim_bottom + $trim_left + $trim_right) == 0) return true;
	
	// return true;

	my_write_log('Memory usage before trim '.memory_get_usage().' bytes.');
	
	$dst_w = ($box['w1'] - $trim_left - $trim_right);
	$dst_h = ($box['h1'] - $trim_top - $trim_bottom);
	
	$tmp = imagecreatetruecolor($dst_w, $dst_h);
	if (!$tmp) {
		$erm = 'imagecreatetruecolor() failed at ('.__FILE__.' Line '.__LINE__.')';
		my_write_log($erm);
		return false;
	}
	
	$r = imagecopy($tmp, $img, 0, 0, $trim_left, $trim_top, $dst_w, $dst_h);
	if (!$r) {
		imagedestroy($tmp);
		$tmp = null;
		$erm = 'imagecopy() failed at ('.__FILE__.' Line '.__LINE__.')';
		my_write_log($erm);
		return false;
	}
	
	imagedestroy($img);
	$img = null;
	$img = $tmp;
	
	$tmp = null;
	
	my_write_log('Memory usage after trim '.memory_get_usage().' bytes.');
	
	return true;
}


// =============================================================================
function img_make_transparent(&$img, $whitecontour) {

	$h = imagesy($img);
	$w = imagesx($img);
	
	//print 'heretd.';
	
	$tmp = imagecreatetruecolor($w, $h);
	if (!$tmp) return false;
	
	// will replace existing pixels instead of blending
	$r = imagealphablending($tmp, false);
	if (!$r) return false;
	
	//
	for ($y = 0; $y < $h; $y++) {
		for ($x = 0; $x < $w; $x++) {
			$rgb = imagecolorat($img, $x, $y);
			$colors = imagecolorsforindex($img, $rgb);
			$lightness = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)(3 * 255));
			//
			
			$transparency = ((double)$lightness);
			$k = (double)0.6;
			$opacity = (((double)1 - $transparency) * $k);
			$alpha = ( (double)127 * (1 - $opacity) );
			
			// print ' '.intval($alpha);
			
			if ($whitecontour) {
				$c = imagecolorallocatealpha($tmp, 255, 255, 255, intval($alpha));
			} else {
				$c = imagecolorallocatealpha($tmp, 0, 0, 0, intval($alpha));
			}
			
/*
$rgba     = $c;
$r = ($rgba >> 16) & 0xFF;
$g = ($rgba >> 8) & 0xFF;
$b = $rgba & 0xFF;
$a     = ($rgba & 0x7F000000) >> 24;

print ' ('.$r.'-'.$g.'-'.$b.'-'.$a.') ';
*/
			
			
			//print ' ';
			//print $c;
			
			//
			
			// print ' '.intval($lightness * 255);
			
			// $c = imagecolorallocatealpha($tmp, intval($lightness * 255), intval($lightness * 255), intval($lightness * 255), 50);
			
			$r = imagesetpixel($tmp, $x, $y, $c);
			if (!$r) return false;
			// imagecolordeallocate($tmp, $c);
		}
	}
	
	$r = imagedestroy($img);
	if (!$r) return false;
	
	$img = $tmp;
	
	return $img;
}


// =============================================================================
function img_is_correct_blueprint_file(&$img) {

	$tw = img_is_t_side_white($img, 252);
	if (!$tw) return 'Фон должен быть белым, кроме нижней границы изображения.';
	
	$lrw = img_is_lr_side_white($img, 252);
	if (!$lrw) return 'Фон должен быть белым, кроме нижней границы изображения.';
	
	$h = imagesy($img);
	if ($h === false) return 'Проблема 1';
	if ($h < 20) return 'Высота изображения должна быть не менее 20 и не более 200 пикселей. Сейчас '.$h;
	if ($h > 180) return 'Высота изображения должна быть не менее 20 и не более 200 пикселей. Сейчас '.$h;
	
	$w = imagesx($img);
	if ($w === false) return 'Проблема 2';
	if ($w < 200) return 'Ширина изображения должна быть не менее 200 и не более 600 пикселей. Сейчас '.$w;
	if ($w > 550) return 'Ширина изображения должна быть не менее 200 и не более 600 пикселей. Сейчас '.$w;
	
	return '';
}


?>