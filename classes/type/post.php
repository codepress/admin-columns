<?php

class CPAC_Type_Post extends CPAC_Type {

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct( $post_type ) {

		$this->storage_key 	= $post_type;
		
		$this->meta_type	= 'post';
		
		$this->label 		= $this->get_label();
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
	 * Get WP default supported admin columns per post type.
	 *
	 * @see CPAC_Type::get_default_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_default_columns() {
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
		return $columns;
	}
}