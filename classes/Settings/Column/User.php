<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class User extends Settings\Column implements Settings\FormatValue {

	const NAME = 'user';

	const PROPERTY_DISPLAY_NAME = 'display_name';
	const PROPERTY_EMAIL = 'user_email';
	const PROPERTY_FULL_NAME = 'first_last_name';
	const PROPERTY_FIRST_NAME = 'first_name';
	const PROPERTY_ID = 'ID';
	const PROPERTY_LAST_NAME = 'last_name';
	const PROPERTY_LOGIN = 'user_login';
	const PROPERTY_NICENAME = 'user_nicename';
	const PROPERTY_NICKNAME = 'nickname';
	const PROPERTY_ROLES = 'roles';

	/**
	 * @var string
	 */
	private $display_author_as;

	protected function set_name() {
		$this->name = self::NAME;
	}

	protected function define_options() {
		return [ 'display_author_as' ];
	}

	public function get_dependent_settings() {
		$settings = [];

		$settings[] = new Settings\Column\UserLink( $this->column );

		return $settings;
	}

	/**
	 * @return View
	 */
	public function create_view() {
		$select = $this->create_element( 'select', 'display_author_as' )
		               ->set_attribute( 'data-label', 'update' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$view = new View( [
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
			'for'     => $select->get_id(),
		] );

		return $view;
	}

	/**
	 * @param int $user_id
	 *
	 * @return false|string
	 */
	public function get_user_name( $user_id ) {
		return ac_helper()->user->get_display_name( $user_id, $this->get_display_author_as() );
	}

	/**
	 * @return array
	 */
	protected function get_display_options() {
		$options = [
			self::PROPERTY_DISPLAY_NAME => __( 'Display Name', 'codepress-admin-columns' ),
			self::PROPERTY_FIRST_NAME   => __( 'First Name', 'codepress-admin-columns' ),
			self::PROPERTY_FULL_NAME    => __( 'Full Name', 'codepress-admin-columns' ),
			self::PROPERTY_LAST_NAME    => __( 'Last Name', 'codepress-admin-columns' ),
			self::PROPERTY_NICKNAME     => __( 'Nickname', 'codepress-admin-columns' ),
			self::PROPERTY_ROLES        => __( 'Roles', 'codepress-admin-columns' ),
			self::PROPERTY_LOGIN        => __( 'User Login', 'codepress-admin-columns' ),
			self::PROPERTY_EMAIL        => __( 'User Email', 'codepress-admin-columns' ),
			self::PROPERTY_ID           => __( 'User ID', 'codepress-admin-columns' ),
			self::PROPERTY_NICENAME     => __( 'User Nicename', 'codepress-admin-columns' ),
		];

		// resort for possible translations
		natcasesort( $options );

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_display_author_as() {
		return $this->display_author_as;
	}

	/**
	 * @param string $display_author_as
	 *
	 * @return bool
	 */
	public function set_display_author_as( $display_author_as ) {
		$this->display_author_as = $display_author_as;

		return true;
	}

	public function format( $value, $original_value ) {
		return $this->get_user_name( $value );
	}

}