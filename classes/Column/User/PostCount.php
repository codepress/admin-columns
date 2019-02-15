<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class PostCount extends Column {

	public function __construct() {
		$this->set_type( 'column-user_postcount' );
		$this->set_label( __( 'Post Count', 'codepress-admin-columns' ) );
	}

	/**
	 * Get count
	 * @since 2.0
	 *
	 * @param $user_id
	 *
	 * @return string
	 */
	public function get_count( $user_id ) {
		return ac_helper()->user->get_postcount( $user_id, $this->get_option( 'post_type' ) );
	}

	public function get_value( $user_id ) {
		$value = $this->get_empty_char();
		$count = $this->get_raw_value( $user_id );

		if ( $count > 0 ) {
			$link = add_query_arg( array( 'post_type' => $this->get_option( 'post_type' ), 'author' => $user_id ), admin_url( 'edit.php' ) );
			$value = ac_helper()->html->link( $link, $count );
		}

		return $value;
	}

	public function get_raw_value( $user_id ) {
		return $this->get_count( $user_id );
	}

	protected function register_settings() {
		$this->add_setting( new Settings\Column\PostType( $this ) );
	}

}