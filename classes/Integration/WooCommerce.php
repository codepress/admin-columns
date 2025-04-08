<?php

namespace AC\Integration;

use AC\Integration;
use AC\Screen;
use AC\TableScreen;
use AC\Type\Url\Site;

final class WooCommerce extends Integration
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-woocommerce',
            __('WooCommerce', 'codepress-admin-columns'),
            'assets/images/addons/woocommerce-icon.png',
            sprintf(
                '%s %s',
                sprintf(
                    __('Integrates %s with Admin Columns.', 'codepress-admin-columns'),
                    __('WooCommerce', 'codepress-admin-columns')
                ),
                __(
                    'Display, inline- and bulk-edit, smart filter and sort your Products, Variations, Orders and Customers',
                    'codepress-admin-columns'
                )
            ),
            null,
            new Site(Site::PAGE_ADDON_WOOCOMMERCE)
        );
    }

    public function is_plugin_active(): bool
    {
        return class_exists('WooCommerce', false);
    }

    private function get_post_types(): array
    {
        return [
            'product',
            'shop_order',
            'shop_coupon',
            'shop_subscription',
        ];
    }

    public function show_notice(Screen $screen): bool
    {
        $is_user_screen = 'users' === $screen->get_id();
        $is_post_screen = 'edit' === $screen->get_base()
                          && in_array($screen->get_post_type(), $this->get_post_types(), true);
        $is_order_screen = 'woocommerce_page_wc-orders' === $screen->get_id();

        return $is_user_screen || $is_post_screen || $is_order_screen;
    }

    private function get_list_keys(): array
    {
        $keys = [
            'wp-users',
            'wc_order',
            'wc_order_subscription',
        ];

        foreach ($this->get_post_types() as $post_type) {
            $keys[] = $post_type;
        }

        return $keys;
    }

    public function show_placeholder(TableScreen $table_screen): bool
    {
        return in_array((string)$table_screen->get_id(), $this->get_list_keys(), true);
    }

}