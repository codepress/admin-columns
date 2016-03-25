<?php

/**
 * WPML: display correct flags on the overview screens
 */
class CPAC_WPML_COLUMN {

	CONST COLUMN_NAME = 'icl_translations';

	private $column;

	function __construct( $post_type ) {
		add_filter( "manage_{$post_type}_posts_columns", array( $this, 'store_wpml_column' ), 11 ); // prio just after WPML set's it's column
		add_filter( "manage_edit-{$post_type}_columns", array( $this, 'replace_wpml_column' ), 201 ); // prio just after AC overwrite all columns
	}

	public function store_wpml_column( $columns ) {
		if ( empty( $this->column ) && isset( $columns[ self::COLUMN_NAME ] ) ) {
			$this->column = $columns[ self::COLUMN_NAME ];
		}

		return $columns;
	}

	public function replace_wpml_column( $columns ) {
		if ( $this->column && isset( $columns[ self::COLUMN_NAME ] ) ) {
			$columns[ self::COLUMN_NAME ] = $this->column;
		}

		return $columns;
	}
}

/**
 * WPML compatibility
 */
class CPAC_WPML {

	function __construct() {

		// display correct flags on the overview screens
		add_action( 'cac/loaded', array( $this, 'replace_flags' ) );

		// enable the translation of the column labels
		add_action( 'wp_loaded', array( $this, 'register_column_labels' ), 99 );

		// enable the WPML translation of column headings
		add_filter( 'cac/headings/label', array( $this, 'register_translated_label' ), 10, 4 );

		// set WPML to be a columns screen for translation so that storage models are loaded
		add_filter( 'cac/is_cac_screen', array( $this, 'is_cac_screen' ) );
	}

	public function replace_flags( $cac ) {
		if ( ! class_exists( 'SitePress', false ) ) {
			return;
		}
		if ( ! $cac->is_columns_screen() ) {
			return;
		}

		$settings = get_option( 'icl_sitepress_settings' );
		if ( ! isset( $settings['custom_posts_sync_option'] ) ) {
			return;
		}
		$post_types = (array) $settings['custom_posts_sync_option'];
		$post_types['post'] = 1;
		$post_types['page'] = 1;
		foreach ( $post_types as $post_type => $value ) {
			if ( $value ) {
				new CPAC_WPML_COLUMN( $post_type );
			}
		}
	}

	// Create translatable column labels
	public function register_column_labels() {

		// don't load this unless required by WPML
		if ( ! isset( $_GET['page'] ) || 'wpml-string-translation/menu/string-translation.php' !== $_GET['page'] ) {
			return;
		}

		foreach ( cpac()->get_storage_models() as $storage_model ) {
			foreach ( $storage_model->get_stored_columns() as $column_name => $options ) {
				icl_register_string( 'Admin Columns', $storage_model->key . '_' . $column_name, stripslashes( $options['label'] ) );
			}
		}
	}

	public function register_translated_label( $label, $column_name, $column_options, $storage_model ) {
		if ( function_exists( 'icl_t' ) ) {
			$name = $storage_model->key . '_' . $column_name;
			$label = icl_t( 'Admin Columns', $name, $label );
		}

		return $label;
	}

	public function is_cac_screen( $is_columns_screen ) {
		if ( isset( $_GET['page'] ) && 'wpml-string-translation/menu/string-translation.php' == $_GET['page'] ) {
			return true;
		}

		return $is_columns_screen;
	}
}

new CPAC_WPML;