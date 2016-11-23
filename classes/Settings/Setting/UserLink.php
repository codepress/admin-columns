<?php

class AC_Settings_Setting_UserLink extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $user_link;

	protected function set_managed_options() {
		$this->managed_options = array( 'user_link_to' );
	}

	public function view() {
		$options = array(
			'edit_user'       => __( 'Edit User Profile' ),
			'email_user'      => __( 'User Email' ),
			'view_user_posts' => __( 'View User Posts' ),
			'view_author'     => __( 'View Public Author Page', 'codepress-admin-columns' ),
		);

		// resort for possible translations
		natcasesort( $options );

		$options = array_merge( array( '' => __( 'None' ) ), $options );

		$select = $this->create_element( 'select' )
		               ->set_options( $options );

		$view = $this->get_view();
		$view->set( 'setting', $select )
		     ->set( 'label', __( 'Link To', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Page the author name should link to.', 'codepress-admin-columns' ) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_user_link_to() {
		return $this->user_link;
	}

	/**
	 * @param string $user
	 *
	 * @return $this
	 */
	public function set_user_link_to( $user_link ) {
		$this->user_link = $user_link;

		return $this;
	}

}