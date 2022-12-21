<?php
declare( strict_types=1 );

namespace AC\Helper\Select\ValueFormatter;

use AC\Helper\Select\ValueFormatter;

class StringFormatter implements ValueFormatter {

	public function format_value( $entity ): string {
		return is_scalar( $entity )
			? (string) $entity
			: '';
	}

}