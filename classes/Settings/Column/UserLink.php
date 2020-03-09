<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class UserLink extends Settings\Column
	implements Settings\FormatValue {

	/**
	 * @var string
	 */
	protected $user_link_to;

	protected function define_options() {
		return [
			'user_link_to' => 'edit_user',
		];
	}

	public function format( $value, $user_id ) {
		$link = false;

		switch ( $this->get_user_link_to() ) {
			case 'edit_user' :
				$link = get_edit_user_link( $user_id );

				break;
			case 'view_user_posts' :
				$link = add_query_arg( [
					'post_type' => $this->column->get_post_type(),
					'author'    => $user_id,
				], 'edit.php' );

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

		if ( $link ) {
			$value = ac_helper()->html->link( $link, $value );
		}

		return $value;
	}

	public function create_view() {
		$select = $this->create_element( 'select' )->set_options( $this->get_display_options() );

		$view = new View( [
			'label'   => __( 'Link To', 'codepress-admin-columns' ),
			'setting' => $select,
		] );

		return $view;
	}

	protected function get_display_options() {
		$options = [
			'edit_user'       => __( 'Edit User Profile', 'codepress-admin-columns' ),
			'email_user'      => __( 'User Email', 'codepress-admin-columns' ),
			'view_user_posts' => __( 'View User Posts', 'codepress-admin-columns' ),
			'view_author'     => __( 'View Public Author Page', 'codepress-admin-columns' ),
		];

		// resort for possible translations
		natcasesort( $options );

		$options = array_merge( [ '' => __( 'None' ) ], $options );

		return $options;
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

}