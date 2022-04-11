<?php

namespace AC\Column;

interface DetailedValue {

	/**
	 * @param int $id
	 *
	 * @return string
	 */
	public function get_modal_value( $id );

}