<?php
class CPAC_Utility {
		
	/**
	 * Get licenses
	 *
	 * @since 2.0.0
	 *
	 * @return array Licenses.
	 */
	function get_licenses() {		
		
		return array(
			'sortable' 		=> new CPAC_Licence( 'sortable' ),
			'customfields' 	=> new CPAC_Licence( 'customfields' )
		);
	}	

	/**
	 * Admin message
	 *
	 * @since 2.0.0.0
	 *
	 * @param string $message Message.
	 * @param string $type Update Type.
	 */
	public static function admin_message( $message = '', $type = 'updated' ) {
		$GLOBALS['cpac_message']	  = $message;
		$GLOBALS['cpac_message_type'] = $type;

		add_action('admin_notices', array( 'CPAC_Utility', 'admin_notice' ) );
	}

	/**
	 * Admin Notice
	 *
	 * @since 2.0.0.0
	 *
	 * @return string Message.
	 */
	public static function admin_notice() {
	    echo '<div class="' . $GLOBALS['cpac_message_type'] . '" id="message">' . $GLOBALS['cpac_message'] . '</div>';
	}
}