<?php

interface AC_Settings_FormatInterface {

	/**
	 * @param string|int $value
	 *
	 * @return string
	 */
	public function format( $value );

}