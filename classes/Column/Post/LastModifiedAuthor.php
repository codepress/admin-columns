<?php

/**
 * Column displaying information about the author of a post, such as the
 * author's display name, user ID and email address.
 *
 * @since 2.0
 */
class AC_Column_Post_LastModifiedAuthor extends AC_Column_Meta {

	public function __construct() {
		$this->set_type( 'column-last_modified_author' );
		$this->set_label( __( 'Last Modified Author', 'codepress-admin-columns' ) );
	}

	public function get_meta_key() {
		return '_edit_last';
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_User( $this ) );
	}

}