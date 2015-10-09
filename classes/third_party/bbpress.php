<?php

/**
 * bbPress - remove posttypes: forum, reply and topic
 *
 * The default columns of bbPress are not recognised by Admin Columns as of yet.
 *
 * @since 2.0
 *
 * @return array Posttypes
 */
function cpac_posttypes_remove_bbpress( $post_types ) {
	if ( class_exists( 'bbPress', false ) ) {
		unset( $post_types['topic'] );
		unset( $post_types['reply'] );
		unset( $post_types['forum'] );
	}

	return $post_types;
}
add_filter( 'cac/post_types', 'cpac_posttypes_remove_bbpress' );