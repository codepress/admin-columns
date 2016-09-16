<?php
defined( 'ABSPATH' ) or die();

abstract class AC_StorageModel_PostAbstract extends AC_StorageModel {

	/**
	 * @since 2.0
	 */
	public function init() {
		$this->meta_type = 'post';
	}

	/**
	 * @since NEWVERSION
	 */
	public function init_column_values() {
		add_action( "manage_" . $this->get_post_type() . "_posts_custom_column", array( $this, 'manage_value' ), 100, 2 );
	}

	/**
	 * @since 2.4.7
	 */
	public function get_posts( $args = array() ) {
		$defaults = array(
			'posts_per_page' => -1,
			'post_status'    => apply_filters( 'cac/get_posts/post_status', array( 'any', 'trash' ), $this ),
			'post_type'      => $this->get_post_type(),
			'fields'         => 'ids',
			'no_found_rows'  => 1,
		);

		return (array) get_posts( array_merge( $defaults, $args ) );
	}

	/**
	 * @since 2.0
	 */
	protected function get_screen_link() {
		return add_query_arg( array( 'post_type' => $this->get_post_type() ), admin_url( $this->page . '.php' ) );
	}

	/**
	 * @since 2.2
	 */
	public function is_current_screen() {
		$post_type = isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'post';

		return ( $this->get_post_type() === $post_type ) && parent::is_current_screen();
	}

	/**
	 * @since NEWVERSION
	 * @return WP_Post Post object
	 */
	protected function get_object_by_id( $id ) {
		return get_post( $id );
	}

	/**
	 * @since 2.0
	 */
	public function get_meta() {
		global $wpdb;

		return $wpdb->get_results( $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $this->get_post_type() ), ARRAY_N );
	}

	/**
	 * @since 2.4.7
	 */
	public function manage_value( $column_name, $id ) {
		echo $this->get_display_value_by_column_name( $column_name, $id );
	}

}