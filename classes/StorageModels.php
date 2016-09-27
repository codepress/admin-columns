<?php
defined( 'ABSPATH' ) or die();

final class AC_StorageModels {

	private $storage_models;

	/**
	 * @param AC_StorageModel $storage_model
	 */
	public function add_storage_model( AC_StorageModel $storage_model ) {
		$this->storage_models[] = $storage_model;
	}

	/**
	 * Get registered storage models
	 *
	 * @since 2.5
	 * @return AC_StorageModel[]
	 */
	public function get_storage_models() {
		if ( null === $this->storage_models ) {
			$this->set_storage_models();
		}

		return $this->storage_models;
	}

	/**
	 * @param AC_StorageModel $storage_model
	 */
	public function register_storage_model( AC_StorageModel $storage_model ) {
		$this->storage_models[ $storage_model->get_key() ] = $storage_model;
	}

	/**
	 * Retrieve a storage model object based on its key
	 *
	 * @since 2.0
	 *
	 * @param string $key Storage model key (e.g. post, page, wp-users)
	 *
	 * @return bool|AC_StorageModel Storage Model object (or false, on failure)
	 */
	public function get_storage_model( $key ) {
		$models = $this->get_storage_models();

		return isset( $models[ $key ] ) ? $models[ $key ] : false;
	}

	/**
	 * Get registered storage models
	 *
	 * @since NEWVERSION
	 */
	private function set_storage_models() {

		$classes_dir = AC()->get_plugin_dir() . 'classes/';

		require_once $classes_dir . 'Column.php';

		// Backwards compatibility
		require_once $classes_dir . 'Deprecated/column-default.php';

		// @deprecated NEWVERSION
		require_once $classes_dir . 'Deprecated/storage_model.php';

		// Create a storage model per post type
		foreach ( $this->get_post_types() as $post_type ) {
			$storage_model = new AC_StorageModel_Post();
			$this->register_storage_model( $storage_model->set_post_type( $post_type ) );
		}

		// Create other storage models
		$this->register_storage_model( new AC_StorageModel_User() );
		$this->register_storage_model( new AC_StorageModel_Media() );
		$this->register_storage_model( new AC_StorageModel_Comment() );

		if ( apply_filters( 'pre_option_link_manager_enabled', false ) ) { // as of 3.5 link manager is removed
			$this->register_storage_model( new AC_StorageModel_Link() );
		}

		// @deprecated NEWVERSION
		$this->storage_models = apply_filters( 'cac/storage_models', $this->storage_models, AC() );

		do_action( 'ac/storage_models', $this );
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 *
	 * @since 1.0
	 *
	 * @return array List of post type keys (e.g. post, page)
	 */
	private function get_post_types() {
		$post_types = array();

		if ( post_type_exists( 'post' ) ) {
			$post_types['post'] = 'post';
		}
		if ( post_type_exists( 'page' ) ) {
			$post_types['page'] = 'page';
		}

		$post_types = array_merge( $post_types, get_post_types( array(
			'_builtin' => false,
			'show_ui'  => true,
		) ) );

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @since 2.0
		 *
		 * @param array $post_types List of active post type names
		 */
		return apply_filters( 'cac/post_types', $post_types );
	}

}