<?php

declare(strict_types=1);

namespace AC\Admin\MenuGroupFactory;

use AC\Admin\MenuGroupFactory;
use AC\Admin\Type\MenuGroup;
use AC\PostType;
use AC\TableScreen;
use AC\Taxonomy;

class WooCommerceGroups implements MenuGroupFactory
{

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
