<?php

namespace AC\Settings;

interface FormatValue {

	/**
	 * @param mixed $value
	 * @param mixed $original_value
	 *
	 * @return mixed
	 */
	public function format( $value, $original_value );

}