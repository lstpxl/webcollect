<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/fn/basicfn.php');

// =============================================================================
function outhtml_sample_badge_thumb_div($n) {

	$out = '';

	// bagde thumb div
	$out .= '<div style=" float: left; margin-right: 8px; margin-bottom: 20px; width: 188px; ">';

	// bagde image div
	$out .= '<div style=" width: 188px; height: 188px; display: block; overflow: hidden; border: solid 1px #e0e0e0; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image.php?i='.$item_id.'&n=1&s=m\'); ">';
	
	// inner transparent layer v2
	if (can_i_view_item($item_id)) {
		$href = '/item/view.php?i='.$item_id;
		$out .= '<a style=" position:relative; display: block; width: 186px; height: 186px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " href="'.$href.'" >';
	} else {
		$out .= '<div style=" position:relative; display: block; width: 186px; height: 186px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " >';
	}
	
	// end inner transparent layer v2
	if (can_i_view_item($item_id)) {
		$out .= '</a>'; 
	} else {
		$out .= '</div>'; 
	}
	
	$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';

	// bagde description div
	$out .= '<div style=" min-height: 24px;  padding: 6px 20px 6px 20px; background-color: #ffffff; border: solid 1px #f0f0f0; border-radius: 3px; -moz-border-radius: 3px; ">';
	$out .= '<p style=" font-size: 10pt; color: #66737b; ">';
	$out .= 'Фрунзе';
	$out .= '</p>';
	$out .= '<p style=" font-size: 9pt; color: #66737b; ">';
	$out .= 'т.м., накл, г.э.';
	$out .= '</p>';
	$out .= '</div>';
	//

	$out .= '</div>';
	// end thumb

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_sample($param) {
	
	$out = '';

	// ship type/project div
	$out .= '<div style=" background-color: #f8f8f8f; margin-top: 30px; " >';
	
	// image schematic
	$out .= '<div style=" padding-top: 10px; padding-left: 15px; " >';
	$out .= '<img src="/images/shiptype_sample.png" />';
	$out .= '</div>';

	// black bar
	$out .= '<div style=" background-color: #000000; min-height: 4px; ">';
	$out .= '</div>';

	// sea gray bar
	$out .= '<div style=" background-color: #66737b; min-height: 4px; padding: 10px 20px 10px 20px; ">';
	$out .= '<p style=" font-size: 11pt; color: #ffffff; ">';
	$out .= 'Проект 1144, шифр «Орлан»';
	$out .= '</p>';
	$out .= '<p style=" font-size: 9pt; color: #b5b5b5; ">';
	$out .= 'Тяжелые атомные ракетные крейсера';
	$out .= '</p>';
	$out .= '</div>';
	
	$out .= '</div>';
	//


	// ship name div
	$out .= '<div style=" margin-top: 10px; background-color: #3f6b86; min-height: 4px; padding: 10px 20px 10px 20px; ">';
	$out .= '<p style=" font-size: 10pt; color: #ffffff; ">';
	$out .= 'Тяжелый атомный ракетный крейсер ≪Фрунзе≫';
	$out .= '</p>';
	$out .= '</div>';
	//

	// badge thumbs list div
	$out .= '<div style=" margin-top: 10px; ">';

	for ($i=1; $i<=7; $i++) {
		$out .= outhtml_sample_badge_thumb_div($i);
	}

	$out .= '</div>';
	// end thumbs list





	//$out .= '<img src="/images/sample1.png" />';
	


	return $out.PHP_EOL;
}



// =============================================================================
function my_get_total_item_count() {
	$qr = mydb_queryarray("".
		" SELECT COUNT(item.item_id) AS n ".
		" FROM item ".
		" WHERE item.status = 'K' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr[0]['n'];
}


// =============================================================================
function my_get_total_item_count_by_shipmodelclass($class_id) {

	$qr = mydb_queryarray("".
		" SELECT COUNT(item.item_id) AS n ".
		" FROM item ".
		" WHERE item.status = 'K' ".
		" AND item.top_shipmodelclass_id = '".$class_id."' ".
		"");
	if ($qr === false) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	
	return $qr[0]['n'];
}


// =============================================================================
function outhtml_intro_text($param) {
	
	$out = '';
	
	$out .= '<div style=" margin-left: 48px; color: #606060; ">';

		$out .= '<div style=" margin-top: 24px; width: 512px;  ">';
		
			$out .= '<h1 class="grayemb" style=" margin-bottom: 20px; ">';
				$out .= 'Добро пожаловать';
			$out .= '</h1>';
			
			$out .= '<p class="grayeleg" style=" text-align: justify; ">';
				$out .= 'Наш сервис для людей, которые так же, как и мы, интересуются коллекционированием знаков. Каталог содержит знаки по теме кораблей и судов России и СССР. Мы уделяем особенное внимание правильному описанию и классификации знаков, собираем полную и достоверную информацию о предметах коллекционирования. Мы ставим целью сделать этот каталог основным справочником на данную тему.';
			$out .= '</p>';
			
		$out .= '</div>';
		
	$out .= '</div>';
	
	
	// вставка
			
		$out .= '<div style=" margin-top: 24px; margin-bottom: 24px; width: 560px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';

			$out .= '<div style=" padding: 20px 10px 20px 0px; color: #a0a0a0;  text-align: left; margin-left: 48px; ">';

				$n = my_get_total_item_count();
				$str = get_item_count_str_case($n);
				$out .= '<p style=" font-size: 12pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing:2px; text-transform: uppercase; text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); ">';
					$out .= 'Сейчас в нашем каталоге <span style=" color: #b01010; font-size: 16pt; ">'.$n.'</span> '.$str;
				$out .= '</p>';
				
				$out .= '<p class="grayeleg" style=" margin-top: 12px; text-align: justify; ">';
				
				$out .= 'В том числе: ';
				
				// Надводные 
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(2);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по надводным, ';
				$out .= '</nobr> ';
				
				// Подводные 
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(3);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по подводным, ';
				$out .= '</nobr> ';
				
				// Парусные и гребные
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(4);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по парусным и гребным, ';
				$out .= '</nobr> ';
				
				// Гражданские
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(5);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по гражданским, ';
				$out .= '</nobr> ';
				
				/*
				// Спецназначения
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(139);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по судам спецназначения, ';
				$out .= '</nobr> ';
				
				// Вспомогательные
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(163);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по вспомогательным, ';
				$out .= '</nobr> ';
				*/
				
				// Пограничные
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(194);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по пограничным, ';
				$out .= '</nobr> ';
				
				// Таможенные
				$out .= '<nobr>';
				$n = my_get_total_item_count_by_shipmodelclass(195);
				$out .= '<span style=" color: #ad7373; ">'.$n.'</span>';
				$out .= ' по таможенным ';
				$out .= '</nobr> ';
				
				$out .= 'кораблям и судам.';
				
				$out .= '</p>';
				

			$out .= '</div>';

		$out .= '</div>';
		
	// конец вставки


	$out .= '<div style=" margin-left: 48px; color: #606060; ">';
		
		$out .= '<div style=" margin-top: 24px; margin-bottom: 36px; width: 512px;  ">';
		
			
			$out .= '<h2 class="grayemb">';
				$out .= 'Личный каталог для каждого';
			$out .= '</h2>';
			
			$out .= '<p class="grayeleg" style=" text-align: justify; ">';
				$out .= 'Вы можете составлять у нас каталог своей собственной коллекции, который будет доступен вам в любое время в любом месте, где есть доступ к сети. Используйте все имеющиеся у нас предметы и добавляйте ваши. Наполнение нашего общего каталога происходит с участием пользователей. Отмечайте ваши места хранения. Оставляейте заметки. Мы позаботимся о сохранности вашей информации.';
			$out .= '</p>';
			
			$out .= '<h2 class="grayemb">';
				$out .= 'Краудсорсинг';
			$out .= '</h2>';
			
			$out .= '<p class="grayeleg" style=" text-align: justify; ">';
				$out .= 'Наш каталог пополняется силами пользователей. Начальная основа каталога составлена из нескольких крупных коллекций, любезно предоставленных их владельцами. Вы также можете загрузить в него предметы, которые появятся в каталоге, пройдя процедуру модерации.';
			$out .= '</p>';
			
			$out .= '<h2 class="grayemb">';
				$out .= 'Поиск';
			$out .= '</h2>';
			
			$out .= '<p class="grayeleg" style=" text-align: justify; ">';
				$out .= 'Найти все известные знаки вашей тематики, которых не хватает в вашей коллекции теперь просто как никогда. Зарегистрируйтесь и получите возможности просмотра всех известных знаков нашей тематики, поиск в структурированном каталоге, учет собственной коллекции.';
			$out .= '</p>';

		$out .= '</div>';
		
	$out .= '</div>';
	
	if (!am_i_emailverified_user()) {
	
	// вставка
			
		$out .= '<div style=" margin-top: 24px; margin-bottom: 24px; width: 560px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';

			$out .= '<div style=" padding: 20px 10px 20px 0px; color: #a0a0a0; font-size: 12pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing:2px; text-transform: uppercase; text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); text-align: left; margin-left: 48px; ">';

				/*
				$n = my_get_total_item_count();
				$str = get_item_count_str_case($n);
				$out .= 'Сейчас в нашем каталоге <span style=" color: #b01010; font-size: 16pt; ">'.$n.'</span> '.$str;
				*/
				
				$out .= '<form method="GET" action="/register.php">';
				
					/*
					$out .= '<p class="grayeleg" style=" margin-top: 0px; ">';
						$out .= 'Регистрация временно невозможна';
					$out .= '</p>';
					*/
					
					
				
					$out .= '<h1 class="grayemb" style=" margin-top: 10px; color: #b01010; " >';
						$out .= 'Зарегистрируйтесь';
					$out .= '</h1>';
					
					$out .= '<p class="grayeleg" style=" margin-top: 0px; font-size: 11px; ">';
						$out .= 'и станьте постоянным пользователем';
					$out .= '</p>';

					$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Регистрация</button></div>';
					
					
					
				$out .= '</form>';

			$out .= '</div>';

		$out .= '</div>';
		
	// конец вставки
	
	}
	
	
	
			
	return $out.PHP_EOL;
}


// =============================================================================
function get_fresh_item_list($size) {

	$size = ''.intval($size);

	$q = "".
		" SELECT item.item_id, item.time_submit_finish ".
		" FROM item ".
		" WHERE item.status = 'K' ".
		// " ORDER BY item.time_submit_finish DESC ".
		" ORDER BY item.time_approved DESC ".
		" LIMIT ".$size." ".
		"";
	$qr = mydb_queryarray($q);
	if ($qr === false) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	return $qr;
}


// =============================================================================
function outhtml_item_inlist_fresh($item_id) {

	$item_id = ''.intval($item_id);

	$out = '';
	
	$qr = mydb_queryarray("".
		" SELECT item.item_id, ".
		" item.shipmodel_str, item.shipmodel_id, item.ship_str, item.notes  ".
		" FROM item ".
		" WHERE item.item_id = '".$item_id."' ".
		"");
	if (!$qr) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}
	if (sizeof($qr) != 1) {
		my_print_error("Ошибка запроса в базу данных! (".__FILE__." Line ".__LINE__.")");
		return false;
	}

	// bagde thumb div
	$out .= '<div style=" float: left; margin-right: 4px; margin-bottom: 20px; width: 98px; height: 132px; ">';

	// bagde image div
	$out .= '<div style=" width: 98px; height: 98px; display: block; overflow: hidden; border: solid 1px #9da7ac; border-radius: 3px; -moz-border-radius: 3px; text-align: center; vertical-align: middle; background-color: #ffffff; background-repeat: no-repeat; background-position: 3px 3px; background-image: url(\'/item/image_fresh.php?i='.$item_id.'&n=1&s=s\'); ">';
	
	// inner transparent layer v2
	if (can_i_view_item($item_id)) {
		$href = '/item/view.php?i='.$item_id;
		$out .= '<a style=" position:relative; display: block; width: 96px; height: 96px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " href="'.$href.'" >';
	} else {
		$out .= '<div style=" position:relative; display: block; width: 96px; height: 96px; background-repeat: no-repeat; background-position: 0px 0px; background-image: url(\'/images/spacer.gif\'); " >';
	}
	
		
	// end inner transparent layer v2
	if (can_i_view_item($item_id)) {
		$out .= '</a>'; 
	} else {
		$out .= '</div>'; 
	}
	
	$out .= '<div style=" clear: both; "></div>';

	$out .= '</div>';

	//


	$out .= '<div style=" width: 98px; overflow: hidden; min-height: 24px; background-color: #dce2e6; border: solid 1px #9da7ac; border-radius: 3px; -moz-border-radius: 3px; ">';
	
		$out .= '<div style=" min-height: 24px; border-bottom: solid 6px #'.$color.'; ">';
	
			$out .= '<div style=" padding: 0px 15px 2px 4px; ">';

				// 66737b 
				$out .= '<p style=" font-size: 9pt; color: #4b575e; width: 88px; overflow: hidden; white-space: nowrap; " title="'.$qr[0]['ship_str'].'" >';
					//$out .= 'Фрунзе';
					$out .= $qr[0]['ship_str'];
				$out .= '</p>';

				$modelfull = get_item_shipmodel_name_full($qr[0]['shipmodel_id'], $qr[0]['shipmodel_str']);
				if ($modelfull != '') {
					$out .= '<p style=" font-size: 8pt; color: #9ca5ab; width: 88px; overflow: hidden; white-space: nowrap; padding-left: 1px; " title="'. $modelfull.'" >';
					//$out .= 'т.м., накл, г.э.';
						$out .= $modelfull;
					$out .= '</p>';
				}

				

				$out .= '<div style=" clear: both; "></div>';
				
			$out .= '</div>';
		
		$out .= '</div>';

	$out .= '</div>';

	//

	
	$out .= '</div>';
	// end thumb

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_fresh_new_items($param) {


	if ($GLOBALS['is_registered_user']) {
		$count = 24;
	} else {
		$count = 6;
	}
	$freshes = get_fresh_item_list($count);
	if (sizeof($freshes) < 1) return '';
	
	$out = '';
	
	//576px
	
	//$out .= '<div style=" float: left;  clear: none; width: 612px; padding: 0px 0px 10px 0px; color: #888888; line-height: 125%; ">';

	//$out .= '<div style=" background-color: #f8f8f8f; margin-top: 30px; " >';
	
	if ($GLOBALS['is_registered_user']) {
		$style = '';
	} else {
		$style = ' width: 262px; ';
	}

	$out .= '<div style=" clear: none; '.$style.' padding: 0px 0px 10px 0px; margin-top: 24px; " >';

		// $out .= '<div style=" margin-left: 18px; margin-top: 24px; margin-bottom: 36px; height: 1px; width: 508px; border-top: 1px solid #b6bec3; box-shadow: inset 0 1px 0 white; "></div>';
		
		$out .= '<h2 class="grayemb" style=" margin-left: 18px; color: #767f83; line-height: 150%; " >';
			$out .= 'Недавнее пополнение каталога';
		$out .= '</h2>';

			
/*
			// black bar
			$out .= '<div style=" background-color: #000000; min-height: 4px; ">';
			$out .= '</div>';

			// sea gray bar
			$out .= '<div style=" background-color: #66737b; min-height: 4px; padding: 10px 20px 10px 20px; ">';
				$out .= '<p style=" font-size: 11pt; color: #ffffff; ">';
					$out .= 'Пополнение каталога';
				$out .= '</p>';

			$out .= '</div>';
			
		$out .= '</div>';
*/


		// badge thumbs list div
		$out .= '<div style=" margin-top: 10px; margin-left: 16px; ">';


			for ($i=0; $i<sizeof($freshes); $i++) {
				$out .= outhtml_item_inlist_fresh($freshes[$i]['item_id']);
			}

			$out .= '<div style=" clear: left; "></div>';

		$out .= '</div>';
		//
		
		
		$href = '/item/addedon.php';
		
		
		if ($GLOBALS['is_registered_user']) {
			$out .= '<a href="'.$href.'" class="grayemb" style=" margin-left: 18px; color: #841a1a; font-size: 14px; line-height: 150%; font-family: Georgia,​ Times New Roman,​ Times,​ serif; text-shadow: 0px -1px 0px rgba(87,99,105,0.2), 0px 1px 0px rgba(245,251,255,0.5); " >';
				$out .= 'Все пополнения за месяц';
			$out .= '</a>';
		}
		
		
		/*
		$out .= '<form method="GET" action="/item/add.php">';
					
				
			$out .= '<div style=" margin-top: 10px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Смотреть все пополнения за месяц</button></div>';

					
		$out .= '</form>';
		*/
	
	$out .= '</div>';
	// end thumbs list

	return $out.PHP_EOL;
}



// =============================================================================
function outhtml_welcome_screen_sidebar($param) {

	if ($GLOBALS['user_id'] > 0) {
		// prepared query
		$a = array();
		$q = "".
			" SELECT user.email_verified, user.phone_verified, ".
			" user.firstname, user.lastname ".
			" FROM user ".
			" WHERE ( user.user_id = ? ) ".
			"";
		$a[] = $GLOBALS['user_id'];
		$t = 'i';
		$qres = mydb_prepquery($q, $t, $a);
		if ($qres === false) {
			die('mydb_prepquery() fatal error');
		}
		$email_verified = ($qres[0]['email_verified'] == 'Y');
		$phone_verified = ($qres[0]['phone_verified'] == 'Y');
		$name_given = (($qres[0]['firstname'] != '') && ($qres[0]['lastname'] != ''));
	} else {
		$email_verified = false;
		$phone_verified = false;
		$name_given = false;
	}
	
	//

	$out = '';
	
	// width: 310px;
	$out .= '<div style=" float: right; clear: none;  ">';
	
	if (($GLOBALS['user_id'] > 0) && (!$GLOBALS['is_registered_user'])) {
		
			$str = '';
			$link = '/personal/email_verify.php';
			if (!$email_verified) $str .= ' адрес электронной почты ';
			if (!$phone_verified) {
				if ($str != '') {
					$str .= ' и ';
				} else {
					$link = '/personal/phone_verify.php';
					if (!$name_given) $link = '/personal/name_modify.php';
				}
				$str .= ' номер телефона ';
			}
		
			$out .= '<div style=" width: 308px; margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #ffe49f; border: solid 1px #ffe49f; border-radius: 3px; color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing: 2px; text-transform: uppercase; ">';

				$out .= '<div style=" padding: 20px 20px 20px 20px;  text-align: left; ">';
				
					$out .= '<form method="GET" action="'.$link.'">';
					
						$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; margin-top: 10px; font-size: 9pt; color: #808080; white-space: normal; line-height: 150%; "><strong>Подтвердите</strong> ваш '.$str.' и станьте зарегистрированным пользователем.</div>';
						
						$out .= '<div style=" margin-top: 10px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">ПОДТВЕРДИТЬ</button></div>';

					
					$out .= '</form>';

				$out .= '</div>';

			$out .= '</div>';
					
		}

	if (!am_i_emailverified_user()) {
	
		// width: 310px;
		$out .= '<div style=" margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; ">';

			// text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); 
			$out .= '<div style=" padding: 20px 10px 20px 20px; color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing:2px; text-transform: uppercase; text-align: left; ">';
			
				$out .= '<form method="POST" action="/index.php">';

					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 9pt; color: #a0a0a0; ">e-mail:</div>';
					
					$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverlightblueborder lightgraygradientinv" style=" text-align: left; padding-right: 10px; font-size: 10pt; background-color: #e7e9ea; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="email" value="" /></div>';
					
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; margin-top: 20px; font-size: 9pt; color: #a0a0a0; ">пароль:</div>';
					
					$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverlightblueborder lightgraygradientinv" style=" text-align: left; padding-right: 10px; font-size: 10pt; background-color: #e7e9ea; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" type="password" name="password" value="" /></div>';
					
					$out .= '<div style=" margin-top: 20px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" name="action" value="login" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Войти</button></div>';
					
					$out .= '<div style=" margin-top: 10px; margin-left: 2px; vertical-align: top; "><a class="reseted" style="" href="/restore_password.php">вспомнить пароль</a></div>';
				
				$out .= '</form>';

			$out .= '</div>';

		$out .= '</div>';
		
		//
		
		
		// width: 310px;
		$out .= '<div style=" margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #b6bec3; border: solid 1px #b6bec3; border-radius: 3px; ">';

			// text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); 
			
			// color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing:2px; text-transform: uppercase;
			$out .= '<div style=" padding: 20px 10px 20px 20px;  text-align: left; ">';
			
				$out .= outhtml_fresh_new_items($param);

			$out .= '</div>';

		$out .= '</div>';
		
		
		
		//
	
		/*
		$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';
			$out .= '<form method="POST" action="/index.php">';
			
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; ">e-mail:</div>';
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" name="email" value="" /></div>';
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; padding-top: 8px; font-size: 10pt; color: #c0c0c0; ">пароль:</div>';
				$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverwhiteborder" style=" text-align: left; padding-right: 10px; font-size: 12px; background-color: #ffffff; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" type="password" name="password" value="" /></div>';
				$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" name="action" value="login" style="background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; ">Войти</button></div>';
			$out .= '</form>';
		$out .= '</div>';
		*/
			
		/*
			
		$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';
		
			$out .= '<form method="GET" action="/register.php">';
				
				$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
					$out .= '<p style=" margin-bottom: 6px; " ><strong>Зарегистрируйтесь</strong> и станьте постоянным пользователем.</p>';
					$out .= '<p style=" margin-bottom: 6px; " >Ведите учет вашей коллекции у нас и получайте к ней доступ из любой точки мира.</p>';
					$out .= '<p style=" margin-bottom: 6px; " >Используйте нашу помощь для обмена с другими пользователями. Мы гарантируем вашу анонимность и конфиденциальность вашей коллекции.</p>';
					$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" style="background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; ">Регистрация</button></div>';
				$out .= '</div>';
				
			$out .= '</form>';
			
		$out .= '</div>';

		*/
		
	} else {
	
		// if is_registered_user
		
		
		
		
		
		//
				
		if (can_i_submit_item()) {
		
			// width: 310px;
			$out .= '<div style=" width: 308px; margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing: 2px; text-transform: uppercase; ">';

				// text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); 
				
				// color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing:2px; text-transform: uppercase;
				$out .= '<div style=" padding: 20px 20px 20px 20px;  text-align: left; ">';
				
					$out .= '<form method="GET" action="/item/add.php">';
					
						$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; margin-top: 10px; font-size: 9pt; color: #a0a0a0; white-space: normal; line-height: 150%; "><strong>Загрузите</strong> изображение знака, которого у нас нет</div>';
						
						$out .= '<div style=" margin-top: 10px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Загрузить</button></div>';

					
					$out .= '</form>';

				$out .= '</div>';

			$out .= '</div>';
			
			/*
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; margin-top: 20px; font-size: 9pt; color: #a0a0a0; ">пароль:</div>';
					
					$out .= '<div style=" vertical-align: top; padding-right: 16px; "><input class="hoverlightblueborder lightgraygradientinv" style=" text-align: left; padding-right: 10px; font-size: 10pt; background-color: #e7e9ea; padding: 4px; border-radius: 3px; -moz-border-radius: 3px; min-width: 180px; " size="20" type="password" name="password" value="" /></div>';
					
					$out .= '<div style=" margin-top: 20px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" name="action" value="login" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Войти</button></div>';
			*/
		
			/*
			$out .= '<div style=" margin-top: 10px; background-color: #66737b; padding: 20px; border-top: solid 4px #000000; ">';
				$out .= '<form method="GET" action="/item/add.php">';
					
					$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; font-size: 10pt; color: #c0c0c0; line-height: 125%; ">';
						$out .= '<p style=" margin-bottom: 6px; " ><strong>Загрузите</strong> картинку знака, которого у нас нет.</p>';
						$out .= '<div style=" padding-top: 8px; vertical-align: top; "><button class="hoverwhiteborder" type="submit" style="background-color: #3f6b86; border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #c0c0c0; padding: 2px 12px 3px 12px; min-width: 130px; ">Загрузить</button></div>';
					$out .= '</div>';
					
				$out .= '</form>';
			$out .= '</div>';
			*/
		
		}

		/*
		if ($GLOBALS['user_id'] == 2) {
		
			$out .= '<div style=" width: 308px; margin-left: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #ffffff; border: solid 1px #ffffff; border-radius: 3px; color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing: 2px; text-transform: uppercase; ">';

				$out .= '<div style=" padding: 20px 20px 20px 20px;  text-align: left; ">';
				
					$out .= '<form method="GET" action="/item/load_blueprint.php">';
					
						$out .= '<div style=" padding-bottom: 4px; padding-left: 4px; margin-top: 10px; font-size: 9pt; color: #a0a0a0; white-space: normal; line-height: 150%; "><strong>Загрузите</strong> отсутствующий силуэт проекта</div>';
						
						$out .= '<div style=" margin-top: 10px; vertical-align: top; "><button class="lightbluegradient hoverlightblueborder" type="submit" style=" border-radius: 3px; -moz-border-radius: 3px; font-size: 10pt; vertical-align: bottom; color: #606060; padding: 2px 12px 3px 12px; min-width: 130px; ">Загрузить</button></div>';

					
					$out .= '</form>';

				$out .= '</div>';

			$out .= '</div>';
		
		}
		*/

	}
	
	$out .= '</div>';

	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_welcome_screen_main($param) {

	$out = '';
	
	$out .= '<div style=" float: left;  clear: none; width: 625px; padding: 20px 5px 20px 0px;  line-height: 125%; ">';
		
		$out .= outhtml_intro_text($param);
		
	$out .= '</div>';
	
	return $out.PHP_EOL;
}


// =============================================================================
function outhtml_welcome_screen($param) {
	
	$out = '';
	
	$out .= '<div style=" background-color: #f8f8f8; " >';
		
		$out .= outhtml_welcome_screen_main($param);
		
		$out .= outhtml_welcome_screen_sidebar($param);
		
		$out .= '<div style=" clear: both; " ></div>';
		
		if ($GLOBALS['is_registered_user']) {

			// width: 590px;
			$out .= '<div style="  margin-right: 18px; margin-top: 24px; margin-bottom: 24px; box-shadow: 0 1px 2px gray; background-color: #b6bec3; border: solid 1px #b6bec3; border-radius: 3px; ">';

				// text-shadow: 0px -1px 0px rgba(240,240,240,0.7), 0px 1px 0px rgba(250,250,250,0.3); 
				
				// color: #808080; font-size: 11pt; font-family: Georgia,​ Times New Roman,​ Times,​ serif; letter-spacing:2px; text-transform: uppercase;
				$out .= '<div style=" padding: 20px 10px 20px 30px;  text-align: left; ">';
				
					$out .= outhtml_fresh_new_items($param);

				$out .= '</div>';

			$out .= '</div>';
		}
		
	$out .= '</div>';

	return $out.PHP_EOL;
}

?>