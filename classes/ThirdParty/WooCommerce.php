<?php

namespace AC\ThirdParty;

use AC;
use AC\Registerable;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;
use Automattic\WooCommerce\Utilities\OrderUtil;

class WooCommerce implements Registerable
{

    public function register(): void
    {
        add_filter('ac/post_types', [$this, 'remove_webhook']);
        add_filter('ac/admin/screen_notices', [$this, 'add_hpos_notice']);
    }

    public function remove_webhook($post_types)
    {
        if (AC\WooCommerce::is_active()) {
            unset($post_types['shop_webhook']);
        }

        return $post_types;
    }

    public function add_hpos_notice(array $notices): array
    {
        if ( ! $this->is_hpos_enabled()) {
            return $notices;
        }

        $notices[] = [
            'list_key'  => 'shop_order',
            'type'      => 'info',
            'message'   => sprintf(
                __(
                    'WooCommerce is using %s on your site. Columns configured here won\'t appear on the Orders screen. Upgrade to Admin Columns Pro for full order management with HPOS support.',
                    'codepress-admin-columns'
                ),
                '<strong>' . __('High-Performance Order Storage', 'codepress-admin-columns') . '</strong>'
            ),
            'cta_label' => __('Upgrade to Admin Columns Pro', 'codepress-admin-columns'),
            'cta_url'   => (new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'hpos-notice'))->get_url(),
            'locked'    => true,
        ];

        return $notices;
    }

    private function is_hpos_enabled(): bool
    {
        return class_exists(OrderUtil::class)
               && OrderUtil::custom_orders_table_usage_is_enabled();
    }

}