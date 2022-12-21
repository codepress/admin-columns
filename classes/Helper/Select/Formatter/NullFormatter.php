<?php

namespace AC\Helper\Select\Formatter;

use AC\Helper\Select;

class NullFormatter extends Select\Formatter {

	public function __construct( Select\Entities $entities, Select\UnqiueValueFormatter $value = null ) {
		if ( null === $value ) {
			$value = new Select\Value\NullFormatter();
		}

		parent::__construct( $entities, $value );
	}

	/**
	 * @param string $label
	 *
	 * @return string
	 */
	public function get_label( $label ) {
		return $label;
	}

}