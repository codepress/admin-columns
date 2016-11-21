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
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * @return array
	 */
	public function get_option( $key ) {
		return isset( $this->options[ $key ] ) ? $this->options[ $key ] : null;
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