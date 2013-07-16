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

	if ( defined('WPSEO_PATH') && file_exists(WPSEO_PATH.'admin/class-metabox.php') ) {
		if (
		( isset($_GET['page']) && 'codepress-admin-columns' == $_GET['page'] && 'options-general.php' == $pagenow )
		||
		// for when column list is populated through ajax
		( defined('DOING_AJAX') && DOING_AJAX && ! empty( $_POST['type'] ) )
		) {
			require_once WPSEO_PATH.'admin/class-metabox.php';
		}
	}
}
add_action( 'plugins_loaded', 'pre_load_wordpress_seo_class_metabox', 0 );

/**
 * WPML compatibility
 *
 * @since 2.0.0
 */
function cac_add_wpml_columns( $storage_model ) {
	global $pagenow;

	// only for posts
	if ( 'options-general.php' !== $pagenow || 'post' !== $storage_model->type ) return;

	global $sitepress, $posts, $__management_columns_posts_translations;

	// prevent DB error
	$__management_columns_posts_translations = 'not_null';

	$post_type = $storage_model->key;

	// Is needed by SitePress::add_posts_management_column()
	$posts = get_posts( array(
		'post_type' 	=> $post_type,
		'numberposts' 	=> -1
	));

	// Trigger SitePress::add_posts_management_column() so admin coumkns can pick up it's added column heading
	add_filter( "manage_{$post_type}s_columns", array( $sitepress, 'add_posts_management_column' ) );
}
add_action( 'cac/get_columns', 'cac_add_wpml_columns' );



//add_filter( 'manage_pages_columns', array( $sitepress, 'add_posts_management_column' ) );

//add_filter('manage_'.$post_type.'s_columns',array($this,'add_posts_management_column'));
//add_filter('manage_'.$post_type.'_posts_columns',array($this,'add_posts_management_column'));

/**
 * Fix which remove the Advanced Custom Fields Type (acf) from the admin columns settings page
 *
 * @since 2.0.0
 *
 * @return array Posttypes
 */
function remove_acf_from_cpac_post_types( $post_types ) {
	if ( class_exists('Acf') ) {
		unset( $post_types['acf'] );
	}

	return $post_types;
}
add_filter( 'cac/post_types', 'remove_acf_from_cpac_post_types' );

/**
 * bbPress - remove posttypes: forum, reply and topic
 *
* @since 2.0.0
 *
 * @return array Posttypes
 */
function cpac_posttypes_remove_bbpress( $post_types ) {
	if ( class_exists( 'bbPress' ) ) {
		unset( $post_types['topic'] );
		unset( $post_types['reply'] );
		unset( $post_types['forum'] );
	}

	return $post_types;
}
add_filter( 'cac/post_types', 'cpac_posttypes_remove_bbpress' );

/**
 * Add support for All in SEO columns
 *
* @since 2.0.0
 */
function cpac_load_aioseop_addmycolumns() {
	if ( function_exists('aioseop_addmycolumns') ) {
		aioseop_addmycolumns();
	}
}
add_action( 'cac/columns/default/posts', 'cpac_load_aioseop_addmycolumns' );

