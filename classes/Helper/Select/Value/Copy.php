<?php
namespace AC\Helper\Select\Value;

use AC\Helper\Select\Value;

final class Copy
	implements Value {

	public function get_value( $value ) {
		return $value;
	}

}