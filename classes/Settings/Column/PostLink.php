<?php

class AC_Settings_Column_PostLink extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var string
	 */
	protected $post_link_to;

	protected function define_options() {
		return array(
			'post_link_to' => 'edit_post',
		);
	}

	public function format( $value, $original_value ) {
		$id = $original_value;

		switch ( $this->get_post_link_to() ) {
			case 'edit_post' :
				$link = get_edit_post_link( $id );

				break;
			case 'view_post' :
				$link = get_permalink( $id );

				break;
			case 'edit_author' :
				$link = get_edit_user_link( ac_helper()->post->get_raw_field( 'post_author', $id ) );

				break;
			case 'view_author' :
				$link = get_author_posts_url( ac_helper()->post->get_raw_field( 'post_author', $id ) );

				break;
			default :
				$link = false;
		}

		if ( $link ) {
			$value = ac_helper()->html->link( $link, $value );
		}

		return $value;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )->set_options( $this->get_display_options() );

		$view = new AC_View( array(
			'label'   => __( 'Link To', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	protected function get_display_options() {
		// Default options
		$options = array(
			''            => __( 'None' ),
			'edit_post'   => __( 'Edit Post' ),
			'view_post'   => __( 'View Post' ),
			'edit_author' => __( 'Edit Post Author', 'codepress-admin-columns' ),
			'view_author' => __( 'View Public Post Author Page', 'codepress-admin-columns' ),
		);

		if ( $this->column instanceof AC_Column_RelationInterface ) {
			$relation_options = array(
				'edit_post'   => _x( 'Edit %s', 'post' ),
				'view_post'   => _x( 'View %s', 'post' ),
				'edit_author' => _x( 'Edit %s Author', 'post', 'codepress-admin-columns' ),
				'view_author' => _x( 'View Public %s Author Page', 'post', 'codepress-admin-columns' ),
			);

			$label = $this->column->get_relation_object()->get_labels()->singular_name;

			foreach ( $relation_options as $k => $option ) {
				$options[ $k ] = sprintf( $option, $label );
			}
		}

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