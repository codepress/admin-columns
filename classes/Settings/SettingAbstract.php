<?php

abstract class AC_Settings_SettingAbstract {

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
	 * @var AC_Column
	 */
	protected $column;

	/**
	 * The main view
	 *
	 * @var AC_Settings_View
	 */
	private $view;

	/**
	 * @param AC_Column $column
	 */
	public function __construct( AC_Column $column ) {
		$this->column = $column;

		$this->set_managed_options();
		$this->set_name();
		$this->load_options();

		$this->view = new AC_Settings_View( array(
			'name' => $this->name,
		) );
	}

	/**
	 * Create a string representation of this setting
	 *
	 * @return AC_Settings_View
	 */
	public abstract function view();

	/**
	 * Set the options this field manages
	 *
	 */
	protected abstract function set_managed_options();

	protected function has_managed_options() {
		return ! empty( $this->managed_options );
	}

	public function has_managed_option( $option ) {
		return in_array( $option, $this->managed_options );
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
	 * @param string $name
	 * @param string $type
	 *
	 * @return AC_Settings_Form_ElementAbstract
	 */
	protected function create_element( $type = 'text', $name = null ) {
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
			return false;
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
	 * Load the current options from the column
	 *
	 */
	private function load_options() {
		$this->set_options( $this->column->get_options() );
	}

	/**
	 * Set the options of this setting
	 *
	 * @param array $options
	 */
	public function set_options( array $options ) {
		foreach ( $options as $key => $value ) {
			if ( in_array( $key, $this->managed_options ) ) {
				$this->set_option( $value, $key );
			}
		}
	}

	/**
	 * Set a default value unless option is loaded from settings
	 *
	 * @param string|array|int|bool $value
	 * @param string $option
	 *
	 * @return $this
	 */
	public function set_default( $value, $option = null ) {
		if ( null === $option ) {
			$option = $this->get_managed_option();
		}

		if ( ! $this->is_user_set( $option ) ) {
			$this->set_option( $value, $option );
		}

		return $this;
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
	 * @return AC_Settings_View
	 */
	public function get_view() {
		return $this->view;
	}

	public function __toString() {
		$view = $this->view();

		if ( ! ( $view instanceof AC_Settings_View ) ) {
			return '';
		}

		return $view->render();
	}

}