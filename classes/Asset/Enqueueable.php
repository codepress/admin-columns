<?php

namespace AC\Asset;

interface Enqueueable {

	/**
	 * @return string
	 */
	public function get_handle();

	/**
	 * @return void
	 */
	public function register();

	/**
	 * @return void
	 */
	public function enqueue();

}
