<?php

/**
 * Fix for getting the columns loaded by WordPress SEO Yoast
 *
 * The added columns from WordPress SEO by Yoast weren't available on
 * the admin columns settings page. The eason was that class-metabox.php was prevented
 * from loading. This fix will also load this class when admin columns is loaded.
 *
 * @since     1.4.6
 */
function pre_load_wordpress_seo_class_metabox()
{
	global $pagenow;
	
	if ( 
		isset($_REQUEST['page']) && 'codepress-admin-columns' == $_REQUEST['page'] && 
		'options-general.php' == $pagenow && defined('WPSEO_PATH') && 
		file_exists(WPSEO_PATH.'admin/class-metabox.php')
		) {
		require_once WPSEO_PATH.'admin/class-metabox.php';
	}
}
add_action( 'plugins_loaded', 'pre_load_wordpress_seo_class_metabox', 0 );