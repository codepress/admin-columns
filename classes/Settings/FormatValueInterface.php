<?php

interface AC_Settings_FormatValueInterface {

	/**
	 * @param mixed $value
	 * @param mixed $original_value
	 *
	 * @return mixed
	 */
	public function format( $value, $original_value );

}