<?php

interface AC_Settings_FormatValueInterface extends AC_Settings_FormatInterface {

	/**
	 * @param AC_ValueFormatter $value
	 *
	 * @return AC_ValueFormatter|AC_Collection
	 */
	public function format( AC_ValueFormatter $value_formatter );

}