<?php

require_once 'api.php';
require_once 'classes/Autoloader.php';

AC\Autoloader::instance()->register_prefix( 'AC', __DIR__ . '/classes' );

AC();