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