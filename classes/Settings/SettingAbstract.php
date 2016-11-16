<?php

abstract class AC_Settings_SettingAbstract {

	/**
	 * @var AC_Column
	 */
	protected $column;

	/**
	 * @var AC_Settings_Form_ElementAbstract[]
	 */
	protected $elements = array();

	/**
	 * The properties this field manages
	 *
	 * @var array
	 */
	protected $properties = array();

	/**
	 * @var AC_Settings_ViewAbstract
	 */
	protected $view;

	public function __construct( AC_Column $column, $defaults = array() ) {
		$this->column = $column;

		$this->init_view();
		$this->set_properties();
		$this->set_values( $defaults );
		$this->load_settings();
	}

	public function render() {
		return $this->view->render();
	}

	private function init_view() {
		$view = new AC_Settings_View_Section();
		$view->nest( new AC_Settings_View_Label(), 'label' );
		$view->nest( new AC_Settings_View_Setting(), 'settings' );

		$this->view = $view;
	}

	/**
	 * Add an element to this setting
	 *
	 * @param string $name
	 * @param sting $type
	 *
	 * @return AC_Settings_Form_ElementAbstract
	 */
	protected function add_element( $name, $type = null ) {
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

		$element->set_name( sprintf( 'columns[%s][%s]' ), $this->column->get_name(), $name );
		$element->set_id( sprintf( 'ac-%s-%s' ), $this->column->get_name(), $name );

		$this->elements[ $name ] = $element;

		return $element;
	}

	// todo: maybe implement __get for elements

	protected function get_element( $name ) {
		return isset( $this->elements[ $name ] ) ? $this->elements[ $name ] : false;
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

	protected function add_property( $property ) {
		$this->properties[] = $property;

		return $this;
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
		return $this->view->render();
	}

}