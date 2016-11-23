<?php

class AC_Settings_Setting_User extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $user;

	protected function set_managed_options() {
		$this->managed_options = array( 'user' );
	}

	public function view() {
		$options = array(
			'display_name'    => __( 'Display Name', 'codepress-admin-columns' ),
			'first_name'      => __( 'First Name', 'codepress-admin-columns' ),
			'last_name'       => __( 'Last Name', 'codepress-admin-columns' ),
			'nickname'        => __( 'Nickname', 'codepress-admin-columns' ),
			'user_login'      => __( 'User Login', 'codepress-admin-columns' ),
			'user_email'      => __( 'User Email', 'codepress-admin-columns' ),
			'ID'              => __( 'User ID', 'codepress-admin-columns' ),
			'first_last_name' => __( 'First and Last Name', 'codepress-admin-columns' ),
		);

		// resort for possible translations
		natcasesort( $options );

		$select = $this->create_element( 'select' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $options );

		$view = $this->get_view();
		$view->set( 'setting', $select )
		     ->set( 'label', __( 'Display Format', 'codepress-admin-columns' ) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_user() {
		return $this->user;
	}

	/**
	 * @param string $user
	 *
	 * @return $this
	 */
	public function set_user( $user ) {
		$this->user = $user;

		return $this;
	}

}