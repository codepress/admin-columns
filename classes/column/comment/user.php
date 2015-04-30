<?php
/**
 * @since 2.4.2
 */
class CPAC_Column_Comment_User extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type']	 = 'column-user';
		$this->properties['label']	 = __( 'User', 'cpac' );
	}

	public function get_value( $id ) {
		$user_id = $this->get_raw_value( $id );

		if ( ! $user_id ) {
			return false;
		}

		$display_name = $this->get_display_name( $user_id );
		if ( $edit_link = get_edit_profile_url( $user_id ) ) {
			$display_name = '<a href="' . $edit_link . ' ">' . $display_name . '</a>';
		}
		return $display_name;
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );
		return $comment->user_id;
	}
}