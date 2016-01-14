<?php

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