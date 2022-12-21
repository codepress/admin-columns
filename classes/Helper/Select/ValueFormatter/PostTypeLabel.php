<?php
declare( strict_types=1 );

namespace AC\Helper\Select\ValueFormatter;

use AC\Helper\Select\ValueFormatter;

class PostTypeLabel implements ValueFormatter {

	public function format_value( $entity ): string {
		// TODO validate $entity

		return (string) $entity->label;
	}

}