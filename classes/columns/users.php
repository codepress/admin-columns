<?php

class CPAC_Columns_Users extends CPAC_Columns
{
	/**
	 * Constructor
	 *
	 * @since 1.1.0
	 */
	public function __construct() {
		$this->storage_key = 'wp-users';
    }

	/**
	 * Get WP default users columns per post type.
	 *
	 * @see CPAC_Columns::get_default_columns()
	 * @since 1.1.0
	 *
	 * @return array
	 */
	function get_default_columns() {
		// You can use this filter to add third_party columns by hooking into this.
		do_action( 'cpac-get-default-columns-users' );

		// Dependencies
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php' );

		// turn off site users
		$this->is_site_users = false;

		// get users columns
		$cols = WP_Users_List_Table::get_columns();

		// change to uniform format
		$columns = $this->get_uniform_format( $cols );

		return apply_filters( 'cpac-default-users-columns', $columns );
	}

	/**
	 * Custom users columns
	 *
	 * @see CPAC_Columns::get_custom_columns()
	 * @since 1.1.0
	 *
	 * @return array
	 */
	function get_custom_columns() {
		$custom_columns = array(
			'column-user_id' => array(
				'label'	=> __( 'User ID', CPAC_TEXTDOMAIN )
			),
			'column-nickname' => array(
				'label'	=> __( 'Nickname', CPAC_TEXTDOMAIN )
			),
			'column-first_name' => array(
				'label'	=> __( 'First name', CPAC_TEXTDOMAIN )
			),
			'column-last_name' => array(
				'label'	=> __( 'Last name', CPAC_TEXTDOMAIN )
			),
			'column-user_url' => array(
				'label'	=> __( 'Url', CPAC_TEXTDOMAIN )
			),
			'column-user_registered' => array(
				'label'	=> __( 'Registered', CPAC_TEXTDOMAIN )
			),
			'column-user_description' => array(
				'label'	=> __( 'Description', CPAC_TEXTDOMAIN )
			),
			'column-actions' => array(
				'label'	=> __( 'Actions', CPAC_TEXTDOMAIN ),
				'options'	=> array(
					'enable_sorting' => false
				)
			),
			'column-user_commentcount' => array(
				'label'		=> __( 'Comment Count', CPAC_TEXTDOMAIN )
			),
		);

		// User total number of posts
		foreach ( CPAC_Utility::get_post_types() as $post_type ) {
			$label = '';

			// get plural label
			$posttype_obj = get_post_type_object( $post_type );
			if ( $posttype_obj ) {
				$label = $posttype_obj->labels->name;
			}

			$custom_columns['column-user_postcount-'.$post_type] = array(
				'label'			=> __( sprintf( 'No. of %s', $label ), CPAC_TEXTDOMAIN ),
				'options'		=> array(
					'type_label'	=> __( 'Postcount', CPAC_TEXTDOMAIN )
				)
			);
		}

		// Custom Field support
		$custom_columns['column-meta-1'] = array(
			'label'			=> __( 'Custom Field', CPAC_TEXTDOMAIN ),
			'field'			=> '',
			'field_type'	=> '',
			'before'		=> '',
			'after'			=> '',
			'options'		=> array(
				'type_label'	=> __( 'Custom Field', CPAC_TEXTDOMAIN ),
				'class'			=> 'cpac-box-metafield'
			)
		);

		// merge with defaults
		$custom_columns = $this->parse_defaults( $custom_columns );

		return apply_filters( 'cpac-custom-users-columns', $custom_columns );
	}

	/**
     * Get Meta Keys
     *
	 * @see CPAC_Columns::get_meta_keys()
	 * @since 1.5.0
	 *
	 * @return array
     */
    public function get_meta_keys() {
        global $wpdb;

		$fields = $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1", ARRAY_N );

		if ( is_wp_error( $fields ) )
			$fields = false;

		return apply_filters( 'cpac-get-meta-keys-users', $this->maybe_add_hidden_meta( $fields ) );
    }

	/**
	 * Get label
	 *
	 * @see CPAC_Columns::get_label()
	 * @since 1.5
	 *
	 * @return string
	 */
	public function get_label() {
		return __( 'Users');
	}
}