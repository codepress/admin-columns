<?php

class AC_Settings_Setting_Password extends AC_Settings_SettingAbstract
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $password;

	protected function set_name() {
		$this->name = 'password';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'password' );
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_options( array(
			               ''     => __( 'Password', 'codepress-admin-column' ), // default
			               'text' => __( 'Plain text', 'codepress-admin-column' ),
		               ) );

		$view = new AC_View( array(
			'label'   => __( 'Display format', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_password() {
		return $this->password;
	}

	/**
	 * @param string $password
	 *
	 * @return $this
	 */
	public function set_password( $password ) {
		$this->password = $password;

		return $this;
	}

	/**
	 * @param $text
	 *
	 * @return bool|string
	 */
	public function format( $text ) {
		if ( 'text' == $this->get_password() ) {
			return $text;
		};
		$pwchar = '&#9679;';

		return $text ? str_pad( '', strlen( $text ) * strlen( $pwchar ), $pwchar ) : false;
	}

}