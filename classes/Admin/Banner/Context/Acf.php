<?php

declare(strict_types=1);

namespace AC\Admin\Banner\Context;

use AC\Acf\FieldCount;
use AC\Admin\Banner\BannerContext;
use AC\TableScreen;
use AC\Type\StartingPrice;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class Acf implements BannerContext
{

    private FieldCount $field_count;

    public function __construct(FieldCount $field_count)
    {
        $this->field_count = $field_count;
    }

    public function is_active(TableScreen $table_screen): bool
    {
        if ( ! class_exists('acf', false)) {
            return false;
        }

        return $this->field_count->get_count_for_table_screen($table_screen) > 0;
    }

    public function get_priority(): int
    {
        return 10;
    }

    public function get_arguments(TableScreen $table_screen): array
    {
        $upgrade_url = new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'banner-acf');

        $field_count = $this->field_count->get_count_for_table_screen($table_screen);

        return [
            'badge'             => __('Admin Columns Pro', 'codepress-admin-columns'),
            'title'             => _n(
                'Your ACF field is hidden on this screen',
                'Your ACF fields are hidden on this screen',
                $field_count,
                'codepress-admin-columns'
            ),
            'description'       => sprintf(
                _n(
                    '%d ACF field is not visible here. Pro turns it into a column you can sort, filter, edit, and export - no code needed.',
                    '%d ACF fields are not visible here. Pro turns each one into a column you can sort, filter, edit, and export - no code needed.',
                    $field_count,
                    'codepress-admin-columns'
                ),
                $field_count
            ),
            'quote'             => [
                'text' => __('A super intuitive life saver!', 'codepress-admin-columns'),
                'cite' => __('Elliot Condon, creator of Advanced Custom Fields', 'codepress-admin-columns'),
            ],
            'features_label'    => __('With Pro, your ACF fields become:', 'codepress-admin-columns'),
            'features'          => [
                [
                    'url'   => $upgrade_url->with_content('usp-acf-columns')->get_url(),
                    'label' => __('Visible as columns on this screen', 'codepress-admin-columns'),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-acf-sorting')->get_url(),
                    'label' => __('Filterable and sortable by any field', 'codepress-admin-columns'),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-acf-editing')->get_url(),
                    'label' => __('Editable directly in the table', 'codepress-admin-columns'),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-acf-bulk-edit')->get_url(),
                    'label' => __('Bulk editable across hundreds of posts', 'codepress-admin-columns'),
                ],
                [
                    'url'   => $upgrade_url->with_content('usp-acf-export')->get_url(),
                    'label' => __('Exportable to CSV', 'codepress-admin-columns'),
                ],
            ],
            'upgrade_cta'       => __('Unlock ACF fields in your table', 'codepress-admin-columns'),
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

}
