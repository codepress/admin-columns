<?php

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class CPAC_Column_Post_Author_Name extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {
		parent::init();

		$this->properties['type'] = 'column-author_name';
		$this->properties['label'] = __( 'Display Author As', 'codepress-admin-columns' );
		$this->properties['is_cloneable'] = true;
		$this->properties['object_property'] = 'post_author';

		// Options
		$this->options['display_author_as'] = '';
		$this->options['user_link_to'] = '';
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		$value = '';

		if ( $user_id = $this->get_raw_value( $post_id ) ) {
			$value = $this->get_display_name( $user_id );
		}

		switch ( $this->get_option( 'user_link_to' ) ) {
			case 'edit_user':
				$link = get_edit_user_link( $user_id );
				break;
			case 'view_user_posts':
				$link = add_query_arg( array(
					'post_type' => get_post_field( 'post_type', $post_id ),
					'author'    => get_the_author_meta( 'ID' )
				), 'edit.php' );
				break;
			case 'view_author':
				$link = get_author_posts_url( $user_id );
				break;
			default:
				$link = '';
		}

		if ( $link ) {
			$value = '<a href="' . esc_url( $link ) . '">' . $value . '</a>';
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_author', $post_id );
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	public function display_settings() {
		$this->display_field_user_format();
		$this->display_field_user_link_to();
	}

	/**
	 * Display settings field for the page the posts should link to
	 *
	 * @since 2.4.7
	 */
	public function display_field_user_link_to() {
		$this->display_field_select(
			'user_link_to',
			__( 'Link To', 'codepress-admin-columns' ),
			array(
				''                => __( 'None' ),
				'edit_user'       => __( 'Edit User Profile' ),
				'view_user_posts' => __( 'View User Posts' ),
				'view_author'     => __( 'View Public Author Page', 'codepress-admin-columns' )
			),
			__( 'Page the author name should link to.', 'codepress-admin-columns' )
		);
	}

}