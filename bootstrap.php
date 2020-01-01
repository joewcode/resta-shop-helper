<?php

if ( !defined('JSTART') ) die('access denied');

include 'app/helpers.php';

// include global config 
require_once $_SERVER['DOCUMENT_ROOT'].'/admin/setup/config.sql.php';
define('PREF', $sql_access['pref']);

// DB connection or die
try {
    $db = new PDO('mysql:host=localhost;dbname='.$sql_access['db'], $sql_access['user'], $sql_access['pass']);
} catch (PDOException $e) {
    print 'Error!: ' . $e->getMessage() . '<br/>';
    die();
}

