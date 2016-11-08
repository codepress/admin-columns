<?php

interface AC_Settings_Field_EventInterface {

	/**
	 * @param string $name
	 *
	 */
	public function set_event_change( $name );

	/**
	 * @return string
	 */
	public function get_event_change();

	/**
	 * @param bool $refresh
	 *
	 */
	public function set_event_refresh( $refresh );

	/**
	 * @return bool
	 */
	public function get_event_refresh();

}