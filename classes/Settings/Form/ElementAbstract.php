<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class AC_Settings_Form_ElementAbstract {

	private $attributes = array();

	protected $name;

	protected $id;

	protected $value = false;

	protected $default_value = false;

	public function set_attribute( $key, $value ) {
		$this->attributes[ $key ] = $value;
	}

	public function get_attribute( $key ) {
		$getter = 'get_' . $key;

		if ( method_exists( $this, $getter ) && $getter != __FUNCTION__ ) {
			return $this->$getter;
		}

		if ( ! isset( $this->attributes[ $key ] ) ) {
			return false;
		}

		return $this->attributes[ $key ];
	}

	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Render an attribute
	 *
	 * @param $key
	 * @param bool $return Return or echo output
	 *
	 * @return null|string
	 */
	protected function attribute( $key, $return = false ) {
		$attribute = $this->get_attribute( $key );

		if ( ! $attribute ) {
			return;
		}

		$output = sprintf( '%s="%s"', $key, esc_attr( $attribute ) );

		if ( $return ) {
			return $output;
		}

		echo $output;
	}

	public function get_name() {
		return $this->name;
	}

	/**
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_name( $name ) {
		$this->name = $name;

		return $this;
	}

	public function get_id() {
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return $this
	 */
	public function set_id( $id ) {
		$this->id = $id;

		return $this;
	}

	public function get_default_value() {
		return $this->default_value;
	}

	public function get_value() {
		$value = $this->value;

		if ( empty( $value ) ) {
			$value = $this->get_default_value();
		}

		// todo: add stripslashes? some fields have it
		return $value;
	}

	/**
	 * @param mixed $value
	 *
	 * @return $this
	 */
	public function set_value( $value ) {
		$this->value = $value;

		return $this;
	}

	public function set_class( $class ) {
		$this->set_attribute( 'class', $class );

		return $this;
	}

	public function add_class( $class ) {
		$parts = explode( ' ', (string) $this->get_attribute( 'class' ) );
		$parts[] = $class;

		$this->set_class( implode( ' ', $class ) );

		return $this;
	}

}