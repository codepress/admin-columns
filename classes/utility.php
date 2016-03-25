<?php

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
 * Is doing ajax
 *
 * @since 2.3.4
 */
function cac_is_doing_ajax() {
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
		return false;
	}

	$is_doing_ajax = cac_wp_is_doing_ajax() || isset( $_REQUEST['storage_model'] );

	return apply_filters( 'cac/is_doing_ajax', $is_doing_ajax );
}

/**
 * Is WordPress doing ajax
 *
 * @since 2.5
 */
function cac_wp_is_doing_ajax() {
	$storage_model = false;

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

		switch ( filter_input( INPUT_POST, 'action' ) ) {
			case 'inline-save' :  // Quick edit
				$storage_model = filter_input( INPUT_POST, 'post_type' );
				break;
			case 'add-tag' : // Adding term
			case 'inline-save-tax' : // Quick edit term
				$storage_model = 'wp-taxonomy_' . filter_input( INPUT_POST, 'taxonomy' );
				break;
			case 'edit-comment' : // Quick edit comment
			case 'replyto-comment' :  // Inline reply on comment
				$storage_model = 'wp-comments';
				break;
		}
	}

	return $storage_model;
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

function cpac_is_acf_active() {
	return class_exists( 'acf', false );
}

function cpac_is_woocommerce_active() {
	return class_exists( 'WooCommerce', false );
}

function cpac_is_pro_active() {
	return class_exists( 'CAC_Addon_Pro', false );
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
	global $pagenow;

	if ( ! ( 'options-general.php' === $pagenow && isset( $_GET['page'] ) && ( 'codepress-admin-columns' === $_GET['page'] ) ) ) {
		return false;
	}

	if ( $tab && ( empty( $_GET['tab'] ) || ( isset( $_GET['tab'] ) && $tab !== $_GET['tab'] ) ) ) {
		return false;
	}

	return true;
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