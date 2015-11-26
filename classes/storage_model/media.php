<?php

class CPAC_Storage_Model_Media extends CPAC_Storage_Model {

	public function __construct() {

		$this->key            = 'wp-media';
		$this->label          = __( 'Media Library' );
		$this->singular_label = __( 'Media' );
		$this->type           = 'media';
		$this->meta_type      = 'post';
		$this->page           = 'upload';
		$this->post_type      = 'attachment';
		$this->menu_type      = 'other';

		// Increased the priority to overrule 3th party plugins such as Media Tags
		add_filter( "manage_{$this->page}_columns", array( $this, 'add_headings' ), 100 );
		add_action( 'manage_media_custom_column', array( $this, 'manage_value' ), 100, 2 );

		parent::__construct();
	}

	public function get_default_columns() {
		if ( ! function_exists( '_get_list_table' ) ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		$table   = _get_list_table( 'WP_Media_List_Table', array( 'screen' => 'upload' ) );
		$columns = (array) $table->get_columns();

		if ( $this->is_settings_page() ) {
			$columns = array_merge( get_column_headers( 'upload' ), $columns );
		}

		return $columns;
	}

	public function get_default_column_names() {
		return array( 'cb', 'date', 'parent', 'icon', 'title', 'author', 'comments' );
	}

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1", ARRAY_N );
	}

	public function manage_value( $column_name, $media_id ) {
		if ( ! ( $column = $this->get_column_by_name( $column_name ) ) ) {
			return false;
		}
		$value = $column->get_value( $media_id );

		// hooks
		$value = apply_filters( "cac/column/value", $value, $media_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $media_id, $column, $this->key );

		echo $value;
	}
}