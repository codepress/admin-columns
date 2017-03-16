<?php

interface AC_Settings_FormatInterface {

	const DEFAULT_FORMAT_PRIORITY = 10;

	/**
	 * @param AC_ValueFormatter $value
	 *
	 * @return AC_ValueFormatter|AC_Collection
	 */
	public function format( AC_ValueFormatter $formatter );

	/**
	 * Decides the order in which a formatter is applied
	 *
	 * @return int
	 */
	public function get_format_priority();

}