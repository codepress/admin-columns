<?php
/*
Plugin Name: Admin Columns
Version: 3.1.7
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

/**
 * Show legacy PHP message on the plugins screen
 */
function ac_legacy_php_version_notice() {
	$screen = get_current_screen();

	if ( ! $screen->id !== 'plugins' ) {
		return;
	}

	$message = sprintf( __( 'Admin Columns requires at least PHP 5.3.0 to function properly. Your server currently runs PHP %s.', 'codepress-admin-columns' ), PHP_VERSION );
	$message .= ' ' . __( 'You can ask your hosting provider to upgrade to the latest version of PHP.', 'codepress-admin-columns' );

	printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $message ) );
}

/**
 * Load text-domain
 */
function ac_localize() {
	load_plugin_textdomain( 'codepress-admin-columns', false, dirname( __FILE__ ) . '/languages/' );
}

add_action( 'plugins_loaded', 'ac_localize' );

/**
 * Check current PHP version
 */
if ( version_compare( PHP_VERSION, '5.3.0', '<' ) ) {
	add_action( 'admin_notices', 'ac_legacy_php_version_notice' );

	return;
}

require_once 'bootstrap.php';