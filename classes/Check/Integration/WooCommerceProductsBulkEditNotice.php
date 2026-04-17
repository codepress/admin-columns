<?php

declare(strict_types=1);

namespace AC\Check\Integration;

use AC\Screen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;
use AC\WooCommerce;

class WooCommerceProductsBulkEditNotice implements IntegrationNotice, UsageAwareNotice
{

    use PostEditReferrerAware;

    public function is_active(Screen $screen): bool
    {
        if ( ! WooCommerce::is_active()) {
            return false;
        }

        return 'edit' === $screen->get_base() && 'product' === $screen->get_post_type();
    }

    public function get_slug(): string
    {
        return 'wc-products-bulk-edit';
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
        return __('Updating products one by one?', 'codepress-admin-columns');
    }

    public function get_description(): string
    {
        return __(
            'With Pro, bulk edit prices, stock, SKUs, attributes, and custom fields across your entire selection. Need to adjust pricing? The Price Update Wizard lets you change prices by percentage or fixed value across hundreds of products in seconds.',
            'codepress-admin-columns'
        );
    }

    public function get_cta_label(): string
    {
        return __('Upgrade to Admin Columns Pro', 'codepress-admin-columns');
    }

    public function get_cta_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-bulk-edit'))->get_url();
    }

    public function get_secondary_label(): string
    {
        return __('See features', 'codepress-admin-columns');
    }

    public function get_secondary_url(): string
    {
        return (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'notice-wc-products-bulk-edit-features'))->get_url();
    }

    public function get_extra_classes(): string
    {
        return '';
    }

    public function is_usage_detected(): bool
    {
        return $this->is_post_edit_referrer();
    }

    public function get_delay_days(): int
    {
        return 14;
    }

}
