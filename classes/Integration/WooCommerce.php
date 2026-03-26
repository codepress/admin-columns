<?php

namespace AC\Integration;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\Screen;
use AC\TableScreen;
use AC\Taxonomy;
use AC\Type\Integration;
use AC\Type\Url\Site;

final class WooCommerce extends Integration implements MenuGroupFactory
{

    public function __construct()
    {
        parent::__construct(
            'ac-addon-woocommerce',
            __('WooCommerce', 'codepress-admin-columns'),
            'assets/images/addons/woocommerce-icon.png',
            __(
                'Manage products, orders, coupons, and subscriptions from one screen. Bulk edit prices, stock, and SKUs. Filter orders by any field, then export the results to CSV in one click.',
                'codepress-admin-columns'
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

    public function create(TableScreen $table_screen): ?MenuGroup
    {
        $table_id = (string)$table_screen->get_id();

        if (
            $table_id === 'wc_order' ||
            $table_id === 'wc_order_subscription' ||
            $table_id === 'shop_subscription'
        ) {
            return new MenuGroup('woocommerce', __('WooCommerce'), 13, 'cpacicon-woo');
        }

        if ($table_screen instanceof PostType) {
            $post_type = (string)$table_screen->get_post_type();

            if (in_array($post_type, ['product', 'shop_order', 'product_variation'], true)) {
                return new MenuGroup('woocommerce', __('WooCommerce'), 13, 'cpacicon-woo');
            }

            if ('shop_coupon' === $post_type) {
                return new MenuGroup('woocommerce', __('WooCommerce'), 14, 'cpacicon-woo');
            }
        }

        if ($table_screen instanceof Taxonomy) {
            $taxonomy = (string)$table_screen->get_taxonomy();

            if (in_array($taxonomy, ['product_tag', 'product_cat'], true)) {
                return new MenuGroup('woocommerce-taxonomy', __('WooCommerce Taxonomies'), 14, 'cpacicon-woo');
            }

            if (function_exists('taxonomy_is_product_attribute') && taxonomy_is_product_attribute($taxonomy)) {
                return new MenuGroup('woocommerce-attributes', __('WooCommerce Attributes'), 14, 'cpacicon-woo');
            }
        }

        return null;
    }

}