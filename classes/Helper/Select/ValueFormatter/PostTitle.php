<?php
declare( strict_types=1 );

namespace AC\Helper\Select\ValueFormatter;

use AC\Helper\Select\ValueFormatter;
use WP_Post;

class PostTitle implements ValueFormatter {

	public function format_value( $entity ): string {
		if ( is_numeric( $entity ) ) {
			$entity = get_post( (int) $entity );
		}

		if ( ! $entity instanceof WP_Post ) {
			return '';
		}

		$label = $entity->post_title;

		if ( 'attachment' === $entity->post_type ) {
			$label = ac_helper()->image->get_file_name( $entity->ID );
		}

		if ( ! $label ) {
			$label = sprintf( __( '#%d (no title)' ), $entity->ID );
		}

		return (string) apply_filters( 'acp/select/formatter/post_title', $label, $entity );
	}

}