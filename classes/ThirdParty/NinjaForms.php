<?php

namespace AC\ThirdParty;

class NinjaForms {

	public function __construct() {
		add_filter( 'ac/post_types', array( $this, 'remove_nf_sub' ) );
	}

	public function remove_nf_sub( $post_types ) {
		if ( class_exists( 'Ninja_Forms', false ) ) {
			if ( isset( $post_types['nf_sub'] ) ) {
				unset( $post_types['nf_sub'] );
			}
		}

		return $post_types;
	}

}