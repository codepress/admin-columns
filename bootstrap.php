<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'api.php';
require_once 'classes/Autoloader.php';

AC\Autoloader::instance()->register_prefix( 'AC', __DIR__ . '/classes' );

/**
 * For loading external resources, e.g. column settings.
 * Can be called from plugins and themes.
 */
do_action( 'ac/ready', AC() );