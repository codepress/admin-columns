<?php
declare( strict_types=1 );

namespace AC\Helper\Select\UniqueValueFormatter;

use AC\Helper\Select\UnqiueValueFormatter;
use WP_Post;

class PostId implements UnqiueValueFormatter {

	public function format_value_unique( $value ): string {
		// TODO throw exception?
		return $value instanceof WP_Post
			? (string) $value->ID
			: '';
	}

}