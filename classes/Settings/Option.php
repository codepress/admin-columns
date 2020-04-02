<?php

namespace AC\Settings;

class Option {

	/**
	 * @var string
	 */
	private $name;

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	private function get_options() {
		return get_option( General::NAME );
	}

	/**
	 * @return bool
	 */
	public function is_empty() {
		return false === $this->get_options();
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function get() {
		$options = $this->get_options();

		if ( ! $options || ! array_key_exists( $this->name, $options ) ) {
			return false;
		}

		return $options[ $this->name ];
	}

	/**
	 * @param mixed $value
	 */
	public function save( $value ) {
		$options = $this->get_options();

		if ( false === $options ) {
			$options = [];
		}

		$options[ $this->name ] = $value;

		$this->update_option( $options );
	}

	/**
	 * @param string $name
	 */
	public function delete() {
		$options = $this->get_options();

		if ( empty( $options ) ) {
			return;
		}

		unset( $options[ $this->name ] );

		$this->update_option( $options );
	}

	private function update_option( $options ) {
		update_option( General::NAME, $options );
	}

}