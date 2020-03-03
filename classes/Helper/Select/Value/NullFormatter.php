<?php

namespace AC\Helper\Select\Value;

use AC\Helper\Select\Value;

final class NullFormatter
	implements Value {

	public function get_value( $value ) {
		return $value;
	}

}