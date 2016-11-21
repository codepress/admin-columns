<?php

class AC_Settings_Column {

	/**
	 * @var AC_Settings_SettingAbstract[]
	 */
	private $settings = array();

	/**
	 * Stored options for this columns
	 *
	 * @var array
	 */
	private $options = array();

	public function add( AC_Settings_SettingAbstract $setting ) {
		$this->settings[ $setting->get_id() ] = $setting;

		return $this;
	}

	/**
	 * @param $id
	 *
	 * @return AC_Settings_SettingAbstract|false
	 */
	public function get( $id ) {
		return isset( $this->settings[ $id ] ) ? $this->settings[ $id ] : false;
	}

	/**
	 * Magic accessor for self::$settings
	 *
	 * @param string $id
	 *
	 * @return AC_Settings_SettingAbstract|false
	 */
	public function __get( $id ) {
		return $this->get( $id );
	}

	public function get_all() {
		return $this->settings;
	}

	public function render() {
		$views = array();

		foreach ( $this->settings as $setting ) {
			$views[] = $setting->view()->render();
		}

		return implode( "\n", array_filter( $views ) );
	}

	/**
	 * @param string $field_type (e.g. label, width, type, before_after )
	 *
	 * @return bool
	 */
	public function get_value( $property ) {
		foreach ( $this->settings as $setting ) {
			if ( $setting->has_property( $property ) ) {
				return $setting->get_value( $property );
			}
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * @param array $options
	 *
	 * @return $this
	 */
	public function set_options( $options ) {
		$this->options = $options;

		return $this;
	}

}