<?php

/**
 * WPML compatibility
 */
class AC_ThirdParty_WPML {

	function __construct() {

		// display correct flags on the overview screens
		add_action( 'cac/loaded', array( $this, 'replace_flags' ) );

		// enable the translation of the column labels
		add_action( 'wp_loaded', array( $this, 'register_column_labels' ), 99 );

		// enable the WPML translation of column headings
		add_filter( 'cac/headings/label', array( $this, 'register_translated_label' ), 10, 4 );
	}

	public function replace_flags() {
		if ( ! class_exists( 'SitePress', false ) ) {
			return;
		}
		if ( ! AC()->list_screen_manager()->get_list_screen() ) {
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
				new AC_ThirdParty_WPMLColumn( $post_type );
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
			foreach ( $list_screen->settings()->get_columns() as $column_name => $options ) {
				icl_register_string( 'Admin Columns', $list_screen->get_key() . '_' . $column_name, stripslashes( $options['label'] ) );
			}
		}
	}

	/**
	 * @param string $label
	 * @param string $column_name
	 * @param array $column_options
	 * @param AC_ListScreen $list_screen
	 *
	 * @return string
	 */
	public function register_translated_label( $label, $column_name, $column_options, $list_screen ) {
		if ( function_exists( 'icl_t' ) ) {
			$name = $list_screen->get_key() . '_' . $column_name;
			$label = icl_t( 'Admin Columns', $name, $label );
		}

		return $label;
	}

}