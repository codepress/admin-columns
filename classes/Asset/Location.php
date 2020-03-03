<?php

namespace AC\Asset;

interface Location {

	/**
	 * @return string
	 */
	public function get_url();

	/**
	 * @return string
	 */
	public function get_path();

}
