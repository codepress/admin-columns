<?php

class CPAC_Columns_Posttype extends CPAC_Columns {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct( $post_type ) {
		$this->storage_key = $post_type;
	}

	/**
	 * Custom posts columns
	 *
	 * @see CPAC_Columns::get_custom_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_custom_columns() {
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
		if ( $this->storage_key == 'post' ) {
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
		if ( $this->storage_key == 'page' ) {
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
				if ( $tax_slug != 'post_tag' && $tax_slug != 'category' && $tax_slug != 'post_format' ) {
					$custom_columns['column-taxonomy-'.$tax->name] = array(
						'label'			=> $tax->label,
						'show_filter'	=> true,
						'options'		=> array(
							'type_label'	=> __( 'Taxonomy', CPAC_TEXTDOMAIN )
						)
					);
				}
			}
		}

		// Custom Field support
		if ( $this->get_meta_keys() ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __( 'Custom Field', CPAC_TEXTDOMAIN ),
				'field'			=> '',
				'field_type'	=> '',
				'image_size'	=> '',
				'before'		=> '',
				'after'			=> '',
				'options'		=> array(
					'type_label'	=> __( 'Custom Field', CPAC_TEXTDOMAIN ),
					'class'			=> 'cpac-box-metafield'
				)
			);
		}

		// merge with defaults
		$custom_columns = $this->parse_defaults( $custom_columns );

		return apply_filters( 'cpac-custom-posts-columns', $custom_columns );
	}

	/**
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Columns::get_default_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_default_columns() {
		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( 'cpac-get-default-columns-posts', $this->storage_key );

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

		// add sorting to some of the default links columns

		//	categories
		if ( ! empty( $columns['categories'] ) ) {
			$columns['categories']['options']['enable_sorting'] = true;
		}
		// tags
		if ( ! empty( $columns['tags'] ) ) {
			$columns['tags']['options']['enable_sorting'] = true;
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

		return apply_filters( 'cpac-get-meta-keys-posts', $this->maybe_add_hidden_meta( $fields ), $this->storage_key );
    }

	/**
	 * Get Label
	 *
	 * @see CPAC_Columns::get_label()
	 * @since 1.5.0
	 *
	 * @return string
	 */
	function get_label() {
		$posttype_obj = get_post_type_object( $this->storage_key );

		return $posttype_obj->labels->singular_name;
	}
}