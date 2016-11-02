<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4.7
 */
class AC_Column_WooCommercePlaceholder extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-wc_placeholder' );
		$this->set_label( __( 'WooCommerce', 'codepress-admin-columns' ) );
		$this->set_group( __( 'WooCommerce', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		return false;
	}

	public function is_valid() {
		return in_array( $this->get_post_type(), array( 'product', 'shop_order', 'shop_coupon' ) );
	}

	public function display_settings() {
		$this->field_settings->placeholder( array( 'label' => $this->get_label, 'type' => $this->get_type(), 'url' => ac_get_site_url( 'woocommerce-columns' ) ) );
	}
}