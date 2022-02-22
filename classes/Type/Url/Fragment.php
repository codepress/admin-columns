<?php

namespace AC\Type\Url;

trait Fragment {

	/**
	 * @var string
	 */
	protected $fragment;

	/**
	 * @param string $path
	 */
	protected function set_fragment( $fragment ) {
		$this->fragment = '#' . $fragment;
	}

	/**
	 * @return string
	 */
	protected function get_fragment() {
		return $this->fragment;
	}

	/**
	 * @return bool
	 */
	protected function has_fragment() {
		return null !== $this->fragment;
	}

}