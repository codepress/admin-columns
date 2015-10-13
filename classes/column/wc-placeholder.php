<?php
/**
 * @since 2.4.7
 */
class CPAC_Column_WC_Placeholder extends CPAC_Column {

	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 		= 'column-wc_placeholder';
		$this->properties['label']	 		= __( 'WooCommerce', 'codepress-admin-columns' );
		$this->properties['is_pro_only']	= true;
		$this->properties['group']			= 'woocommerce';
	}

	public function apply_conditional() {
		return in_array( $this->storage_model->get_post_type(), array( 'product', 'shop_order', 'shop_coupon' ) );
	}

	public  function display_settings() {
		$this->display_settings_placeholder( 'https://www.admincolumns.com/woocommerce-columns/' );
	}
}