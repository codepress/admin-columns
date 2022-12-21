<?php
declare( strict_types=1 );

namespace AC\Helper\Select\ValueFormatter;

use AC\Helper\Select\ValueFormatter;
use WP_Post_Type;

class PostTypeLabel implements ValueFormatter {

	public function format_value( $entity ): string {
		if ( ! $entity instanceof WP_Post_Type ) {
			$entity = get_post_type_object( $entity );
		}

		if ( ! $entity instanceof WP_Post_Type ) {
			return '';
		}

		return (string) $entity->labels->singular_name;
	}

}