<?php
/*
Plugin Name: Admin Columns
Version: 4.5.4
Description: Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: AdminColumns.com
Author URI: https://www.admincolumns.com
Plugin URI: https://www.admincolumns.com
Requires PHP: 5.6.20
Text Domain: codepress-admin-columns
Domain Path: /languages
License: GPL v3

Admin Columns Plugin
Copyright (C) 2011-2021, Admin Columns - info@admincolumns.com
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_admin() ) {
	return;
}

define( 'AC_FILE', __FILE__ );
define( 'AC_VERSION', '4.5.4' );

require_once __DIR__ . '/classes/Dependencies.php';

add_action( 'after_setup_theme', function () {
	$dependencies = new AC\Dependencies( plugin_basename( AC_FILE ), AC_VERSION );
	$dependencies->requires_php( '5.6.20' );

	if ( $dependencies->has_missing() ) {
		return;
	}

	require_once __DIR__ . '/api.php';
	require_once __DIR__ . '/classes/Autoloader.php';

	$class_map = __DIR__ . '/config/autoload-classmap.php';

	if ( is_readable( $class_map ) ) {
		AC\Autoloader::instance()->register_class_map( require $class_map );
	} else {
		AC\Autoloader::instance()->register_prefix( 'AC', __DIR__ . '/classes' );
	}

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
}, 1 );