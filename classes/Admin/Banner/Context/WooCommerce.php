<?php

declare(strict_types=1);

namespace AC\Admin\Banner\Context;

use AC\Admin\Banner\BannerContext;
use AC\PostType;
use AC\TableScreen;
use AC\Type\StartingPrice;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class WooCommerce implements BannerContext
{

    public function is_active(TableScreen $table_screen): bool
    {
        if ( ! class_exists('WooCommerce', false)) {
            return false;
        }

        return $this->get_screen_type($table_screen) !== null;
    }

    public function get_priority(): int
    {
        return 5;
    }

    public function get_arguments(TableScreen $table_screen): array
    {
        $upgrade_url = new UtmTags(new Site(Site::PAGE_ADDON_WOOCOMMERCE), 'banner-woocommerce');
        $is_product = $this->get_screen_type($table_screen) === 'product';

        return [
            'badge'             => __('Admin Columns Pro', 'codepress-admin-columns'),
            'title'             => $is_product
                ? __('Manage your products faster', 'codepress-admin-columns')
                : __('Manage your orders faster', 'codepress-admin-columns'),
            'description'       => $is_product
                ? __('Your product list is more than a list. With Pro, it becomes a workspace where you can view, edit, filter, and export every product detail - without opening a single product.', 'codepress-admin-columns')
                : __('Stop clicking into orders to find what you need. Pro turns your order list into a filterable, sortable, exportable overview - built for the way you actually manage orders.', 'codepress-admin-columns'),
            'features_label'    => __('With Pro you get', 'codepress-admin-columns'),
            'features'          => $is_product
                ? $this->get_product_features($upgrade_url)
                : $this->get_order_features($upgrade_url),
            'upgrade_cta'       => $is_product
                ? __('Take control of your products', 'codepress-admin-columns')
                : __('Filter, edit & manage your orders', 'codepress-admin-columns'),
            'upgrade_cta_price' => sprintf(
                '%s · %s',
                sprintf(
                /* translators: %s: price (e.g. $79) */
                    __('from %s/year', 'codepress-admin-columns'),
                    StartingPrice::get()
                ),
                __('all features included', 'codepress-admin-columns')
            ),
            'integrations'      => [],
            'promo_url'         => $upgrade_url->get_url(),
        ];
    }

    private function get_screen_type(TableScreen $table_screen): ?string
    {
        if ($table_screen instanceof PostType) {
            $post_type = (string)$table_screen->get_post_type();

            if ('product' === $post_type) {
                return 'product';
            }

            if ('shop_order' === $post_type) {
                return 'order';
            }
        }

        if ('wc_order' === (string)$table_screen->get_id()) {
            return 'order';
        }

        return null;
    }

    private function get_product_features(UtmTags $upgrade_url): array
    {
        return [
            [
                'url'   => $upgrade_url->with_content('usp-wc-product-columns')->get_url(),
                'label' => __('Add price, stock, SKU, and attributes as columns', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-product-editing')->get_url(),
                'label' => __('Inline edit product data directly in the table', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-product-bulk')->get_url(),
                'label' => __('Bulk update prices or stock across hundreds of products', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-product-filtering')->get_url(),
                'label' => __('Filter products by any field - including custom fields', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-product-export')->get_url(),
                'label' => __('Export filtered product lists to CSV', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-product-views')->get_url(),
                'label' => __('Save different column layouts for different tasks', 'codepress-admin-columns'),
            ],
        ];
    }

    private function get_order_features(UtmTags $upgrade_url): array
    {
        return [
            [
                'url'   => $upgrade_url->with_content('usp-wc-order-columns')->get_url(),
                'label' => __('Add shipping method, coupons, and item count as columns', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-order-filtering')->get_url(),
                'label' => __('Filter orders by product, status, date range, or custom field', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-order-sorting')->get_url(),
                'label' => __('Sort by total, item count, or any order detail', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-order-editing')->get_url(),
                'label' => __('Inline edit order status and custom fields', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-order-export')->get_url(),
                'label' => __('Export filtered order sets to CSV in one click', 'codepress-admin-columns'),
            ],
            [
                'url'   => $upgrade_url->with_content('usp-wc-order-views')->get_url(),
                'label' => __('Save views for different workflows - refunds, fulfilment, reporting', 'codepress-admin-columns'),
            ],
        ];
    }

}
