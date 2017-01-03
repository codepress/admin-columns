<?php

class AC_Settings_Setting_Password extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $password;

	protected function define_options() {
		return array( 'password' );
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
	 * @return true
	 */
	public function set_password( $password ) {
		$this->password = $password;

		return true;
	}

	/**
	 * @param $text
	 *
	 * @return bool|string
	 */
	public function format( $text ) {

		if ( ! $this->get_password() ) {
			$pwchar = '&#9679;';
			$text = $text ? str_pad( '', strlen( $text ) * strlen( $pwchar ), $pwchar ) : false;
		}

		return $text;
	}

}