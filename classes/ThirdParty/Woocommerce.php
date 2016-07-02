<?php
defined( 'ABSPATH' ) or die();

class AC_ThirdParty_WooCommerce {

	public function __construct() {
		add_filter( 'cac/post_types', array( $this, 'remove_webhook_from_post_types' ) );
		add_filter( 'cac/grouped_columns', array( $this, 'place_woocommerce_on_top_of_group_list' ) );
	}

	public function remove_webhook_from_post_types( $post_types ) {
		if ( class_exists( 'WooCommerce', false ) ) {
			if ( isset( $post_types['shop_webhook'] ) ) {
				unset( $post_types['shop_webhook'] );
			}
		}
		return $post_types;
	}

	public function place_woocommerce_on_top_of_group_list( $grouped_columns ) {
		$label = __( 'WooCommerce', 'woocommerce' );

		if ( isset( $grouped_columns[ $label ] ) ) {
			$group[ $label ] = $grouped_columns[ $label ];
			unset( $grouped_columns[ $label ] );
			$grouped_columns = $group + $grouped_columns;
		}

		return $grouped_columns;
	}

}