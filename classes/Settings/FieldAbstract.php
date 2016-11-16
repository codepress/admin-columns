<?php

abstract class AC_Settings_FieldAbstractAC_Settings_FieldAbstract
	implements AC_Settings_ViewInterface {

	/**
	 * @var AC_Column
	 */
	protected $column;

	/**
	 * The properties this field manages
	 *
	 * @var array
	 */
	protected $properties = array();

	public function __construct( AC_Column $column, $defaults = array() ) {
		$this->column = $column;

		$this->set_properties();
		$this->set_values( $defaults );
		$this->load_settings();
	}

	private function set_values( $values ) {
		foreach ( $values as $property => $value ) {
			$method = 'set_' . $property;

			if ( method_exists( $method ) ) {
				$this->$method( $value );
			}
		}

		return $this;
	}

	/**
	 * Set the properties this field manages
	 */
	protected abstract function set_properties();

	protected function has_properties() {
		return ! empty( $this->properties );
	}

	protected function get_default_property() {
		if ( empty( $this->properties ) ) {
			return false;
		}

		return $this->properties[0];
	}

	public function get_value( $property = null ) {
		if ( null === $property ) {
			$property = $this->get_default_property();
		}

		$method = 'get_' . $property;

		if ( ! method_exists( $this, $method ) ) {
			return false;
		}

		return $this->$method;
	}

	/**
	 * Retrieve setting (value) for an element
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	private function get_setting( $name ) {
		return $this->column->settings()->get_option( $name );
	}

	/**
	 * Retrieve settings
	 *
	 */
	private function load_settings() {
		$settings = array();

		foreach ( $this->properties as $property ) {
			$setting = $this->get_setting( $property );

			if ( $setting ) {
				$settings[ $property ] = $setting;
			}
		}

		$this->set_values( $settings );
	}

	public function __toString() {
		return $this->render();
	}

}