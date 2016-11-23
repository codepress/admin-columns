<?php

class AC_Settings_Setting_PostLink extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $post_link;

	protected function set_managed_options() {
		$this->managed_options = array( 'post_link_to' );
	}

	public function view() {

		$options = array(
			'edit_post'   => __( 'Edit Post' ),
			'view_post'   => __( 'View Post' ),
			'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
			'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' ),
		);

		// sorts when translated
		natcasesort( $options );

		$options = array_merge( array( '' => __( 'None' ) ), $options );

		$select = $this->create_element( 'post_link_to', 'select' )
		               ->set_options( $options );

		$view = new AC_Settings_View();
		$view->set( 'setting', $select )
		     ->set( 'label', __( 'Link To', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Page the posts should link to.', 'codepress-admin-columns' ) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_post_link_to() {
		return $this->post_link;
	}

	/**
	 * @param string $user
	 *
	 * @return $this
	 */
	public function set_post_link_to( $post_link ) {
		$this->post_link = $post_link;

		return $this;
	}

}