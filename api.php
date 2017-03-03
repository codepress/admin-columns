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
 * Url with utm tags
 *
 * @param string $path
 * @param string $utm_medium
 * @param string $utm_content
 *
 * @return string
 */
function ac_get_site_utm_url( $path, $utm_medium, $utm_content = null, $utm_campaign = false ) {
	$url = ac_get_site_url( $path );

	if ( ! $utm_campaign ) {
		$utm_campaign = 'plugin-installation';
	}

	$args = array(
		// Referrer: plugin
		'utm_source'   => 'plugin-installation',

		// Specific promotions or sales
		'utm_campaign' => $utm_campaign,

		// Marketing medium: banner, support documentation, email
		'utm_medium'   => $utm_medium,

		// Used for differentiation of medium
		'utm_content'  => $utm_content,
	);

	$args = array_map( 'sanitize_key', $args );

	return add_query_arg( $args, $url );
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
 * Manually set the columns for a list screen
 * This overrides the database settings and thus renders the settings screen for this list screen useless
 *
 * If you like to register a column of your own please have a look at our documentation.
 * We also have a free start-kit available, which contains all the necessary files.
 *
 * Documentation: https://www.admincolumns.com/documentation/developer-docs/creating-new-column-type/
 * Starter-kit: https://github.com/codepress/ac-column-template/
 *
 * @since 2.2
 *
 * @param string|array $list_screen_key List screen key or keys
 * @param array $column_data
 */
function ac_register_columns( $list_screen_keys, $column_data ) {
	AC()->api()->load_columndata( $list_screen_keys, $column_data );
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

	$is_doing_ajax = AC()->table_screen()->get_list_screen_when_doing_ajax() || ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['list_screen'] ) );

	return apply_filters_deprecated( 'cac/is_doing_ajax', array( $is_doing_ajax ), 'NEWVERSION' );
}

/**
 * Is WordPress doing ajax
 *
 * @since 2.5
 */
function cac_wp_is_doing_ajax() {
	_deprecated_function( __FUNCTION__, 'NEWVERSION' );

	return AC()->table_screen()->get_list_screen_when_doing_ajax();
}

/**
 * Whether the current screen is the Admin Columns settings screen
 *
 * @since 2.4.8
 *
 * @param string $slug Specifies a page screen (optional)
 *
 * @return bool True if the current screen is the settings screen, false otherwise
 */
function cac_is_setting_screen( $slug = '' ) {
	_deprecated_function( __FUNCTION__, 'NEWVERSION', 'AC()->admin()->is_current_page( $slug )' );

	return AC()->admin()->is_current_page( $slug );
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