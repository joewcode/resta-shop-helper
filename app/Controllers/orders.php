<?php

include 'app/Models/Order.php';

$action = isset($_GET['act']) ? $_GET['act'] : false;
if ( $action ) {
	if ( $action == 'list' and isset($_GET['today']) ) {
		$orderDetail = Order::detailDayList($_GET['today']);
	}
	
	elseif ($action == 'download') {
		if ( strtotime($_GET['today']) > 0 ) {
			$orders = Order::detailDayList($_GET['today']);
			$products = array();
			foreach ($orders as $order) {
				foreach ($order['prod_ids'] as $prod) {
					// array('Объект доставки', 'Цех изготов.', 'Наименование товара', 'Ед.', 'Кол-во', 'Отправка', 'Ф.И.О. получателя', 'Комментарий телефон');
					$products[] = array($order['addr'], 't', $prod['name'], $prod['unit'], $prod['count'], $order['delivery_time'], $order['name'],  $order['comm'].' (т. '.$order['phone'].')');
				}
			}
			// print_r($products); exit();
			// load
			Order::toExcel($_GET['today'], $products);
		}
	}
} else {
	$orderList = Order::groupDayList();
}

