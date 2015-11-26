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
	if ( ! defined( 'WPSEO_PATH' ) ) {
		return;
	}

	if ( ! cac_is_doing_ajax() && ! cac_is_setting_screen() ) {
		return;
	}

	// Yoast SEO version > 3.0
	if ( file_exists( WPSEO_PATH . 'admin/class-meta-columns.php' ) ) {
		require_once WPSEO_PATH . 'admin/class-meta-columns.php';
		if ( class_exists( 'WPSEO_Meta_Columns', false ) ) {
			$metabox = new WPSEO_Meta_Columns;
			if ( method_exists( $metabox, 'setup_hooks' ) ) {
				$metabox->setup_hooks();
			}
		}
	}

	// Yoast SEO version < 3.0
	if ( file_exists( WPSEO_PATH . 'admin/class-meta-box.php' ) ) {
		require_once WPSEO_PATH . 'admin/class-meta-box.php';
		if ( class_exists( 'WPSEO_Meta_Columns', false ) ) {
			new WPSEO_Metabox;
		}
	}
}

add_action( 'plugins_loaded', 'cpac_pre_load_wordpress_seo_class_metabox', 0 );