<?php

abstract class AC_Settings_SettingAbstract {

	/**
	 * A (short) unique reference to this setting
	 *
	 * @var string
	 */
	protected $id;

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

		$this->set_id();
		$this->set_properties();
		$this->load_settings();
	}

	/**
	 * Create a string representation of this setting
	 *
	 * @return AC_Settings_View
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

	public function has_property( $property ) {
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
	 * @param string $type
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

	/**
	 * Get value of this setting, optionally specified with a key
	 *
	 * Will return the value of the default property
	 *
	 * @param string|null $property
	 *
	 * @return string|array|int|bool
	 */
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
	 * Return the value of all properties
	 *
	 * @return array
	 */
	public function get_values() {
		$values = array();

		foreach ( $this->properties as $property ) {
			$values[ $property ] = $this->get_value( $property );
		}

		return $values;
	}

	/**
	 * Set value of a property
	 *
	 * @param string|array|int|bool $value
	 * @param string $property
	 *
	 * @return $this
	 */
	private function set_value( $value, $property = null ) {
		if ( null === $property ) {
			$property = $this->get_default_property();
		}

		$method = 'set_' . $property;

		if ( method_exists( $this, $method ) ) {
			$this->$method( $value );
		}

		return $this;
	}

	/**
	 * Set a default value unless property is loaded from settings
	 *
	 * @param string|array|int|bool $value
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
	 * Check if a property is user set
	 *
	 * @param string|null $property
	 *
	 * @return bool
	 */
	private function is_user_set( $property = null ) {
		if ( null === $property ) {
			$property = $this->get_default_property();
		}

		return $this->has_property( $property ) && null !== $this->get_setting( $property );
	}

	/**
	 * Retrieve setting for an element
	 *
	 * @param string $property
	 *
	 * @return string
	 */
	private function get_setting( $property ) {
		// todo: make this work!
		//return $this->column->settings()->get_option( $property );

		return $property;
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

	/**
	 * @return string
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * By default it will generate the id, overwrite to hardcode it
	 *
	 * @return $this
	 */
	protected function set_id() {
		$r = new ReflectionClass( $this );
		$id = $r->getShortName();

		// get shortname for prefix syntax
		if ( false !== strpos( $id, '_' ) ) {
			$id = substr( strchr( $id, '_' ), 1 );
		}

		// convert CamelCase to snake_case
		$this->id = strtolower( preg_replace( '/([a-z])([A-Z]+)/', '$1_$2', $id ) );

		return $this;
	}

	public function __toString() {
		$view = $this->view();

		if ( ! ( $view instanceof AC_Settings_View ) ) {
			return '';
		}

		return $view->render();
	}

}