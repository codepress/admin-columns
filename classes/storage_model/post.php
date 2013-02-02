<?php

class CPAC_Storage_Model_Post extends CPAC_Storage_Model {

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct( $post_type ) {		
		
		$this->key 		= $post_type;		
		$this->label 	= $this->get_label();
		
		add_filter( "manage_edit-{$post_type}_columns",  array( $this, 'add_headings' ) );
		
		parent::__construct();
	}
	
	/**
	 * Get Label
	 *
	 * @since 1.5.0
	 *
	 * @return string Singular posttype name
	 */
	function get_label() {
		$posttype_obj = get_post_type_object( $this->key );

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
		do_action( "cpac_before_default_columns_{$this->key}" );

		// some plugins directly hook into get_column_headers, such as: WooCommerce.
		$columns = get_column_headers( 'edit-' . $this->key );

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

				$table 		= new WP_Posts_List_Table( array( 'screen' => $this->key ) );
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
				$current_screen->post_type = $this->key;

				// ...so we can get its columns
				$columns = WP_Posts_List_Table::get_columns();

				// reset current screen
				$current_screen = $org_current_screen;
			}

		}

		if ( empty( $columns ) )
			return false;
		
		return $columns;
	}
	
	/**
	 * Get custom columns.
	 *
	 * @see CPAC_Storage_Model::get_custom_columns()
	 * @since 1.0.0
	 *
	 * @return array
	 */
	function get_custom_columns() {
		
		return array(
			'CPAC_Column_Post_Custom_Field' 	=> CPAC_DIR . '/classes/column/post/custom-field.php',
			'CPAC_Column_Post_Excerpt'			=> CPAC_DIR . '/classes/column/post/excerpt.php',
			'CPAC_Column_Post_Featured_Image'	=> CPAC_DIR . '/classes/column/post/featured-image.php',
		);		
	}
	
	/**
	 * Maybe add hidden meta
	 *
	 * @since 1.5
	 *
	 * @param array $fields Custom fields.
	 * @return array Custom fields.
	 */
	private function maybe_add_hidden_meta( $fields ) {
		if ( ! $fields )
			return false;

		$combined_fields = array();

		$use_hidden_meta = apply_filters( 'cpac_use_hidden_custom_fields', false );

		// filter out hidden meta fields
		foreach ( $fields as $field ) {

			// give hidden fields a prefix for identifaction
			if ( $use_hidden_meta && substr( $field[0], 0, 1 ) == "_") {
				$combined_fields[] = 'cpachidden'.$field[0];
			}

			// non hidden fields are saved as is
			elseif ( substr( $field[0], 0, 1 ) != "_" ) {
				$combined_fields[] = $field[0];
			}
		}

		if ( empty( $combined_fields ) )
			return false;

		return $combined_fields;
	}
	
	/**
     * Get Meta Keys
     *
	 * @since 1.5.0
	 *
	 * @return array
     */
    public function get_meta_keys() {
        global $wpdb;

		$fields = $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->key ), ARRAY_N );

		if ( is_wp_error( $fields ) )
			$fields = false;

		return apply_filters( "cpac_get_meta_keys_{$this->key}", $this->maybe_add_hidden_meta( $fields ), $this->key );
    }
	
	/**
	 * Returns excerpt
	 *
	 * @todo: excerpt length
	 * @since 1.0.0
	 *
	 * @param int $post_id Post ID
	 * @return string Post Excerpt.
	 */
	protected function get_post_excerpt( $post_id )	{
		global $post;

		$save_post 	= $post;
		$post 		= get_post( $post_id );
		$excerpt 	= get_the_excerpt();
		$post 		= $save_post;

		$output = $this->get_shortened_string( $excerpt, 20 );

		return $output;
	}
}