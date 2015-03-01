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

/**
 * Is doing ajax
 *
 * @since 2.3.4
 */
function cac_is_doing_ajax() {
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
		return false;
	}

	if ( ( isset( $_POST['action'] ) && 'inline-save' === $_POST['action'] ) ) {
		return true;
	}

	if ( ( isset( $_POST['action'] ) && 'edit-comment' === $_POST['action'] ) ) {
		return true;
	}

	if ( ( isset( $_POST['action'] ) && 'replyto-comment' === $_POST['action'] ) ) {
		return true;
	}

	if ( ( isset( $_POST['plugin_id'] ) && 'cpac' == $_POST['plugin_id'] ) || ( isset( $_GET['plugin_id'] ) && 'cpac' == $_GET['plugin_id'] ) ) {
		return true;
	}

	return false;
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