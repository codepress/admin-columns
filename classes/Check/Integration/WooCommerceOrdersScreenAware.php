<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;

trait WooCommerceOrdersScreenAware
{

    private function is_woocommerce_orders_screen(Screen $screen): bool
    {
        $is_hpos_orders = 'woocommerce_page_wc-orders' === $screen->get_id();
        $is_legacy_orders = 'edit' === $screen->get_base() && 'shop_order' === $screen->get_post_type();

        return $is_hpos_orders || $is_legacy_orders;
    }

}
