<?php

include 'app/Models/Statistic.php';

$stats = new Statistic();

// restart function
if ( isset($_GET['setup']) ) {
	$stats->init();
	header('Location: ?mod=stats');
	exit();
}

$mostCustomers = $stats->mostCustomers();
$popularCustomers = $stats->popularCustomers();
$popularRestaurant = $stats->popularRestaurant();
$popularGoods = $stats->popularGoods();
