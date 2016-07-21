<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4.2
 */
class AC_Column_Comment_User extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user';
		$this->properties['label'] = __( 'User', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$user_id = $this->get_raw_value( $id );

		$display_name = $this->format->user( $user_id );
		if ( $edit_link = get_edit_profile_url( $user_id ) ) {
			$display_name = '<a href="' . esc_attr( $edit_link ) . ' ">' . esc_html( $display_name ) . '</a>';
		}

		return $display_name;
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->user_id;
	}

	public function display_settings() {
		$this->field_settings->user();
	}

}