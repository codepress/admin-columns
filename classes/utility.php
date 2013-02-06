<?php
class CPAC_Utility {
	
	/**
	 * Get post types
	 *
	 * @since 1.0.0
	 *
	 * @return array Posttypes
	 */
	public static function get_post_types() {
		$post_types = get_post_types( array(
			'_builtin' => false
		));
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';

		return apply_filters( 'cpac_get_post_types', $post_types );
	}

	/**
	 * Sanitize label
	 *
	 * Uses intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since 1.0.0
	 *
	 * @param string $string
	 * @return string Sanitized string
	 */
	public static function sanitize_string( $string ) {
		$string = esc_url( $string );
		$string = str_replace( 'http://','', $string );
		$string = str_replace( 'https://','', $string );

		return $string;
	}
	
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
	 * @since 1.5.0
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
	 * @since 1.5.0
	 *
	 * @return string Message.
	 */
	public static function admin_notice() {
	    echo '<div class="' . $GLOBALS['cpac_message_type'] . '" id="message">' . $GLOBALS['cpac_message'] . '</div>';
	}
}