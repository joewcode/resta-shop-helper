<?php
//
define ('JSTART', microtime(true));
require 'bootstrap.php';

// get controller name
$controller = getController();
// include controller
include 'app/Controllers/'. $controller .'.php';
// View html
include 'template/'. $controller .'.html';

// echo 'End.. '. round(microtime(true) - JSTART, 4).' сек.';
