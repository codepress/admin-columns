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
	$GLOBALS['cpac_message']	  = $message;
	$GLOBALS['cpac_message_type'] = $type;

	add_action('admin_notices', 'cpac_admin_notice' );
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
    echo '<div class="' . $GLOBALS['cpac_message_type'] . '" id="message"><p>' . $GLOBALS['cpac_message'] . '</p></div>';
}
