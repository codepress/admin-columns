<?php

interface AC_Settings_FormatInterface {

	/**
	 * The higher this integer, the later this formatter will be applied on the value
	 */
	const FORMAT_PRIORITY = 10;

	/**
	 * @param AC_ValueFormatter $value
	 *
	 * @return AC_ValueFormatter|AC_Collection
	 */
	public function format( AC_ValueFormatter $formatter );

}