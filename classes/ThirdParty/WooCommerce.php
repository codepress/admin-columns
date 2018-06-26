<?php

namespace AC\ThirdParty;

class WooCommerce {

	public function __construct() {
		add_filter( 'ac/post_types', array( $this, 'remove_webhook' ) );
	}

	public function remove_webhook( $post_types ) {
		if ( class_exists( 'WooCommerce', false ) ) {
			if ( isset( $post_types['shop_webhook'] ) ) {
				unset( $post_types['shop_webhook'] );
			}
		}

		return $post_types;
	}

}