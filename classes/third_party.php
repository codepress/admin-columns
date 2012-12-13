<?php

/**
 * Fix for getting the columns loaded by WordPress SEO Yoast
 *
 * The added columns from WordPress SEO by Yoast weren't available on
 * the admin columns settings page. The reason was that class-metabox.php was prevented
 * from loading. This fix will also load this class when admin columns is loaded.
 *
 * @since     1.4.6
 */
function pre_load_wordpress_seo_class_metabox()
{
	global $pagenow;

	if (
		isset($_REQUEST['page']) &&
		'codepress-admin-columns' == $_REQUEST['page'] &&
		'options-general.php' == $pagenow &&
		defined('WPSEO_PATH') &&
		file_exists(WPSEO_PATH.'admin/class-metabox.php')
		) {
		require_once WPSEO_PATH.'admin/class-metabox.php';
	}
}
add_action( 'plugins_loaded', 'pre_load_wordpress_seo_class_metabox', 0 );

/**
 * Fix which remove the Advanced Custom Fields Type (acf) from the admin columns settings page
 *
 * @since 1.5
 */
function remove_acf_from_cpac_post_types( $post_types )
{
	if ( class_exists('Acf') ) {
		unset( $post_types['acf'] );
	}

	return $post_types;
}
add_filter( 'cpac-get-post-types', 'remove_acf_from_cpac_post_types' );

/**
 * Fix which removes bbPress Posttypes ( forum, reply and topic ) from the admin columns settings page
 *
 * @since 1.5
 */
function remove_bbpress_from_cpac_post_types( $post_types )
{
	if ( class_exists('bbPress') ) {
		unset( $post_types['topic'] );
		unset( $post_types['reply'] );
		unset( $post_types['forum'] );
	}

	return $post_types;
}
add_filter( 'cpac-get-post-types', 'remove_bbpress_from_cpac_post_types' );

/**
 * Add support for All in SEO columns
 *
 * @since 1.5
 */
function cpac_load_aioseop_addmycolumns()
{
	if ( function_exists('aioseop_addmycolumns') ) {
		aioseop_addmycolumns();
	}
}
add_action( 'cpac-get-default-columns-posts', 'cpac_load_aioseop_addmycolumns' );