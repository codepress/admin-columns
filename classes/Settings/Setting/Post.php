<?php

class AC_Settings_Setting_Post extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $post_property;

	/**
	 * @var string
	 */
	private $post_link_to;

	protected function set_name() {
		$this->name = 'post';
	}

	protected function define_options() {
		return array(
			'post_property_display',
			'post_link_to' => 'edit_post',
		);
	}

	/**
	 * @param int $post_id
	 *
	 * @return string
	 */
	public function format( $post_id ) {
		return ac_helper()->html->link( $this->get_post_link( $post_id ), $this->get_post_name( $post_id ) );
	}

	public function create_view() {
		$select = $this->create_element( 'select', 'post_property_display' );

		$select->set_attribute( 'data-refresh', 'column' )
		       ->set_options( $this->get_display_options() );

		$display_format = new AC_View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		$select = $this->create_element( 'select', 'post_link_to' )
		               ->set_options( $this->get_link_options() );

		$link_format = new AC_View( array(
			'label'   => __( 'Link To', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		$view = new AC_View( array(
			'label'    => __( 'Post', 'codepress-admin-columns' ),
			'sections' => array( $display_format, $link_format ),
		) );

		return $view;
	}

	/**
	 * @param int $post_id
	 *
	 * @return false|string
	 */
	private function get_post_link( $post_id ) {
		$link = false;

		switch ( $this->get_post_link_to() ) {
			case 'edit_post' :
				$link = get_edit_post_link( $post_id );
				break;
			case 'view_post' :
				$link = get_permalink( $post_id );
				break;
			case 'edit_author' :
				$link = get_edit_user_link( ac_helper()->post->get_raw_field( 'post_author', $post_id ) );
				break;
			case 'view_author' :
				$link = get_author_posts_url( ac_helper()->post->get_raw_field( 'post_author', $post_id ) );
				break;
		}

		return $link;
	}

	/**
	 * @param $post_id
	 *
	 * @return false|string
	 */
	private function get_post_name( $post_id ) {
		switch ( $this->get_post_property_display() ) {
			case 'author' :
				$user_id = ac_helper()->post->get_raw_field( 'post_author', $post_id );
				$label = ac_helper()->user->get_display_name( $user_id );
				break;

			case 'id' :
				$label = $post_id;
				break;

			default:
				$label = ac_helper()->post->get_raw_field( 'post_title', $post_id );
				break;
		}

		return $label;
	}

	private function get_display_options() {
		return array(
			'title'  => __( 'Title' ),
			'id'     => __( 'ID' ),
			'author' => __( 'Author' ),
		);
	}

	private function get_link_options() {
		return array(
			'edit_post'   => __( 'Edit Post' ),
			'view_post'   => __( 'View Post' ),
			'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
			'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' ),
		);
	}

	/**
	 * @return string
	 */
	public function get_post_property_display() {
		return $this->post_property;
	}

	/**
	 * @param string $post_property
	 *
	 * @return bool
	 */
	public function set_post_property_display( $post_property ) {
		$this->post_property = $post_property;

		return true;
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