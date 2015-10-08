<?php

/**
 * Fix for getting the columns loaded by WordPress SEO Yoast
 *
 * The added columns from WordPress SEO by Yoast weren't available on
 * the admin columns settings page. The reason was that class-metabox.php was prevented
 * from loading. This fix will also load this class when admin columns is loaded.
 *
 * @since 1.4.6
 */
function cpac_pre_load_wordpress_seo_class_metabox() {

	if ( ! defined('WPSEO_PATH') || ! file_exists( WPSEO_PATH . 'admin/class-metabox.php' ) ) {
		return;
	}

	global $pagenow;

	// page is a CPAC page or CPAC ajax event
	if (
		( isset( $_GET['page'] ) && 'codepress-admin-columns' == $_GET['page'] && 'options-general.php' == $pagenow )
		||
		// for when column list is populated through ajax
		( defined('DOING_AJAX') && DOING_AJAX &&
			( ! empty( $_POST['type'] )
				||
				( ! empty( $_POST['plugin_id'] ) && 'cpac' === $_POST['plugin_id'] ) )
			)
		) {

		require_once WPSEO_PATH . 'admin/class-metabox.php';
		if ( class_exists( 'WPSEO_Metabox', false ) ) {
			new WPSEO_Metabox;
		}
	}

}
add_action( 'plugins_loaded', 'cpac_pre_load_wordpress_seo_class_metabox', 0 );

/**
 * WPML: display correct flags on the overview screens
 */
class CPAC_WPML_COLUMN {

	CONST COLUMN_NAME = 'icl_translations';

	private $column;

	function __construct( $post_type ) {
		add_filter( "manage_{$post_type}_posts_columns", array( $this, 'store_wpml_column' ), 11 ); // prio just after WPML set's it's column
		add_filter( "manage_edit-{$post_type}_columns", array( $this, 'replace_wpml_column' ), 101 ); // prio just after AC overwrite all columns
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

		// load wpml columns in AC columns menu
		add_action( 'cac/set_columns', array( $this, 'add_columns_to_settings_menu' ) );

		// display correct flags on the overview screens
		add_action( 'cac/loaded', array( $this, 'replace_flags' ) );
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
		foreach ( $post_types  as $post_type => $value ) {
			if ( $value ) {
				new CPAC_WPML_COLUMN( $post_type );
			}
		}
	}
	public function add_columns_to_settings_menu( $storage_model ) {
		if ( ! class_exists( 'SitePress', false ) ) {
			return;
		}
		if ( 'post' !== $storage_model->type ) {
			return;
		}

		global $pagenow, $cpac;

		// check if we are on the correct page or when a column is being refreshed by ajax.
		if ( ( 'options-general.php' !== $pagenow ) && ( empty( $_POST['action'] ) || 'cpac_column_refresh' !== $_POST['action'] ) ) {
			return;
		}

		// prevent PHP errors from SitePress
		global $sitepress, $posts;
		$posts = get_posts( array(
			'post_type' => $storage_model->post_type,
			'posts_per_page' => 1
		));

		add_filter( 'manage_' . $storage_model->post_type . 's_columns', array( $sitepress, 'add_posts_management_column' ) );
	}
}
new CPAC_WPML;

/**
 * Fix which remove the Advanced Custom Fields Type (acf) from the admin columns settings page
 *
 * @since 2.0
 *
 * @return array Posttypes
 */
function cpac_remove_acf_from_cpac_post_types( $post_types ) {
	if ( class_exists( 'Acf', false ) ) {
		if ( isset( $post_types['acf'] ) ) {
			unset( $post_types['acf'] );
		}
		if ( isset( $post_types['acf-field-group'] ) ) {
			unset( $post_types['acf-field-group'] );
		}
	}

	return $post_types;
}
add_filter( 'cac/post_types', 'cpac_remove_acf_from_cpac_post_types' );

/**
 * bbPress - remove posttypes: forum, reply and topic
 *
 * The default columns of bbPress are not recognised by Admin Columns as of yet.
 *
 * @since 2.0
 *
 * @return array Posttypes
 */
function cpac_posttypes_remove_bbpress( $post_types ) {
	if ( class_exists( 'bbPress', false ) ) {
		unset( $post_types['topic'] );
		unset( $post_types['reply'] );
		unset( $post_types['forum'] );
	}

	return $post_types;
}
add_filter( 'cac/post_types', 'cpac_posttypes_remove_bbpress' );

/**
 * Fix for Ninja Forms
 *
 * @since 2.0
 *
 * @return array Posttypes
 */
function cpac_remove_ninja_forms_from_cpac_post_types( $post_types ) {
	if ( class_exists( 'Ninja_Forms', false ) ) {
		if ( isset( $post_types['nf_sub'] ) ) {
			unset( $post_types['nf_sub'] );
		}
	}

	return $post_types;
}
add_filter( 'cac/post_types', 'cpac_remove_ninja_forms_from_cpac_post_types' );

/**
 * Add support for All in SEO columns
 *
* @since 2.0
 */
function cpac_load_aioseop_addmycolumns() {
	if ( function_exists('aioseop_addmycolumns') ) {
		aioseop_addmycolumns();
	}
}
add_action( 'cac/columns/default/posts', 'cpac_load_aioseop_addmycolumns' );

/**
 * WPML Register labels
 *
 * To enable the translation of the column labels
 *
 * @since 2.0
 */
function cpac_wpml_register_column_labels() {
	global $cpac;

	// dont load this unless required by WPML
	if ( !isset( $_GET['page'] ) || 'wpml-string-translation/menu/string-translation.php' !== $_GET['page'] ) return;

	foreach ( $cpac->storage_models as $storage_model ) {
		foreach ( $storage_model->get_stored_columns() as $column_name => $options ) {
			icl_register_string( 'Admin Columns', $storage_model->key . '_' . $column_name, stripslashes( $options['label'] ) );
		}
	}
}
add_action( 'wp_loaded', 'cpac_wpml_register_column_labels', 99 );

/**
 * WPML Display translated label
 *
 * @since 2.0
 */
function cpac_wpml_set_translated_label( $label, $column_name, $column_options, $storage_model ) {

	// register with WPML
	if( function_exists('icl_t') ) {
		$name 	= $storage_model->key . '_' . $column_name;
		$label 	= icl_t( 'Admin Columns', $name, $label );
	}

	return $label;
}
add_filter( 'cac/headings/label', 'cpac_wpml_set_translated_label', 10, 4 );

/**
 * Set WPML to be a columns screen for translation so that storage models are loaded
 *
 * @since 2.2
 */
function cpac_wpml_is_cac_screen( $is_columns_screen ) {

	if ( isset( $_GET['page'] ) && $_GET['page'] == 'wpml-string-translation/menu/string-translation.php' ) {
		return true;
	}

	return $is_columns_screen;
}
add_filter( 'cac/is_cac_screen', 'cpac_wpml_is_cac_screen' );