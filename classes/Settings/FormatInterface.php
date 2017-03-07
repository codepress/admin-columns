<?php

interface AC_Settings_FormatInterface {

	/**
	 * @param string|int $value
	 * @param int $id Object ID
	 *
	 * @return string
	 */
	public function format( $value, $object_id = null );

}