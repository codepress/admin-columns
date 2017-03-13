<?php

interface AC_Settings_FormatInterface {

	/**
	 * @param AC_Value $value
	 *
	 * @return AC_Value
	 */
	public function format( AC_Value $value );

}