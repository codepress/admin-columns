<?php
defined( 'ABSPATH' ) or die();

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

	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}
}