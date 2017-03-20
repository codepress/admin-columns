<?php

interface AC_Settings_FormatCollectionInterface extends AC_Settings_FormatInterface {

	/**
	 * @param AC_Collection $collection
	 * @param int           $id
	 *
	 * @return AC_ValueFormatter|AC_Collection
	 */
	public function format( AC_Collection $collection, $id );

}