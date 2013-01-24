<?php

class CPAC_Columns_Posttype extends CPAC_Columns {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct( $post_type ) {

		$this->storage_key 	= $post_type;
		$this->label 		= $this->get_label();
	}

	/**
	 * Custom posts columns
	 *
	 * @see CPAC_Columns::get_custom()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_custom() {
		
		$custom_columns = array(
			'column-featured_image' => array(
				'label'	     => __( 'Featured Image', CPAC_TEXTDOMAIN ),
				'options'	 => array(
					'is_image'	=> true
				)
			),
			'column-excerpt' => array(
				'label'	=> __( 'Excerpt', CPAC_TEXTDOMAIN )
			),
			'column-order' => array(
				'label'	=> __( 'Page Order', CPAC_TEXTDOMAIN )
			),
			'column-post_formats' => array(
				'label'	=> __( 'Post Format', CPAC_TEXTDOMAIN )
			),
			'column-postid' => array(
				'label'	=> __( 'ID', CPAC_TEXTDOMAIN )
			),
			'column-page-slug' => array(
				'label'	=> __( 'Slug', CPAC_TEXTDOMAIN )
			),
			'column-attachment' => array(
				'label'	=> __( 'Attachment', CPAC_TEXTDOMAIN )
			),
			'column-attachment-count' => array(
				'label'	=> __( 'No. of Attachments', CPAC_TEXTDOMAIN )
			),
			'column-roles' => array(
				'label'	=> __( 'Roles', CPAC_TEXTDOMAIN )
			),
			'column-status' => array(
				'label'	=> __( 'Status', CPAC_TEXTDOMAIN )
			),
			'column-comment-status' => array(
				'label'	=> __( 'Comment status', CPAC_TEXTDOMAIN )
			),
			'column-ping-status' => array(
				'label'	=> __( 'Ping status', CPAC_TEXTDOMAIN )
			),
			'column-actions' => array(
				'label'	=> __( 'Actions', CPAC_TEXTDOMAIN ),
				'options'	=> array(
					'enable_sorting' => false
				)
			),
			'column-modified' => array(
				'label'	=> __( 'Last modified', CPAC_TEXTDOMAIN )
			),
			'column-comment-count' => array(
				'label'	=> __( 'Comment count', CPAC_TEXTDOMAIN )
			),
			'column-author-name' => array(
				'label'			=> __( 'Display Author As', CPAC_TEXTDOMAIN ),
				'display_as'	=> ''
			),
			'column-before-moretag' => array(
				'label'	=> __( 'Before More Tag', CPAC_TEXTDOMAIN )
			)
		);

		// Word count support
		if ( post_type_supports( $this->storage_key, 'editor' ) ) {
			$custom_columns['column-word-count'] = array(
				'label'	=> __( 'Word count', CPAC_TEXTDOMAIN )
			);
		}

		// Sticky support
		if ( 'post' == $this->storage_key ) {
			$custom_columns['column-sticky'] = array(
				'label'			=> __( 'Sticky', CPAC_TEXTDOMAIN )
			);
		}

		// Order support
		if ( post_type_supports( $this->storage_key, 'page-attributes' ) ) {
			$custom_columns['column-order'] = array(
				'label'			=> __( 'Page Order', CPAC_TEXTDOMAIN ),
				'options'		=> array(
					'type_label' 	=> __( 'Order', CPAC_TEXTDOMAIN )
				)
			);
		}

		// Page Template
		if ( 'page' == $this->storage_key ) {
			$custom_columns['column-page-template'] = array(
				'label'	=> __( 'Page Template', CPAC_TEXTDOMAIN )
			);
		}

		// Post Formats
		if ( post_type_supports( $this->storage_key, 'post-formats' ) ) {
			$custom_columns['column-post_formats'] = array(
				'label'	=> __( 'Post Format', CPAC_TEXTDOMAIN )
			);
		}

		// Taxonomy support
		$taxonomies = get_object_taxonomies( $this->storage_key, 'objects' );
		if ( $taxonomies ) {
			foreach ( $taxonomies as $tax_slug => $tax ) {

				// Since WP 3.5 user can define whether to allow automatic creation of taxonomy columns.
				// When this is the case, we can assume the column has already been added by WP.
				if ( isset( $tax->show_admin_column ) && $tax->show_admin_column )
					continue;

				// skip core taxonomies
				if ( in_array( $tax_slug, array('post_tag', 'category', 'post_format') ) )
					continue;

				$custom_columns['column-taxonomy-'.$tax->name] = array(
					'label'			=> $tax->label,
					'options'		=> array(
						'type_label'		=> __( 'Taxonomy', CPAC_TEXTDOMAIN ) . ': ' . $tax->label,
						'enable_filtering'	=> true, // adds a dropdown filter
						'is_dynamic'		=> true // can have multiple instances
					)
				);
			}
		}

		// Custom Field support
		if ( $this->get_meta_keys() ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __( 'Custom Field', CPAC_TEXTDOMAIN ),
				'options'		=> array(
					'type_label'		=> __( 'Custom Field', CPAC_TEXTDOMAIN ),
					'class'				=> 'cpac-box-metafield',
					'enable_filtering'	=> true, // adds a dropdown filter,
					'is_dynamic'		=> true // can have multiple instances
				)
			);
		}

		return $custom_columns;
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Columns::get_default_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_default() {
		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cpac_before_default_columns_posts" );
		do_action( "cpac_before_default_columns_{$this->storage_key}" );

		// some plugins directly hook into get_column_headers, such as: WooCommerce.
		$columns = get_column_headers( 'edit-' . $this->storage_key );

		// get default columns
		if ( empty( $columns ) ) {

			// deprecated as of wp3.3
			if ( file_exists( ABSPATH . 'wp-admin/includes/template.php' ) )
				require_once( ABSPATH . 'wp-admin/includes/template.php' );

			// introduced since wp3.3
			if ( file_exists( ABSPATH . 'wp-admin/includes/screen.php' ) )
				require_once( ABSPATH . 'wp-admin/includes/screen.php' );

			// used for getting columns
			if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) )
				require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
			if ( file_exists( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' ) )
				require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );

			// As of WP Release 3.5 we can use the following.
			if ( version_compare( get_bloginfo( 'version' ), '3.4.10', '>=' ) ) {

				$table 		= new WP_Posts_List_Table( array( 'screen' => $this->storage_key ) );
				$columns 	= $table->get_columns();
			}

			// WP versions older then 3.5
			// @todo: make this deprecated
			else {

				// we need to change the current screen... first lets save original
				$org_current_screen = $current_screen;

				// prevent php warning
				if ( ! isset( $current_screen ) ) {
					$current_screen = new stdClass;
				}

				// overwrite current_screen global with our post type of choose...
				$current_screen->post_type = $this->storage_key;

				// ...so we can get its columns
				$columns = WP_Posts_List_Table::get_columns();

				// reset current screen
				$current_screen = $org_current_screen;
			}

		}

		if ( empty ( $columns ) )
			return false;

		// change to uniform format
		$columns = $this->get_uniform_format( $columns );

		// add sorting to some of the default columns
		foreach ( $columns as $column_name => $column ) {

			// categories, tags
			if ( 'categories' == $column_name || 'tags' == $column_name ){
				$columns[$column_name]['options']['enable_sorting'] = true;
			}

			// custom taxonomies
			if ( CPAC_Utility::is_column_taxonomy( $column_name ) ) {
				$columns[$column_name]['options']['enable_sorting'] = true;
				$columns[$column_name]['options']['enable_filtering'] = true;
			}
		}

		return $columns;
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

		$fields = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->storage_key ), ARRAY_N );

		if ( is_wp_error( $fields ) )
			$fields = false;

		return apply_filters( "cpac_get_meta_keys_{$this->storage_key}", $this->maybe_add_hidden_meta( $fields ), $this->storage_key );
    }

	/**
	 * Get Label
	 *
	 * @since 1.5.0
	 *
	 * @return string Singular posttype name
	 */
	function get_label() {
		$posttype_obj = get_post_type_object( $this->storage_key );

		return $posttype_obj->labels->singular_name;
	}

	/**
     * Get Meta Values
     *
	 * @since 2.0.0
	 *
	 * @param Post Type
	 * @return array
     */
    public function get_meta_values( $meta_key = '' ) {
        if ( ! $meta_key )
			return false;

		global $wpdb;

		$values = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_value FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s AND pm.meta_key = %s AND pm.meta_value != '' ORDER BY 1", $this->storage_key, $meta_key ), ARRAY_N );

		if ( is_wp_error( $values ) )
			$values = false;

		return $values;
    }
}