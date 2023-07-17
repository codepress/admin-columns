<?php

namespace AC\ThirdParty;

use AC\ListScreenRepository\Storage;
use AC\Registerable;

/**
 * WPML compatibility
 */
class WPML implements Registerable {

	/**
	 * @var Storage
	 */
	private $storage;

	/**
	 * @param Storage $storage
	 */
	public function __construct( Storage $storage ) {
		$this->storage = $storage;
	}

	function register(): void
    {

		// display correct flags on the overview screens
		add_action( 'ac/table/list_screen', [ $this, 'replace_flags' ] );

		// enable the translation of the column labels
		add_action( 'ac/ready', [ $this, 'register_column_labels' ], 300 );

		// enable the WPML translation of column headings
		add_filter( 'ac/headings/label', [ $this, 'register_translated_label' ], 100 );
	}

	public function replace_flags() {
		if ( ! class_exists( 'SitePress', false ) ) {
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
				new WPMLColumn( $post_type );
			}
		}
	}

	// Create translatable column labels
	public function register_column_labels() {
		// don't load this unless required by WPML
		if ( ! isset( $_GET['page'] ) || 'wpml-string-translation/menu/string-translation.php' !== $_GET['page'] ) {
			return;
		}

		foreach ( $this->storage->find_all() as $list_screen ) {
			foreach ( $list_screen->get_columns() as $column ) {
				do_action( 'wpml_register_single_string', 'Admin Columns', $column->get_custom_label(), $column->get_custom_label() );
			}
		}

	}

	/**
	 * @param string $label
	 *
	 * @return string
	 */
	public function register_translated_label( $label ) {
		if ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			$label = apply_filters( 'wpml_translate_single_string', $label, 'Admin Columns', $label, ICL_LANGUAGE_CODE );
		}

		return $label;
	}

}