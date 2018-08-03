<?php
/*
Plugin Name: Admin Columns
Version: 3.2.3
Description: Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: AdminColumns.com
Author URI: https://www.admincolumns.com
Plugin URI: https://www.admincolumns.com
Requires PHP: 5.3
Text Domain: codepress-admin-columns
Domain Path: /languages
License: GPL v3

Admin Columns Plugin
Copyright (C) 2011-2018, Admin Columns - info@admincolumns.com
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

require_once 'classes/Dependencies.php';

function ac_init() {
	$dependencies = new AC_Dependencies( plugin_basename( AC_FILE ) );
	$dependencies->check_php_version( '5.3' );

	if ( $dependencies->has_missing() ) {
		return;
	}

	require_once __DIR__ . '/bootstrap.php';
}

add_action( 'after_setup_theme', 'ac_init', 1 );