<?php
defined( 'ABSPATH' ) or die();

class AC_StorageModel_Media extends CPAC_Storage_Model {

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

	public function get_single_row( $id ) {

		// Author column depends on this global to be set.
		global $authordata;
		$authordata = get_userdata( get_post_field( 'post_author', $id ) );

		return parent::get_single_row( $id );
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Post Post object
	 */
	protected function get_object_by_id( $id ) {
		return get_post( $id );
	}

	/**
	 * @since 2.4.9
	 */
	public function init_column_values() {
		add_action( 'manage_media_custom_column', array( $this, 'manage_value' ), 100, 2 );
	}

	public function get_default_columns() {
		if ( ! function_exists( '_get_list_table' ) ) {
			return array();
		}

		// You can use this filter to add thirdparty columns by hooking into this.
		// See classes/third_party.php for an example.
		do_action( "cac/columns/default/storage_key={$this->key}" );

		$table = $this->get_list_table();
		$columns = (array) $table->get_columns();

		if ( cac_is_setting_screen() ) {
			$columns = array_merge( get_column_headers( 'upload' ), $columns );
		}

		return $columns;
	}

	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1", ARRAY_N );
	}

	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}