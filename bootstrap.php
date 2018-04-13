<?php

define( 'AC_FILE', __FILE__ );

require_once 'api.php';
require_once 'classes/Autoloader.php';

$loader = AC_Autoloader::instance();
$loader->register_prefix( 'AC', __DIR__ . '/classes' );

AC();