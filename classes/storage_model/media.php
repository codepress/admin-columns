<?php

class CPAC_Storage_Model_Media extends CPAC_Storage_Model {

	public function __construct() {

		$this->key = 'wp-media';
		$this->label = __( 'Media Library' );
		$this->singular_label = __( 'Media' );
		$this->type = 'media';
		$this->meta_type = 'post';
		$this->page = 'upload';
		$this->post_type = 'attachment';
		$this->table_classname = 'WP_Media_List_Table';

		parent::__construct();
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_values() {
		add_action( 'manage_media_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @since 2.5
	 */
	public function get_default_column_names() {
		return array(
			'cb',
			'date',
			'parent',
			'icon',
			'title',
			'author',
			'comments'
		);
	}

	/**
	 * @since 2.5
	 */
	protected function get_default_column_widths() {
		return array(
			'author' => array( 'width' => 10 ),
			'parent' => array( 'width' => 15 ),
			'date'   => array( 'width' => 10 ),
		);
	}

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1", ARRAY_N );
	}

	public function manage_value( $column_name, $media_id ) {
		if ( ! ( $column = $this->get_column_by_name( $column_name ) ) ) {
			return false;
		}
		$value = $column->get_display_value( $media_id );

		// hooks
		$value = apply_filters( "cac/column/value", $value, $media_id, $column, $this->key );
		$value = apply_filters( "cac/column/value/{$this->type}", $value, $media_id, $column, $this->key );

		echo $value;
	}
}