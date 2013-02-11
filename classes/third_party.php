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
function pre_load_wordpress_seo_class_metabox() {
	global $pagenow;

	if (
		isset($_REQUEST['page']) &&
		'codepress-admin-columns' == $_REQUEST['page'] &&
		'admin.php' == $pagenow &&
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
* @since 2.0.0
 *
 * @return array Posttypes
 */
function remove_acf_from_cpac_post_types( $post_types )
{
	if ( class_exists('Acf') ) {
		unset( $post_types['acf'] );
	}

	return $post_types;
}
add_filter( 'cpac_get_post_types', 'remove_acf_from_cpac_post_types' );

/**
 * bbPress - remove posttypes: forum, reply and topic
 *
* @since 2.0.0
 *
 * @return array Posttypes
 */
function cpac_posttypes_remove_bbpress( $post_types )
{
	if ( class_exists( 'bbPress' ) ) {
		unset( $post_types['topic'] );
		unset( $post_types['reply'] );
		unset( $post_types['forum'] );
	}

	return $post_types;
}
add_filter( 'cpac_get_post_types', 'cpac_posttypes_remove_bbpress' );

/**
 * Add support for All in SEO columns
 *
* @since 2.0.0
 */
function cpac_load_aioseop_addmycolumns()
{
	if ( function_exists('aioseop_addmycolumns') ) {
		aioseop_addmycolumns();
	}
}
add_action( 'cpac_before_default_columns_posts', 'cpac_load_aioseop_addmycolumns' );
