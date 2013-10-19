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

