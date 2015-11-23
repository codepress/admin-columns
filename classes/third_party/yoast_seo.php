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


function cpac_seo_column_heading( $columns ) {

	$columns['wpseo-score']    = __( 'SEO', 'wordpress-seo' );
	$columns['wpseo-title']    = __( 'SEO Title', 'wordpress-seo' );
	$columns['wpseo-metadesc'] = __( 'Meta Desc.', 'wordpress-seo' );
	$columns['wpseo-focuskw']  = __( 'Focus KW', 'wordpress-seo' );

	return $columns;
}


function cpac_seo_headings() {
	if ( ! cac_is_doing_ajax() && ! cac_is_setting_screen() ) {
		return;
	}

	$post_types = (array) get_post_types( array( 'public' => true ), 'names' );

	foreach ( $post_types as $pt ) {
		add_filter( 'manage_' . $pt . '_posts_columns', 'cpac_seo_column_heading', 10, 1 );
	}

}

add_filter( 'plugins_loaded', 'cpac_seo_headings' );
