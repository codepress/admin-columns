<?php

class AC_Settings_Setting_PostLink extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $post_link_to;

	protected function define_options() {
		return array(
			'post_link_to' => 'edit_post',
		);
	}

	/**
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function format( AC_Value $value ) {
		if ( ! $value ) {
			return false;
		}

		switch ( $this->get_post_link_to() ) {

			case 'edit_post' :
				$link = get_edit_post_link( $value->get_id() );

				$value->add_wrapper( sprintf( '<a href="%s">', $link ), '</a>' );

				break;

			case 'view_post' :
				$link = get_permalink( $object_id );
				break;

			case 'edit_author' :
				$link = get_edit_user_link( ac_helper()->post->get_raw_field( 'post_author', $object_id ) );
				break;

			case 'view_author' :
				$link = get_author_posts_url( ac_helper()->post->get_raw_field( 'post_author', $object_id ) );
				break;

			default :
				$link = false;
		}

		return $value;
		return ac_helper()->html->link( $link, $value );
	}

	public function create_view() {
		$select = $this->create_element( 'select' )->set_options( $this->get_display_options() );

		$view = new AC_View( array(
			'label'   => __( 'Link To', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	private function get_display_options() {
		$options = array(
			'edit_post'   => __( 'Edit Post' ),
			'view_post'   => __( 'View Post' ),
			'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
			'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' ),
		);

		asort( $options );

		$options = array_merge( array( '' => __( 'None' ) ), $options );

		return $options;
	}

	/**
	 * @return string
	 */
	public function get_post_link_to() {
		return $this->post_link_to;
	}

	/**
	 * @param string $post_link_to
	 *
	 * @return bool
	 */
	public function set_post_link_to( $post_link_to ) {
		$this->post_link_to = $post_link_to;

		return true;
	}

}