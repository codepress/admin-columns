<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class WooCommerceOrdersSearchNotice implements IntegrationNotice, UsageAwareNotice
{

    use WooCommerceOrdersScreenAware;

    public function is_usage_detected(): bool
    {
        return ! empty($_GET['s']);
    }

    public function is_active(Screen $screen): bool
    {
        if ( ! class_exists('WooCommerce', false)) {
            return false;
        }

        return $this->is_woocommerce_orders_screen($screen);
    }

    public function get_slug(): string
    {
        return 'wc-orders-search';
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
        return __('Looking for a specific order?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __('Pro lets you search across any order property - payment method, shipping address, customer email, even order notes. Save your searches as reusable segments and share them with your team.', 'codepress-admin-columns');
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-orders-search'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-orders-search-features'))->get_url();
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
