<?php

class AC_ThirdParty_WooCommerce {

	public function __construct() {
		add_filter( 'cac/post_types', array( $this, 'remove_webhook_from_post_types' ) );
	}

	public function remove_webhook_from_post_types( $post_types ) {
		if ( class_exists( 'WooCommerce', false ) ) {
			if ( isset( $post_types['shop_webhook'] ) ) {
				unset( $post_types['shop_webhook'] );
			}
		}

		return $post_types;
	}

}