<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;
use AC\WooCommerce;

class WooCommerceProductsSearchNotice implements IntegrationNotice, UsageAwareNotice
{

    public function is_usage_detected(): bool
    {
        return ! empty($_GET['s']);
    }

    public function is_active(Screen $screen): bool
    {
        if ( ! WooCommerce::is_active()) {
            return false;
        }

        return 'edit' === $screen->get_base() && 'product' === $screen->get_post_type();
    }

    public function get_slug(): string
    {
        return 'wc-products-search';
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
        return __("Can't find the right products?", 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Pro lets you search across any product property - SKU, attributes, custom fields, even variation data. Save searches as reusable segments and switch between them in one click.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-search'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-search-features'))->get_url();
    }

    public function get_extra_classes(): string
    {
        return '';
    }

    public function get_delay_days(): int
    {
        return 35;
    }

}
