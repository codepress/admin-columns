<?php

class AC_Settings_Column_Password extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

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
	 * @param AC_ValueFormatter $value_formatter
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_ValueFormatter $value_formatter ) {
		if ( ! $this->get_password() ) {
			$pwchar = '&#9679;';
			$value_formatter->value = $value_formatter->value
				? str_pad( '', strlen( $value_formatter->value ) * strlen( $pwchar ), $pwchar )
				: false;
		}

		return $value_formatter;
	}

}