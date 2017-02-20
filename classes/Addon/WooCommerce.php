<?php

class AC_Addon_WooCommerce extends AC_Addon {

	public function __construct() {

		$this
			->set_title( __( 'WooCommerce', 'codepress-admin-columns' ) )
			->set_description( __( 'Enhance the products, orders and coupons overviews with new columns and inline editing.', 'codepress-admin-columns' ) )
			->set_slug( 'cac-addon-woocommerce' )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/woocommerce.png' )
			->set_icon( $this->get_logo() )
			->set_link( ac_get_site_utm_url( 'woocommerce-columns', 'addon', 'woocommerce' ) );
	}

	public function is_plugin_active() {
		return class_exists( 'WooCommerce', false );
	}

	public function get_placeholder_column() {
		$column = new AC_Column_WooCommercePlaceholder();
		$column->set_addon( $this );

		return $column;
	}

}