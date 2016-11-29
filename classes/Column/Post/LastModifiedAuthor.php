<?php
defined( 'ABSPATH' ) or die();

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class AC_Column_Post_LastModifiedAuthor extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-last_modified_author' );
		$this->set_label( __( 'Last Modified Author', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		return $this->get_setting( 'user' )->format( $this->get_raw_value( $post_id ) );
	}

	public function get_raw_value( $post_id ) {
		return get_post_meta( $post_id, '_edit_last', true );
	}

	public function register_settings() {
		parent::register_settings();

		$this->add_setting( new AC_Settings_Setting_User( $this ) );
	}

}