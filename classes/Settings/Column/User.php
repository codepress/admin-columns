<?php

class AC_Settings_Column_User extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var string
	 */
	private $display_author_as;

	/**
	 * @var string
	 */
	private $user_link_to;

	protected function set_name() {
		$this->name = 'user';
	}

	protected function define_options() {
		return array( 'display_author_as', 'user_link_to' );
	}

	/**
	 * @return AC_View
	 */
	public function create_view() {
		$select = $this->create_element( 'select', 'display_author_as' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_display_options() );

		$display_format = new AC_View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
			'for'     => $select->get_id(),
		) );

		$select = $this->create_element( 'select', 'user_link_to' )
		               ->set_attribute( 'data-refresh', 'column' )
		               ->set_options( $this->get_link_options() );

		$link_format = new AC_View( array(
			'label'   => __( 'Link To', 'codepress-admin-columns' ),
			'setting' => $select,
			'for'     => $select->get_id(),
		) );

		$view = new AC_View( array(
			'label'    => __( 'User', 'codepress-admin-columns' ),
			'sections' => array( $display_format, $link_format ),
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
	 * @param $user_id
	 *
	 * @return bool|string
	 */
	private function get_user_link( $user_id ) {
		$link = false;

		switch ( $this->get_user_link_to() ) {

			case 'edit_user' :
				$link = get_edit_user_link( $user_id );

				break;
			case 'view_user_posts' :
				$link = add_query_arg( array(
					'post_type' => $this->column->get_post_type(),
					'author'    => get_the_author_meta( 'ID' ),
				), 'edit.php' );

				break;
			case 'view_author' :
				$link = get_author_posts_url( $user_id );

				break;
			case 'email_user' :
				if ( $email = get_the_author_meta( 'email', $user_id ) ) {
					$link = 'mailto:' . $email;
				}

				break;
		}

		return $link;
	}

	/**
	 * @return array
	 */
	private function get_display_options() {
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

		return $options;
	}

	/**
	 * @return array
	 */
	private function get_link_options() {
		$options = array(
			'edit_user'       => __( 'Edit User Profile' ),
			'email_user'      => __( 'User Email' ),
			'view_user_posts' => __( 'View User Posts' ),
			'view_author'     => __( 'View Public Author Page', 'codepress-admin-columns' ),
		);

		// resort for possible translations
		natcasesort( $options );

		$options = array_merge( array( '' => __( 'None' ) ), $options );

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

	/**
	 * @return string
	 */
	public function get_user_link_to() {
		return $this->user_link_to;
	}

	/**
	 * @param string $user_link_to
	 *
	 * @return bool
	 */
	public function set_user_link_to( $user_link_to ) {
		$this->user_link_to = $user_link_to;

		return true;
	}

	public function format( $value, $original_value ) {
		return ac_helper()->html->link( $this->get_user_link( $value ), $this->get_user_name( $value ) );
	}

}