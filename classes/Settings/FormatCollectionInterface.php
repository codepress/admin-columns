<?php

interface AC_Settings_FormatCollectionInterface {

	/**
	 * @param AC_Collection $collection
	 * @param mixed         $original_value
	 *
	 * @return mixed
	 */
	public function format( AC_Collection $collection, $original_value );

}