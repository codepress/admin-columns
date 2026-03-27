<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class WooCommerceProductsFilterNotice implements IntegrationNotice, UsageAwareNotice
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
        return 'wc-products-filter';
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
        return __('Need to narrow down your product list?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('With Pro, filter products by any property - stock level, attribute, custom field, price range. Save filters as segments and share them with your team.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-filter'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-filter-features'))->get_url();
    }

    public function get_extra_classes(): string
    {
        return '';
    }

    public function is_usage_detected(): bool
    {
        return ! empty($_GET['product_cat']) || ! empty($_GET['product_type']) || ! empty($_GET['stock_status']);
    }

    public function get_delay_days(): int
    {
        return 28;
    }

}
