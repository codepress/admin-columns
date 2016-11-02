<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4.2
 */
class AC_Column_Comment_User extends AC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user';
		$this->properties['label'] = __( 'User', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$user_id = $this->get_raw_value( $id );

		return ac_helper()->html->link( get_edit_profile_url( $user_id ), $this->format->user( $user_id ) );

	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->user_id;
	}

	public function display_settings() {
		$this->field_settings->user();
	}

}