<?php
defined( 'ABSPATH' ) or die();

/**
 * Admin message
 *
 * @since 1.5.0
 *
 * @param string $message Message.
 * @param string $type Update Type.
 */
function cpac_admin_message( $message = '', $type = 'updated' ) {
	$GLOBALS['cpac_messages'][] = '<div class="cpac_message ' . $type . '"><p>' . $message . '</p></div>';

	add_action( 'admin_notices', 'cpac_admin_notice' );
	add_action( 'network_admin_notices', 'cpac_admin_notice' );
}

/**
 * Admin Notice
 *
 * This uses the standard CSS styling from WordPress, no additional CSS have to be loaded.
 *
 * @since 1.5.0
 *
 * @return string Message.
 */
function cpac_admin_notice() {
	echo implode( $GLOBALS['cpac_messages'] );
}

function cpac_settings_message( $message = '', $type = 'updated' ) {
	$GLOBALS['cpac_settings_messages'][] = '<div class="cpac_message inline ' . $type . '"><p>' . $message . '</p></div>'; // .inline prevents JS from placing it below h2
}

/**
 * Returns true if the installed version of WooCommerce is version X or greater
 *
 * @since 2.3.4
 * @return boolean true if the installed version of WooCommerce is version X or greater
 */
function cpac_is_wc_version_gte( $version = '1.0' ) {
	$wc_version = defined( 'WC_VERSION' ) && WC_VERSION ? WC_VERSION : null;

	return $wc_version && version_compare( $wc_version, $version, '>=' );
}

/**
 * @return bool True when ACF plugin is activated.
 */
function cpac_is_acf_active() {
	return class_exists( 'acf', false );
}

/**
 * @return bool True when WooCommerce plugin is activated.
 */
function cpac_is_woocommerce_active() {
	return class_exists( 'WooCommerce', false );
}

/**
 * @return bool True when Admin Columns Pro plugin is activated.
 */
function cpac_is_pro_active() {
	return function_exists( 'ac_pro' );
}

/**
 * @return bool True when Admin Columns ACF add-on plugin is activated.
 */
function cpac_is_addon_acf_active() {
	return class_exists( 'CPAC_Addon_ACF', false );
}

/**
 * @return bool True when Admin Columns WooCommerce add-on plugin is activated.
 */
function cpac_is_addon_woocommerce_active() {
	return class_exists( 'CPAC_Addon_WC', false );
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
 * @see ac_get_site_url()
 */
function ac_site_url( $path = '' ) {
	echo ac_get_site_url( $path );
}

/**
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
 * Adds columns classnames from specified directory
 *
 * @param string $columns_dir Columns directory
 * @param string $prefix Autoload prefix
 * @param array $columns Columns [ class_name => autoload ]
 *
 * @return array
 */
function ac_add_autoload_columns( $columns_dir, $prefix, $columns = array() ) {
	$_columns = AC()->autoloader()->get_class_names_from_dir( $columns_dir, $prefix );

	// set to autoload (true)
	return array_merge( $columns, array_fill_keys( $_columns, true ) );
}

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
 * @param strong $tab Specifies a tab screen (optional)
 *
 * @return bool True if the current screen is the settings screen, false otherwise
 */
function cac_is_setting_screen( $tab = '' ) {
	_deprecated_function( __FUNCTION__, 'NEWVERSION', 'AC()->settings()->is_current_tab( $tab )' );

	return AC()->settings()->is_current_tab( $tab );
}