<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;
use AC\WooCommerce;

class WooCommerceOrdersNotice implements IntegrationNotice, UsageAwareNotice
{

    use WooCommerceOrdersScreenAware;

    public function is_usage_detected(): bool
    {
        return ! empty($_GET['orderby']) || ! empty($_GET['m']) || ! empty($_GET['_customer_user']) || ! empty($_GET['s']);
    }

    public function is_active(Screen $screen): bool
    {
        if ( ! WooCommerce::is_active()) {
            return false;
        }

        return $this->is_woocommerce_orders_screen($screen);
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
        return __('Show any order data as a column - shipping address, payment method, customer email, order notes. Search, filter, and edit without leaving this screen.', 'codepress-admin-columns');
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

    public function get_delay_days(): int
    {
        return 42;
    }

}
