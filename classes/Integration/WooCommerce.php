<?php

namespace AC\Integration;

use AC\Integration;
use AC\ListScreen;
use AC\ListScreenPost;
use AC\Screen;
use AC\Type\Url\Site;

final class WooCommerce extends Integration {

	public function __construct() {
		parent::__construct(
			'ac-addon-woocommerce/ac-addon-woocommerce.php',
			__( 'WooCommerce', 'codepress-admin-columns' ),
			'assets/images/addons/woocommerce-icon.png',
			sprintf(
				'%s %s',
				sprintf( __( 'Integrates %s with Admin Columns.', 'codepress-admin-columns' ), __( 'WooCommerce', 'codepress-admin-columns' ) ),
				__( 'Display, inline- and bulk-edit, smart filter and sort your Products, Variations, Orders and Customers', 'codepress-admin-columns' )
			),
			null,
			new Site( Site::PAGE_ADDON_WOOCOMMERCE )
		);
	}

	public function is_plugin_active(): bool
    {
		return class_exists( 'WooCommerce', false );
	}

	private function get_post_types() {
		return [
			'product',
			'shop_order',
			'shop_coupon',
		];
	}

	public function show_notice( Screen $screen ): bool
    {
		$is_user_screen = 'users' === $screen->get_id();
		$is_post_screen = 'edit' === $screen->get_base()
		                  && in_array( $screen->get_post_type(), $this->get_post_types() );

		return $is_user_screen || $is_post_screen;
	}

	public function show_placeholder( ListScreen $list_screen ): bool
    {
		$is_user_screen = $list_screen instanceof ListScreen\User;
		$is_post_screen = $list_screen instanceof ListScreenPost
		                  && in_array( $list_screen->get_post_type(), $this->get_post_types() );

		return $is_user_screen || $is_post_screen;
	}

}