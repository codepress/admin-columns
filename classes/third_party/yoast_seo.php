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
function cpac_pre_load_wordpress_seo_class_metabox( $columns, $storage_model ) {
	if ( defined( 'WPSEO_PATH' ) ) {
		if ( $post_type = $storage_model->get_post_type() ) {
			if ( ! in_array( $post_type, get_post_types( array( 'public' => true ), 'names' ) ) ) {
				return $columns;
			}
		}

		$columns['wpseo-score'] = __( 'SEO', 'wordpress-seo' );
		$columns['wpseo-title'] = __( 'SEO Title', 'wordpress-seo' );
		$columns['wpseo-metadesc'] = __( 'Meta Desc.', 'wordpress-seo' );
		$columns['wpseo-focuskw'] = __( 'Focus KW', 'wordpress-seo' );
	}

	return $columns;
}
add_filter( "cac/default_columns/type=post", "cpac_pre_load_wordpress_seo_class_metabox", 10, 2 );
add_filter( "cac/default_columns/type=taxonomy", "cpac_pre_load_wordpress_seo_class_metabox", 10, 2 );