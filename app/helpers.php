<?php
// help functions

// ---
function conv($n) {
    return iconv('utf-8' , 'windows-1251', $n);
}

function db() {
	return $GLOBALS['db'];
}

// Router 
function getController() {
	$mod = isset($_GET['mod']) ? $_GET['mod'] : 'index';
	$c = 'index';
	switch ($mod) {
		case 'index': $c = 'index'; break;
		case 'orders': $c = 'orders'; break;
		case 'catalog': $c = 'catalog'; break;
		case 'stats': $c = 'stats'; break;
		
		default: $c = '404';
	}
	return $c;
}

function baseDeCrypt($data) {
	return unserialize( base64_decode($data) );
}

function optimizeDecrypt($data) {
	$list = array();
	foreach ( $data as $val ) {
		$v = array();
		$v['id'] = $val['id'];
		$v['name'] = $val['name'];
		$v['order_amount'] = $val['order_amount'];
		$v['art'] = $val['art'];
		$v['price'] = $val['price'];
		$v['unit'] = $val['unit'];
		$v['step'] = $val['step'];
		$v['count'] = $val['count'];
		$list[] = $v;
	}
	return $list;
}
function optimizeDecryptStats($data) {
	$list = array();
	foreach ( $data as $val ) {
		$v = array();
		$v['id'] = $val['id'];
		$v['name'] = $val['name'];
		$v['price'] = $val['price'];
		$v['count'] = $val['count'];
		$list[] = $v;
	}
	return $list;
}

function arrayToCsv($array, $titles) {
	ob_start();
	$df = fopen('php://output', 'w');
	if (isset($_GET['win'])) $titles = array_map('conv', $titles);
	fputcsv($df, $titles, ';');
	foreach ($array as $row) {
		if (isset($_GET['win'])) {
			$row = array_map('conv', $row);
		}
		fputcsv($df, $row, ';');
	}
	fclose($df);
	return ob_get_clean();
}



function optimizeAddr($addr) {
	$revaddr = array(
		'/Базовый комплекс: 2-й Моторный переулок 12\/1/',
		'/«Компот»: Дерибасовская, 20/',
		'/«Компот»: Адмиральский пр-т, 1/',
		'/«Дача»: Французский бульвар, 85/',
		'/«Стейкхаус»: Дерибасовская, 20/',
		'/Офис: переулок Маланова, 1/',
		'/«Компот»: Пантелеймоновская, 70/',
		'/«Тавернетта»: Екатерининская, 45/',
		
		'/«Компот»: Аркадия/',
		'/«Компот»: Морвокзал/',
		
        );
    $revaddrto = array(
			'БК',
			'Овощной',
			'5 Фонтана',
			'Дача',
			'Стейк',
			'Офис',
			'Чижи',
			'ТТ',
			
			'нет',
			'нет',
			
        );
	return preg_replace($revaddr, $revaddrto, $addr);
}

function arrayToPlist($arr) {
	$text = '';
	foreach ($arr as $a) {
		$text.= '&#8226;'. $a['name'] .' => <span class="badge badge-primary">'. $a['count'] .' '. $a['unit'] .'</span><br />';
	}
	return $text;
}

function sqlProtect($str) {
	$replace_list = array("/'/", '/"/', '/\//');
	$str = preg_replace($replace_list, '', $str);
	return $str;
}


