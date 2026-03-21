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
        return __('Inline edit prices and stock, filter by any field, bulk update hundreds of products — all from this screen.', 'codepress-admin-columns');
    }

    public function get_features(): array
    {
        return [
            __('Inline editing', 'codepress-admin-columns'),
            __('Smart filters', 'codepress-admin-columns'),
            __('Bulk actions', 'codepress-admin-columns'),
            __('CSV export', 'codepress-admin-columns'),
        ];
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See Pro features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-features'))->get_url();
    }

}
