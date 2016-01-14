<?php

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