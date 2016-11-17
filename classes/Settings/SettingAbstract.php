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
	private $view;

	public function __construct( AC_Column $column, $defaults = array() ) {
		$this->column = $column;
		$this->view = $this->create_view();

		$this->set_properties();
		$this->set_values( $defaults );
		$this->load_settings();
	}

	/**
	 * Create a string representation of this setting
	 *
	 * @return AC_Settings_ViewAbstract
	 */
	public abstract function render();

	/**
	 * Set the properties this field manages
	 *
	 */
	protected abstract function set_properties();

	/**
	 * Creates a view and sets default subviews.
	 *
	 * @param string $type Defaults to 'section'
	 *
	 * @return AC_Settings_ViewAbstract
	 */
	protected function create_view( $type = 'section' ) {
		switch ( $type ) {
			case 'setting':
				$view = new AC_Settings_View_Setting();

				break;
			default:
				$view = new AC_Settings_View_Section();
				$view->set_view( new AC_Settings_View_Setting(), 'setting' );
		}

		return $view;
	}

	/**
	 * @return AC_Settings_ViewAbstract
	 */
	public function get_view() {
		return $this->view;
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

		$element->set_name( sprintf( 'columns[%s][%s]', $this->column->get_name(), $name ) );
		$element->set_id( sprintf( 'ac-%s-%s', $this->column->get_name(), $name ) );

		// try to set current value
		$method = 'get_' . $name;

		if ( method_exists( $this, $method ) ) {
			$element->set_value( $this->$method() );
		}

		$this->elements[ $name ] = $element;

		return $element;
	}

	protected function get_elements() {
		return $this->elements;
	}

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

		return $this->$method();
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
		$view = $this->render();

		return $view->render();
	}

}