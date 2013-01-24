<?php

class CPAC_Columns_Comments extends CPAC_Columns {

	/**
	 * Constructor
	 *
	 * @since 1.3.1
	 */
	public function __construct() {

		$this->storage_key 	= 'wp-comments';
		$this->label 		= __( 'Comments' );
    }

	/**
	 * Get WP default links columns.
	 *
	 * @see CPAC_Columns::get_default_columns()
	 * @since  1.3.1
	 *
	 * @return array
	 */
	function get_default() {
		// You can use this filter to add third_party columns by hooking into this.
		do_action( "cpac_get_default_columns_{$this->storage_key}" );

		// dependencies
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
		if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' ) )
			require_once( ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php' );

		// As of WP Release 3.5 we can use the following.
		if ( version_compare( get_bloginfo( 'version' ), '3.4.10', '>=' ) ) {

			$table 		= new WP_Comments_List_Table( array( 'screen' => 'edit-comments' ) );
			$columns 	= $table->get_columns();
		}

		// WP versions older then 3.5
		// @todo: make this deprecated
		else {

			global $current_screen;

			// save original
			$org_current_screen = $current_screen;

			// prevent php warning
			if ( !isset( $current_screen ) ) {
				$current_screen = new stdClass;
			}

			// overwrite current_screen global with our media id...
			$current_screen->id = 'edit-comments';

			// init table object
			$wp_comment = new WP_Comments_List_Table;

			// get comments
			$columns = $wp_comment->get_columns();

			// reset current screen
			$current_screen = $org_current_screen;
		}

		// change to uniform format
		$columns = $this->get_uniform_format( $columns );

		// add sorting to some of the default links columns
		if ( !empty($columns['comment']) ) {
			$columns['comment']['options']['enable_sorting'] = true;
		}

		return $columns;
	}

	/**
	 * Custom comments columns
	 *
	 * @see CPAC_Columns::get_custom_columns()
	 * @since 1.3.1
	 *
	 * @return array
	 */
	function get_custom() {
		$custom_columns = array(
			'column-comment_id' => array(
				'label'	=> __( 'ID', CPAC_TEXTDOMAIN )
			),
			'column-author_author' => array(
				'label'	=> __( 'Author Name', CPAC_TEXTDOMAIN )
			),
			'column-author_avatar' => array(
				'label'	=> __( 'Avatar', CPAC_TEXTDOMAIN )
			),
			'column-author_url' => array(
				'label'	=> __( 'Author url', CPAC_TEXTDOMAIN )
			),
			'column-author_ip' => array(
				'label'	=> __( 'Author IP', CPAC_TEXTDOMAIN )
			),
			'column-author_email' => array(
				'label'	=> __( 'Author email', CPAC_TEXTDOMAIN )
			),
			'column-reply_to' => array(
				'label'			=> __( 'In Reply To', CPAC_TEXTDOMAIN ),
				'options'		=> array(
					'enable_sorting' => false
				)
			),
			'column-approved' => array(
				'label'	=> __( 'Approved', CPAC_TEXTDOMAIN )
			),
			'column-date' => array(
				'label'	=> __( 'Date', CPAC_TEXTDOMAIN )
			),
			'column-date_gmt' => array(
				'label'	=> __( 'Date GMT', CPAC_TEXTDOMAIN )
			),
			'column-agent' => array(
				'label'	=> __( 'Agent', CPAC_TEXTDOMAIN )
			),
			'column-excerpt' => array(
				'label'	=> __( 'Excerpt', CPAC_TEXTDOMAIN )
			),
			'column-actions' => array(
				'label'	=> __( 'Actions', CPAC_TEXTDOMAIN ),
				'options'	=> array(
					'enable_sorting'	=> false
				)
			),
			'column-word-count' => array(
				'label'	=> __( 'Word count', CPAC_TEXTDOMAIN ),
				'options'	=> array(
					'enable_sorting'	=> false
				)
			)
		);

		return $custom_columns;
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

		return apply_filters( "cpac_get_meta_keys_{$this->storage_key}", $this->maybe_add_hidden_meta( $fields ) );
    }
}