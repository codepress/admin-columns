<?php

interface AC_Settings_FormatCollectionInterface extends AC_Settings_FormatInterface {

	/**
	 * @param AC_Collection $collection
	 * @param mixed         $original_value
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_Collection $collection, $original_value );

}