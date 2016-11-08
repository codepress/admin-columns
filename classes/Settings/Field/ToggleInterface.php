<?php

interface AC_Settings_Field_ToggleInterface {

	/**
	 * @param string $name
	 *
	 */
	public function set_toggle_trigger( $name );

	/**
	 * @return string
	 */
	public function get_toggle_trigger();

}