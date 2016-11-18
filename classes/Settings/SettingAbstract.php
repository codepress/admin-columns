<?php

abstract class AC_Settings_SettingAbstract {

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

	/**
	 * @param AC_Column $column
	 */
	public function __construct( AC_Column $column ) {
		$this->column = $column;

		$this->set_properties();
		$this->load_settings();
	}

	/**
	 * Create a string representation of this setting
	 *
	 * @return AC_Settings_ViewAbstract
	 */
	public abstract function view();

	/**
	 * Set the properties this field manages
	 *
	 */
	protected abstract function set_properties();

	protected function has_properties() {
		return ! empty( $this->properties );
	}

	protected function has_property( $property ) {
		return in_array( $property, $this->properties );
	}

	protected function get_default_property() {
		if ( empty( $this->properties ) ) {
			return false;
		}

		return $this->properties[0];
	}

	/**
	 * Add an element to this setting
	 *
	 * @param string $name
	 * @param sting $type
	 *
	 * @return AC_Settings_Form_ElementAbstract
	 */
	protected function create_element( $name, $type = null ) {
		switch ( $type ) {
			case 'radio':
				$element = new AC_Settings_Form_Element_Radio( $name );

				break;
			case 'select':
				$element = new AC_Settings_Form_Element_Select( $name );

				break;
			default:
				$element = new AC_Settings_Form_Element_Input( $name );
				$element->set_type( $type );
		}

		$element->set_name( sprintf( 'columns[%s][%s]', $this->column->get_name(), $name ) );
		$element->set_id( sprintf( 'ac-%s-%s', $this->column->get_name(), $name ) );

		// try to set current value
		$method = 'get_' . $name;

		if ( method_exists( $this, $method ) ) {
			$element->set_value( $this->$method() );
		}

		return $element;
	}

	public function get_value( $property = null ) {
		if ( null === $property ) {
			$property = $this->get_default_property();
		}

		$method = 'get_' . $property;

		if ( ! method_exists( $this, $method ) ) {
			return false;
		}

		return $this->$method();
	}

	private function set_value( $property, $value ) {
		$method = 'set_' . $property;

		if ( method_exists( $this, $method ) ) {
			$this->$method( $value );
		}

		return $this;
	}

	/**
	 * Set a default value unless property is loaded from settings
	 *
	 * @param $value
	 * @param string $property
	 *
	 * @return $this
	 */
	public function set_default( $value, $property = null ) {
		if ( null === $property ) {
			$property = $this->get_default_property();
		}

		if ( ! $this->is_user_set( $property ) ) {
			$this->set_value( $property, $value );
		}

		return $this;
	}

	/**
	 * Retrieve setting for an element
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	private function get_setting( $name ) {
		return $this->column->settings()->get_option( $name );
	}

	/**
	 * Check if a property is user set
	 *
	 * @param string $property
	 */
	private function is_user_set( $property ) {
		return $this->has_property( $property ) && null !== $this->get_setting( $property );
	}

	/**
	 * Retrieve settings
	 *
	 */
	private function load_settings() {
		foreach ( $this->properties as $property ) {
			$setting = $this->get_setting( $property );

			if ( $setting ) {
				$this->set_value( $property, $setting );
			}
		}
	}

	public function __toString() {
		$view = $this->view();

		if ( ! ( $view instanceof AC_Settings_View ) ) {
			return '';
		}

		return $view->render();
	}

}