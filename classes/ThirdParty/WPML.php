<?php

namespace AC\ThirdParty;

use AC\Column;

/**
 * WPML compatibility
 */
class WPML {

	function __construct() {

		// display correct flags on the overview screens
		add_action( 'ac/table/list_screen', array( $this, 'replace_flags' ) );

		// enable the translation of the column labels
		add_action( 'wp_loaded', array( $this, 'register_column_labels' ), 99 );

		// enable the WPML translation of column headings
		add_filter( 'ac/headings/label', array( $this, 'register_translated_label' ), 100 );
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

		foreach ( AC()->get_list_screens() as $list_screen ) {
			foreach ( $list_screen->get_settings() as $column_name => $options ) {
				do_action( 'wpml_register_single_string', 'Admin Columns', $options['label'], $options['label'] );
			}
		}
	}

	/**
	 * @param string $label
	 * @param Column $column
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