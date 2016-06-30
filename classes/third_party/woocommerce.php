<?php
defined( 'ABSPATH' ) or die();

function cpac_remove_webhook_from_post_types( $post_types ) {
	if ( class_exists( 'WooCommerce', false ) ) {
		if ( isset( $post_types['shop_webhook'] ) ) {
			unset( $post_types['shop_webhook'] );
		}
	}
	return $post_types;
}
add_filter( 'cac/post_types', 'cpac_remove_webhook_from_post_types' );

/**
 * place WooCommerce on top of the grouped list
 */
function cpac_place_woocommerce_on_top_of_group_list( $grouped_columns ) {
	$label = __( 'WooCommerce', 'woocommerce' );

	if ( isset( $grouped_columns[ $label ] ) ) {
		$group[ $label ] = $grouped_columns[ $label ];
		unset( $grouped_columns[ $label ] );
		$grouped_columns = $group + $grouped_columns;
	}

	return $grouped_columns;
}

add_filter( 'cac/grouped_columns', 'cpac_place_acf_on_top_of_group_list' );