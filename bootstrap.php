<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/api.php';
require_once __DIR__ . '/classes/Autoloader.php';

AC\Autoloader::instance()->register_prefix( 'AC', __DIR__ . '/classes' );
AC\Autoloader\Underscore::instance()
	->add_alias( 'AC\ListScreen', 'AC_ListScreen' )
	->add_alias( 'AC\Settings\FormatValue', 'AC_Settings_FormatValueInterface' )
	->add_alias( 'AC\Column\Media\MediaParent', 'AC_Column_Media_Parent' )
	->add_alias( 'AC\Column\Post\PostParent', 'AC_Column_Post_Parent' );

/**
 * For loading external resources, e.g. column settings.
 * Can be called from plugins and themes.
 */
do_action( 'ac/ready', AC() );