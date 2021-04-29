<?php

namespace AC;

interface ApplyFilter {

	/**
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function apply_filters( $value );

}