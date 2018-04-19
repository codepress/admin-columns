<?php

namespace AC;

interface ViewInterface {

	/**
	 * Get a string representation of this object
	 *
	 * @return string
	 */
	public function render();

	/**
	 * Should call self::render when treated as a string
	 *
	 * @return string
	 */
	public function __toString();

}