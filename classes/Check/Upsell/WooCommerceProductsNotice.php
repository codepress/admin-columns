<?php

declare(strict_types=1);

namespace AC\Check\Upsell;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class WooCommerceProductsNotice implements UpsellNotice
{

    public function is_active(Screen $screen): bool
    {
        if ( ! class_exists('WooCommerce', false)) {
            return false;
        }

        return 'edit' === $screen->get_base() && 'product' === $screen->get_post_type();
    }

    public function get_slug(): string
    {
        return 'wc-products';
    }

    public function get_integration_slug(): string
    {
        return 'ac-addon-woocommerce';
    }

    public function get_icon(): string
    {
        return '🛒';
    }

    public function get_eyebrow(): string
    {
        return __('Admin Columns Pro for WooCommerce', 'codepress-admin-columns');
    }

    public function get_title(): string
    {
        return __('Still opening each product to check stock or pricing?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Inline edit prices and stock, filter by any field, and bulk update products - all from this table.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return sprintf('%s - %s', __('Upgrade', 'codepress-admin-columns'), '€79/year');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-features'))->get_url();
    }

}
