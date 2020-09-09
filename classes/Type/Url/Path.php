<?php

namespace AC\Type\Url;

trait Path {

	/**
	 * @var string
	 */
	protected $path;

	/**
	 * @param string $path
	 */
	protected function set_path( $path ) {
		$this->path = '/' . trim( $path, '/' ) . '/';
	}

	/**
	 * @return string
	 */
	protected function get_path() {
		return $this->path;
	}

}