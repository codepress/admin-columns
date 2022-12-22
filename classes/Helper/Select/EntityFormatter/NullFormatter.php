<?php

namespace AC\Helper\Select\EntityFormatter;

use AC\Helper\Select\EntityFormatter;

final class NullFormatter
	implements EntityFormatter {

	public function format_entity_value( $entity ): string {
		return is_scalar( $entity )
			? (string) $entity
			: '';
	}

}