<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class User extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	private $display_author_as;

	protected function set_name() {
		$this->name = 'user';
	}

	protected function define_options() {
		return array( 'display_author_as' );
	}

	public function get_dependent_settings() {
		$settings = array();

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

		$view = new View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
			'for'     => $select->get_id(),
		) );

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
		$options = array(
			'display_name'    => __( 'Display Name', 'codepress-admin-columns' ),
			'first_name'      => __( 'First Name', 'codepress-admin-columns' ),
			'last_name'       => __( 'Last Name', 'codepress-admin-columns' ),
			'nickname'        => __( 'Nickname', 'codepress-admin-columns' ),
			'user_login'      => __( 'User Login', 'codepress-admin-columns' ),
			'user_email'      => __( 'User Email', 'codepress-admin-columns' ),
			'ID'              => __( 'User ID', 'codepress-admin-columns' ),
			'first_last_name' => __( 'First and Last Name', 'codepress-admin-columns' ),
			'user_nicename'   => __( 'User Nicename', 'codepress-admin-columns' ),
			'roles'           => __( 'Roles', 'codepress-admin-columns' ),
		);

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