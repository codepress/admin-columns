<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.4.7
 */
class AC_Column_WooCommercePlaceholder extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-wc_placeholder';
		$this->properties['label'] = __( 'WooCommerce', 'codepress-admin-columns' );
		$this->properties['group'] = __( 'WooCommerce', 'codepress-admin-columns' );
	}

	public function apply_conditional() {
		return in_array( $this->get_post_type(), array( 'product', 'shop_order', 'shop_coupon' ) );
	}

	public function display_settings() {
		$this->settings()->placeholder_field( array( 'label' => $this->get_label, 'type' => $this->get_type(), 'url' => ac_get_site_url( 'woocommerce-columns' ) ) );
	}
}