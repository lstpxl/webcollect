<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/image_upload.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/image_process.php');


// =============================================================================
function my_get_item_picture_base_storage_dir($item_id) {

	$str = ''.$item_id;
	$r = my_get_picture_storage_dir();

	for ($i = (mb_strlen($str) - 1); $i >= 0; $i--) {
		$r .= '/'.mb_substr($str, $i, 1);
	};

	$r .= '/@';

	return $r;
}


// =============================================================================
function my_get_item_picture_storage_dir($item_id, $n = 1) {

	$nindex = 'abcdefghijklmnopqrstuvwxyz';
	if ($n > mb_strlen($nindex)) return false;
	$idx = mb_substr($nindex, ($n - 1), 1);

	$r = my_get_item_picture_base_storage_dir($item_id);
	
	$r .= '/'.$idx;

	return $r;
}




// =============================================================================
function my_get_item_picture_filepath($item_id, $n = 1, $size = 'l') {
	// item_id
	// n - image index
	// size (o - original, m - list, l - large)
	
	$allowed_sizes = array('o', 'm', 'l', 's');
	if (!in_array($size, $allowed_sizes)) $param['s'] = 'l';
	
	$filenames = array(
		'o' => '/original.jpg', 
		'm' => '/medium.jpg', 
		'l' => '/large.jpg',
		's' => '/small.jpg',
	);
	
	$filename = my_get_item_picture_storage_dir($item_id, $n);
	//my_write_log('filename='.$filename.'');
	if ($filename === false) return false;
	
	$filename .= $filenames[$size];
	
	
	
	// if (!is_file($filename)) return false;
	
	return $filename;
}


// =============================================================================
function my_create_container_folders($path) {
	//
	// /home/lastpx/www/site3/public_html/itemimages/7/5/c/original.jpg
	//my_write_log('0parentz='.$path);
	
	$sp = mb_strrpos($path, '/');
	if ($sp === false) return false;
	
	//my_write_log('1parentsp='.$sp);
	
	$parent = mb_substr($path, 0, $sp);
	
	//my_write_log('1parent='.$parent);
	
	if (is_dir($parent)) return true;
	
	//my_write_log('2parent='.$parent);
	
	$r = mkdir($parent, 0777, true);
	
	//my_write_log('3parent='.$parent);
	
	if (is_dir($parent)) return true;
	
	//my_write_log('4parent='.$parent);
	
	$r = my_create_container_folders($parent);
	
	//my_write_log('5parent='.$parent);
	
	if (is_dir($parent)) return true;
}


// =============================================================================
function my_check_item_picture_storage_dir($item_id) {

  $dir = my_get_item_picture_storage_dir($item_id);
  if (!is_dir($dir)) return false;

  $r = mkdir($dir, 0777, true); 

  return $r;
}


// =============================================================================
function my_create_ifnot_item_picture_storage_dir($item_id) {

  $dir = my_get_item_picture_storage_dir($item_id);
  
  if (is_dir($dir)) return true;

  $r = mkdir($dir, 0777, true); 
  
  if (is_dir($dir)) return true;
  
  $r = my_create_container_folders($dir);
  
  $r = mkdir($dir, 0777, true); 
  
  if (is_dir($dir)) return true;

  return false;
}


// =============================================================================
function my_cmp_alphabeta($a, $b)
{
	if ($a < $b) return -1;
	if ($a > $b) return 1;
	return 0;
}


// =============================================================================
function my_sort_item_images($item_id) {

	$dir = my_get_item_picture_base_storage_dir($item_id);

	if (!$dh = @opendir($dir)) return false;
	
	$list = array();
	
    while (false !== ($obj = readdir($dh))) {
        if($obj == '.' || $obj == '..') continue;
		$list[] = $obj;
    }
    closedir($dh);
	
	usort($list, "my_cmp_alphabeta");
	
	//print_r($list);
	
	$nindex = 'abcdefghijklmnopqrstuvwxyz';
	
	for ($i=(sizeof($list) - 1); $i >= 0 ; $i--) {
		$r = rename($dir.'/'.$list[$i], $dir.'/'.$nindex[$i]);
		//print '('.$list[$i].'->'.$nindex[$i].'->'.($r?'y':'n').')';
	}
	
	// rename($dir.'/'.$list[$i], $dir.'/'.$nindex[$i]);

	return true;
}


// =============================================================================
function my_remove_item_pictures($item_id) {

	$dir = my_get_item_picture_base_storage_dir($item_id);
	if (!is_dir($dir)) return true;

	$r = my_remove_folder_recurse($dir);

	return $r;
}


// =============================================================================
function my_get_item_picture_count($item_id) {

	$path = my_get_item_picture_base_storage_dir($item_id);
	if ($path === false) return false;
	
	if (!is_dir($path)) return false;

	$dirlist = array_diff(scandir($path), array('..', '.'));
	if ($dirlist === false) return false;
  
	return sizeof($dirlist);
}


// =============================================================================
function my_get_max_picture_file_size() {
	return (4 * 1024 * 1024);
}


