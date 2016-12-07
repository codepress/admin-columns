<?php

abstract class AC_Settings_Setting {

	/**
	 * A (short) reference to this setting
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The options this field manages
	 *
	 * @var array
	 */
	protected $managed_options = array();

	/**
	 * Default values
	 *
	 * @var array
	 */
	private $defaults = array();

	/**
	 * @var AC_Column
	 */
	protected $column;

	/**
	 * @param AC_Column $column
	 */
	public function __construct( AC_Column $column ) {
		$this->column = $column;

		$this->set_managed_options();
		$this->set_name();

		// parse defaults that are set directly on the properties
		foreach ( $this->get_values() as $key => $value ) {
			if ( null !== $value ) {
				$this->set_default( $value, $key );
			}
		}
	}

	/**
	 * Create a string representation of this setting
	 *
	 * @return AC_View|false
	 */
	public abstract function create_view();

	/**
	 * Set the options this field manages
	 *
	 */
	protected abstract function set_managed_options();

	/**
	 * Get settings that depend on this setting
	 *
	 * @return AC_Settings_Setting[]
	 */
	public function get_dependent_settings() {
		return array();
	}

	public function has_managed_option( $option ) {
		return in_array( $option, $this->managed_options );
	}

	public function get_managed_options() {
		return $this->managed_options;
	}

	/**
	 * Get a managed option
	 *
	 * @param null|string $option
	 *
	 * @return false|string
	 */
	protected function get_managed_option( $option = null ) {
		foreach ( $this->managed_options as $managed_option ) {
			if ( null === $option || $option == $managed_option ) {
				return $managed_option;
			}
		}

		return false;
	}

	/**
	 * Add an element to this setting
	 *
	 * @param string $type
	 * @param string|null $name
	 *
	 * @return AC_Settings_Form_Element_Select|AC_Settings_Form_Element_Input|AC_Settings_Form_Element_Radio
	 */
	protected function create_element( $type, $name = null ) {
		if ( null === $name ) {
			$name = $this->get_managed_option();
		}

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
		$element->add_class( 'ac-setting-input_' . $name );

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
	 * Will return the value of the default option
	 *
	 * @param string|null $option
	 *
	 * @return string|array|int|bool
	 */
	public function get_value( $option = null ) {
		if ( null === $option ) {
			$option = $this->get_managed_option();
		}

		$method = 'get_' . $option;

		if ( ! method_exists( $this, $method ) ) {
			return null;
		}

		return $this->$method();
	}

	/**
	 * Return the value of all options
	 *
	 * @return array
	 */
	public function get_values() {
		$values = array();

		foreach ( $this->managed_options as $managed_option ) {
			$values[ $managed_option ] = $this->get_value( $managed_option );
		}

		return $values;
	}

	/**
	 * Set value of an option
	 *
	 * @param string|array|int|bool $value
	 * @param string|null $option
	 *
	 * @return $this
	 */
	private function set_option( $value, $option = null ) {
		if ( null === $option ) {
			$option = $this->get_managed_option();
		}

		$method = 'set_' . $option;

		if ( method_exists( $this, $method ) ) {
			$this->$method( $value );
		}

		return $this;
	}

	/**
	 * Set the options of this setting
	 *
	 * @param array $options
	 *
	 * @return $this
	 */
	public function set_options( array $options ) {
		foreach ( $options as $key => $value ) {
			if ( $this->has_managed_option( $key ) ) {
				$this->set_option( $value, $key );
			}
		}

		return $this;
	}

	/**
	 * Set a default value unless option is loaded from settings
	 *
	 * @param string|array|int|bool $value
	 * @param string|null $option
	 *
	 * @return $this
	 */
	public function set_default( $value, $option = null ) {
		if ( null === $option ) {
			$option = $this->get_managed_option();
		}

		$this->defaults[ $option ] = $value;

		if ( ! $this->is_user_set( $option ) ) {
			$this->set_option( $value, $option );
		}

		return $this;
	}

	/**
	 * Get the default value of an option if set
	 *
	 * @param string|null $option
	 *
	 * @return @param string|array|int|bool|null
	 */
	public function get_default( $option = null ) {
		if ( null === $option ) {
			$option = $this->get_managed_option();
		}

		return isset( $this->defaults[ $option ] ) ? $this->defaults[ $option ] : null;
	}

	/**
	 * Check if a option is user set
	 *
	 * @param string|null $option
	 *
	 * @return bool
	 */
	private function is_user_set( $option = null ) {
		if ( null === $option ) {
			$option = $this->get_managed_option();
		}

		return $this->has_managed_option( $option ) && null !== $this->get_value( $option );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * By default it will use the first managed option
	 *
	 * @return $this
	 */
	protected function set_name() {
		$this->name = $this->get_managed_option();

		return $this;
	}

	/**
	 * Render the output of self::create_header()
	 *
	 * @return false|string
	 */
	public function render_header() {
		if ( ! ( $this instanceof AC_Settings_HeaderInterface ) ) {
			return false;
		}

		/* @var AC_Settings_HeaderInterface $this */
		$view = $this->create_header_view();

		if ( ! ( $view instanceof AC_View ) ) {
			return false;
		}

		if ( null == $view->get_template() ) {
			$view->set_template( 'settings/header' );
		}

		if ( null == $view->setting ) {
			$view->set( 'setting', $this->get_name() );
		}

		return $view->render();
	}

	/**
	 * Render the output of self::create_view()
	 *
	 * @return false|string
	 */
	public function render() {
		$view = $this->create_view();

		if ( ! ( $view instanceof AC_View ) ) {
			return false;
		}

		$template = 'settings/section';

		// set default template
		if ( null === $view->get_template() ) {
			$view->set_template( $template );
		}

		// set default name
		if ( null === $view->get( 'name' ) ) {
			$view->set( 'name', $this->name );
		}

		// set default template for nested sections
		foreach ( (array) $view->sections as $section ) {
			if ( $section instanceof AC_View && null === $section->get_template() ) {
				$section->set_template( $template );
			}
		}

		return $view->render();
	}

	public function __toString() {
		return $this->render();
	}

}