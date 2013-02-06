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
	 * Checks if column name is a csutom field
	 *
	 * Check for custom fields, such as column-meta-[customfieldname]
	 *
	 * @since 1.0.0
	 *
	 * @param string $column_name Column name
	 * @return bool
	 */
	public static function is_column_customfield( $column_name = '' ) {
		if ( strpos( $column_name, 'column-meta-' ) !== false )
			return true;

		return false;
	}

	/**
	 * Checks if column name is a taxonomy
	 *
	 * Check for taxonomies, such as column-taxonomy-[taxname]
	 *
	 * @since 2.0.0
	 *
	 * @param string $column_name Column name
	 * @return bool
	 */
	public static function is_column_taxonomy( $column_name = '' ) {
		if ( 0 === strpos( $column_name, 'column-taxonomy-' ) || 0 === strpos( $column_name, 'taxonomy-' ) )
			return true;

		return false;
	}

	/**
	 * Get column name type
	 *
	 * @since 2.0.0
	 *
	 * @param string $column_name Column name
	 * @return string Column Name Type
	 */
	public static function get_column_name_type( $column_name ) {

		if ( CPAC_Utility::is_column_taxonomy( $column_name ) ) {
			$column_name = 'column-taxonomy';
		}

		if ( CPAC_Utility::is_column_customfield( $column_name ) ) {
			$column_name = 'column-meta';
		}

		return $column_name;
	}

	/**
	 * Get the posttype from columnname
	 *
	 * Check for post count: column-user_postcount-[posttype]
	 *
	 * @since 1.3.1
	 *
	 * @param string $id
	 * @return string Posttype
	 */
	public static function get_posttype_by_postcount_column( $column_name = '' ) {
		if ( strpos( $column_name, 'column-user_postcount-' ) !== false ) {
			return str_replace( 'column-user_postcount-', '', $column_name );
		}

		return false;
	}

	/**
	 * Get the taxonomy from columnname
	 *
	 * Return the taxonomy: column-taxonomy-[taxonomy]
	 *
	 * @since 2.0.0
	 *
	 * @param string $id
	 * @return string Posttype
	 */
	public static function get_taxonomy_by_column_name( $column_name = '' ) {
		if ( ! CPAC_Utility::is_column_taxonomy( $column_name ) )
			return false;

		return str_replace( array( 'column-taxonomy-', 'taxonomy-' ), '', $column_name );
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
	 * Get post count
	 *
	 * @since 1.3.1
	 *
	 * @param string $post_type
	 * @param int $user_id
	 * @return int Postcount
	 */
	public static function get_post_count( $post_type, $user_id ) {
		global $wpdb;

		if ( ! post_type_exists( $post_type ) || empty( $user_id ) )
			return false;

		$sql = "
			SELECT COUNT(ID)
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			AND post_author = %d
			AND post_type = %s
		";

		return $wpdb->get_var( $wpdb->prepare( $sql, $user_id, $post_type ) );
	}

	/**
	 * Strip tags and trim
	 *
	 * @since     1.3
	 */
	public static function strip_trim($string)
	{
		return trim(strip_tags($string));
	}

	/**
	 * Get column value of post attachments
	 *
	 * @since 1.2.1
	 *
	 * @param int $post_id
	 * @return array Attachment ID's
	 */
	public static function get_attachment_ids( $post_id ) {
		return get_posts( array(
			'post_type' 	=> 'attachment',
			'numberposts' 	=> -1,
			'post_status' 	=> null,
			'post_parent' 	=> $post_id,
			'fields' 		=> 'ids'
		));
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