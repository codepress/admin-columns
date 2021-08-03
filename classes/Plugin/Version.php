<?php

namespace AC\Plugin;

class Version {

	/**
	 * @var string
	 */
	private $value;

	public function __construct( $version ) {
		$this->value = (string) $version;
	}

	/**
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * @return bool
	 */
	public function is_valid() {
		return ! empty( $this->value );
	}

	/**
	 * Greater than
	 *
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function is_gt( Version $version ) {
		return version_compare( $this->value, $version->get_value(), '>' );
	}

	/**
	 * Lesser than
	 *
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function is_lt( Version $version ) {
		return version_compare( $this->value, $version->get_value(), '<' );
	}

	/**
	 * Greater than or Equal
	 *
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function is_gte( Version $version ) {
		return version_compare( $this->value, $version->get_value(), '>=' );
	}

	/**
	 * Lesser than or Equal
	 *
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function is_lte( Version $version ) {
		return version_compare( $this->value, $version->get_value(), '<=' );
	}

	/**
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function is_equal( Version $version ) {
		return 0 === version_compare( $this->value, $version->get_value() );
	}

	/**
	 * @param Version $version
	 *
	 * @return bool
	 */
	public function is_not_equal( Version $version ) {
		return ! $this->is_equal( $version );
	}

	/**
	 * @return bool
	 */
	public function is_beta() {
		return false !== strpos( $this->value, 'beta' );
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return $this->value;
	}

}