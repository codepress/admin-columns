<?php

class AC_Addon_WooCommerce extends AC_Addon {

	public function __construct() {

		$this
			->set_title( __( 'WooCommerce', 'codepress-admin-columns' ) )
			->set_description( __( 'Enhance the products, orders and coupons overviews with new columns and inline editing.', 'codepress-admin-columns' ) )
			->set_slug( 'cac-addon-woocommerce' )
			->set_image_url( AC()->get_plugin_url() . 'assets/images/addons/woocommerce.png' );
	}

	public function is_plugin_active() {
		return class_exists( 'WooCommerce', false );
	}

	public function is_addon_active() {
		return class_exists( 'CPAC_Addon_WC', false );
	}

	public function get_placeholder_column() {
		$column = new AC_Column_WooCommercePlaceholder( $this );

		return $column->set_url( ac_get_site_url( 'woocommerce-columns' ) );
	}

}