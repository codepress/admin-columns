<?php

/**
 * @return bool True when Admin Columns Pro plugin is activated.
 */
function ac_is_pro_active() {
	return function_exists( 'ACP' );
}

/**
 * Get the url where the Admin Columns website is hosted
 *
 * @return string
 */
function ac_get_site_url( $path = '' ) {
	$url = 'https://www.admincolumns.com';

	if ( ! empty( $path ) ) {
		$url .= '/' . trim( $path, '/' ) . '/';
	}

	return $url;
}

/**
 * Admin Columns Twitter username
 *
 * @return string
 */
function ac_get_twitter_handle() {
	return 'wpcolumns';
}

/**
 * @see ac_get_site_url()
 */
function ac_site_url( $path = '' ) {
	echo ac_get_site_url( $path );
}

/**
 * Simple helper methods for AC_Column objects
 *
 * @since NEWVERSION
 */
function ac_helper() {
	return AC()->helper();
}

/**
 * @since NEWVERSION
 * @return bool True when a minimum version of Admin Columns Pro plugin is activated.
 */
function ac_is_version_gte( $version ) {
	return version_compare( AC()->get_version(), $version, '>=' );
}

/**
 * Returns row actions for the WP_List_Tables
 *
 * @return AC_Column_ActionColumnHelper
 */
function ac_action_column_helper() {
	return AC_Column_ActionColumnHelper::instance();
}

/**
 * Deprecated functions
 */

/**
 * Is doing ajax
 *
 * @since 2.3.4
 */
function cac_is_doing_ajax() {
	_deprecated_function( __FUNCTION__, 'NEWVERSION' );

	$is_doing_ajax = AC()->list_screen_manager()->get_list_screen_when_doing_ajax() || ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['list_screen'] ) );

	return apply_filters( 'cac/is_doing_ajax', $is_doing_ajax );
}

/**
 * Is WordPress doing ajax
 *
 * @since 2.5
 */
function cac_wp_is_doing_ajax() {
	_deprecated_function( __FUNCTION__, 'NEWVERSION', 'AC()->list_screen_manager()->get_list_screen_when_doing_ajax()' );

	return AC()->list_screen_manager()->get_list_screen_when_doing_ajax();
}

/**
 * Whether the current screen is the Admin Columns settings screen
 *
 * @since 2.4.8
 *
 * @param string $tab Specifies a tab screen (optional)
 *
 * @return bool True if the current screen is the settings screen, false otherwise
 */
function cac_is_setting_screen( $tab = '' ) {
	_deprecated_function( __FUNCTION__, 'NEWVERSION', 'AC()->admin()->is_current_tab( $tab )' );

	return AC()->admin()->is_current_tab( $tab );
}

/**
 * Returns true if the installed version of WooCommerce is version X or greater
 *
 * @since 2.3.4
 * @deprecated NEWVERSION
 * @return boolean true if the installed version of WooCommerce is version X or greater
 */
function cpac_is_wc_version_gte( $version = '1.0' ) {
	_deprecated_function( __FUNCTION__, 'NEWVERSION' );

	return false;
}

/**
 * @deprecated NEWVERSION
 * @return bool True when Admin Columns Pro plugin is activated.
 */
function cpac_is_pro_active() {
	_deprecated_function( __FUNCTION__, 'NEWVERSION', 'ac_is_pro_active' );

	return ac_is_pro_active();
}