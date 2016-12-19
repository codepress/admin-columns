<?php

/**
 * @since 2.4.7
 */
class AC_Column_WooCommercePlaceholder extends AC_Column
	implements AC_Column_PlaceholderInterface {

	public function __construct() {
		$this->set_type( 'column-wc_placeholder' );
		$this->set_label( __( 'WooCommerce', 'codepress-admin-columns' ) );
		$this->set_group( __( 'WooCommerce', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return false;
	}

	public function get_url() {
		return ac_get_site_url( 'woocommerce-columns' );
	}

	public function is_valid() {
		return in_array( $this->get_post_type(), array( 'product', 'shop_order', 'shop_coupon' ) );
	}

}