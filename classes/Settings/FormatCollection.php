<?php

namespace AC\Settings;

use AC\Collection;

interface FormatCollection {

	/**
	 * @param Collection $collection
	 * @param mixed      $original_value
	 *
	 * @return mixed
	 */
	public function format( Collection $collection, $original_value );

}