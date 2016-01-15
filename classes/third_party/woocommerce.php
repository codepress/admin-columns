<?php

function cpac_remove_webhook_from_post_types( $post_types ) {
	if ( class_exists( 'WooCommerce', false ) ) {
		if ( isset( $post_types['shop_webhook'] ) ) {
			unset( $post_types['shop_webhook'] );
		}
	}
	return $post_types;
}
add_filter( 'cac/post_types', 'cpac_remove_webhook_from_post_types' );