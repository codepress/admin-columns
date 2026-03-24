<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class WooCommerceOrdersNotice implements IntegrationNotice
{

    public function is_active(Screen $screen): bool
    {
        if ( ! class_exists('WooCommerce', false)) {
            return false;
        }

        $is_hpos_orders = 'woocommerce_page_wc-orders' === $screen->get_id();
        $is_legacy_orders = 'edit' === $screen->get_base() && 'shop_order' === $screen->get_post_type();

        return $is_hpos_orders || $is_legacy_orders;
    }

    public function get_slug(): string
    {
        return 'wc-orders';
    }

    public function get_integration_slug(): string
    {
        return 'ac-addon-woocommerce';
    }

    public function get_eyebrow(): string
    {
        return '';
    }

    public function get_title(): string
    {
        return __('Still opening each order to find shipping or payment details?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Inline edit order status, filter by payment method, and bulk update orders - all from this table.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-orders'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-orders-features'))->get_url();
    }

    public function get_extra_classes(): string
    {
        return '';
    }

}
