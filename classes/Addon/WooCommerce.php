<?php

class AC_Addon_WooCommerce extends AC_Addon {

	public function __construct() {
		parent::__construct( 'cac-addon-woocommerce' );

		$this
			->set_title( __( 'WooCommerce', 'codepress-admin-columns' ) )
			->set_description( __( 'Enhance the products, orders and coupons overviews with new columns and inline editing.', 'codepress-admin-columns' ) )
			->set_logo( AC()->get_plugin_url() . 'assets/images/addons/woocommerce.png' )
			->set_icon( $this->get_logo() )
			->set_link( ac_get_site_utm_url( 'woocommerce-columns', 'addon', 'woocommerce' ) )
			->add_plugin( 'woocommerce' );
	}

	public function get_placeholder_column() {
		$column = new AC_Column_WooCommercePlaceholder();
		$column->set_addon( $this );

		return $column;
	}

}