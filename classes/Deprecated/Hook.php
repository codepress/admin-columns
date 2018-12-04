<?php
namespace AC\Deprecated;

abstract class Hook {

	/** @var string */
	private $name;

	/** @var string */
	private $version;

	/** @var string */
	private $slug;

	public function __construct( $name, $version, $slug = null ) {
		$this->name = $name;
		$this->version = $version;
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * @return bool
	 */
	abstract public function has_hook();

	/**
	 * @return array|false
	 */
	public function get_callbacks() {
		global $wp_filter;

		if ( ! isset( $wp_filter[ $this->name ] ) ) {
			return false;
		}

		if ( empty( $wp_filter[ $this->name ]->callbacks ) ) {
			return false;
		}

		$callbacks = array();

		foreach ( $wp_filter[ $this->name ]->callbacks as $callback ) {
			foreach ( $callback as $cb ) {

				// Function
				if ( is_scalar( $cb['function'] ) ) {
					$callbacks[] = $cb['function'];
				}

				// Method
				if ( is_array( $cb['function'] ) ) {
					$callbacks[] = get_class( $cb['function'][0] ) . '::' . $cb['function'][1];
				}
			}
		}

		if ( ! $callbacks ) {
			return false;
		}

		return $callbacks;
	}

}