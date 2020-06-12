<?php

namespace AC\Column\User;

use AC\Column;

class Nicename extends Column {

	public function __construct() {
		$this->set_type( 'column-user_nicename' )
		     ->set_label( __( 'Author Slug', 'codepress-admin-columns' ) );
	}

	public function get_value( $user_id ) {
		$value = $this->get_raw_value( $user_id );

		if ( empty( $value ) ) {
			return $this->get_empty_char();
		}

		$url = get_author_posts_url( $user_id );

		if ( $url ) {
			$value = sprintf( '<a href="%s">%s</a>', $url, $value );
		}

		return $value;
	}

	public function get_raw_value( $user_id ) {
		return get_userdata( $user_id )->user_nicename;
	}

}