// =============================================================================
function try_make_item_image_resized_set($item_id, $image_number) {
	
	print '('.$item_id.' '.$image_number.')';

	$existing_images_count = my_get_item_picture_count($item_id);
	if ($image_number > $existing_images_count) return false;
	
	$original = my_get_item_picture_filepath($item_id, $existing_images_count, 'o');

	// 180px thumb
	$m = my_get_item_picture_filepath($item_id, $image_number, 'm');
	//my_write_log('resize m='.$m);
	//resize_jpeg_from_file($original, $m, 200, 200);
	my_make_image_square_resized($original, $m, 180, 180, 0);

	// 500px thumb
	$l = my_get_item_picture_filepath($item_id, $image_number, 'l');
	my_make_image_square_resized($original, $l, 500, 500, 0);
	//resize_jpeg_from_file($original, $l, 500, 500);
	
	// 90px thumb
	$s = my_get_item_picture_filepath($item_id, $image_number, 's');
	my_make_image_square_resized($original, $s, 90, 90, 0);
	//resize_jpeg_from_file($original, $s, 100, 100);

	return true;
}


// =============================================================================
function try_remove_item_image($item_id, $image_number) {
	
	if (!isset($item_id)) return false;
	if (!isset($image_number)) return false;
	
	if (!ctype_digit($item_id)) return false;
	if (!ctype_digit($image_number)) return false;
	
	$dir = my_get_item_picture_storage_dir($item_id, $image_number);
	if (!is_dir($dir)) return false;

	$result = unlinkRecursive($dir, true);
	if (!$result) return false;
	
	my_sort_item_images($item_id);

	return true;
}


// =============================================================================
function try_remove_all_item_images($item_id) {

	if (!isset($item_id)) return false;
	$item_id = ''.intval($item_id);
	if (my_get_item_status($item_id) === false) return false;
	
	$dir = my_get_item_picture_base_storage_dir($item_id);
	if ($dir === false) return false;
	if (!is_dir($dir)) return false;
	$result = unlinkRecursive($dir, true);
	if (!$result) return false;
	
	return true;
}


// =============================================================================
function try_add_item_image(&$param) {

	// вход
	//   $param['i'] - item_id
	// выход
	//   true OR false 
	//   $param['error_message']
	//   $param['image_number']
	
	$param['error_message'] = '';
	
	$p = array();
	$p['field_name'] = 'upfile';
	$p['size_limit_bytes'] = my_get_max_picture_file_size();
	$p['size_limit_px_width'] = 6000;
	$p['size_limit_px_height'] = 6000;
	
	$imgres = process_image_upload(&$p);
	if ($imgres === false) {
		$param['error_message'] = $p['error_message'];
		return false;
	}
	
	$iswhite = img_frame_is_white($imgres);
	if (!$iswhite) {
		$param['error_message'] = "Фон изображения не белый!";
		// imagedestroy($imgres);
		// return false;
	}
	
	$existing_images_count = my_get_item_picture_count($param['i']);
	$param['image_number'] = ($existing_images_count + 1);
	
	my_check_item_picture_storage_dir($param['i']);
	
	$newfilename = my_get_item_picture_filepath($param['i'], $param['image_number'], 'o');
	
	$result = my_create_container_folders($newfilename);
	if (!$result) {
		$param['error_message'] = 'my_create_container_folders() failed file='.$newfilename;
		return false;
	}
	
	if ($iswhite) {
	
		$tmp = img_crop_symm_lr(&$imgres);
		if ($tmp !== false) {
			//print 'croplr.';
			//imagedestroy($imgres);
			//$imgres = $tmp;
		} else {
			my_write_log('img_crop_symm_lr() failed.');
		}
		
		// print 'check2='.imagesx($imgres).'.';
		
		$tmp = img_crop_symm_tb(&$imgres);
		if ($tmp !== false) {
			//print 'croptd.';
			// imagedestroy($imgres);
			// $imgres = $tmp;
		} else {
			my_write_log('img_crop_symm_tb() failed.');
		}
	
	}
	
	$r = imagejpeg($imgres, $newfilename, 98);
	if (!$r) {
		$param['error_message'] = "Ошибка при записи файла! (".__FILE__." Line ".__LINE__.")";
		imagedestroy($imgres);
		$imgres = null;
		return false;
	}
	
	imagedestroy($imgres);
	
	$result = try_make_item_image_resized_set($param['i'], $param['image_number']);
	if (!$result) {
		$param['error_message'] = "Ошибка при генерации файлов! (".__FILE__." Line ".__LINE__.")";
		try_remove_item_image($param['i'], $param['image_number']);
		return false;
	}
	
	return true;
}



// =============================================================================
function calc_item_image_crc($item_id) {

	$item_id = ''.intval($item_id);
	if ($item_id < 1) return false;
	
	$filename = my_get_item_picture_filepath($item_id, 1, 'm');
	if ($filename === false) return false;
	
	$img = imagecreatefromjpeg($filename);
	if (!$img) return false;
	
	//
	
	$h = imagesy($img);
	$w = imagesx($img);
	
	$crc = (int)0;
	
	for ($y = 0; $y < $h; $y += 3) {
		for ($x = 0; $x < $w; $x += 3) {
		
			$rgb = imagecolorat($img, $x, $y);
			$colors = imagecolorsforindex($img, $rgb);
			
			// $lightness = (((double)$colors['red'] + (double)$colors['green'] + (double)$colors['blue']) / (double)(3 * 255));
			
			$crc = (($crc + (int)$colors['red']) % 2147483647);
			$crc = (($crc + (int)$colors['green']) % 2147483647);
			$crc = (($crc + (int)$colors['blue']) % 2147483647);
			
		}
	}
		
	//

	return $crc;
}


?>