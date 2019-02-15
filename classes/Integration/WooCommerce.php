<?php

namespace AC\Integration;

use AC\Integration;
use AC\ListScreen;
use AC\ListScreenPost;
use AC\Screen;

final class WooCommerce extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-woocommerce/ac-addon-woocommerce.php',
			__( 'WooCommerce', 'codepress-admin-columns' ),
			'assets/images/addons/woocommerce.png',
			__( 'Enhance the products, orders and coupons overviews with new columns and inline editing.', 'codepress-admin-columns' ),
			null,
			'woocommerce-columns'
		);
	}

	public function is_plugin_active() {
		return class_exists( 'WooCommerce', false );
	}

	private function get_post_types() {
		return array(
			'product',
			'shop_order',
			'shop_coupon',
		);
	}

	public function show_notice( Screen $screen ) {
		$is_user_screen = 'users' === $screen->get_id();
		$is_post_screen = 'edit' === $screen->get_base()
		                  && in_array( $screen->get_post_type(), $this->get_post_types() );

		return $is_user_screen || $is_post_screen;
	}

	public function show_placeholder( ListScreen $list_screen ) {
		$is_user_screen = $list_screen instanceof ListScreen\User;
		$is_post_screen = $list_screen instanceof ListScreenPost
		                  && in_array( $list_screen->get_post_type(), $this->get_post_types() );

		return $is_user_screen || $is_post_screen;
	}

}