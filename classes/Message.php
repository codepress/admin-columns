<?php

// TODO: maybe remove...?
interface AC_Message {

	/**
	 * Display message
	 *
	 * @return void
	 */
	public function display();

	/**
	 * Get the message
	 *
	 * @return string
	 */
	public function get_message();

	/**
	 * @param string $message
	 */
	//public function set_message( $message );

	/**
	 * Hook to register this message on
	 *
	 * @return void
	 */
	//public function register();

}