<?php

namespace AC\Helper\Select\Value;

use AC\Helper\Select\UnqiueValueFormatter;

final class NullFormatter
	implements UnqiueValueFormatter {

	public function format_value_unique( $value ) {
		return $value;
	}

}