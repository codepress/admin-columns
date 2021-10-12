<?php

namespace AC\Column;

interface ExtendedValue {

	/**
	 * @param int $id
	 *
	 * @return string
	 */
	public function get_extended_value( $id );

}