<?php

declare(strict_types=1);

namespace AC\Admin\Banner\Context;

use AC\Acf\FieldGroup\Location;
use AC\Acf\FieldGroup\Query;
use AC\Admin\Banner\BannerContext;
use AC\PostType;
use AC\TableScreen;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class Acf implements BannerContext
{

    public function is_active(TableScreen $table_screen): bool
    {
        if ( ! class_exists('acf', false)) {
            return false;
        }

        $query = $this->create_query($table_screen);

        if ( ! $query) {
            return false;
        }

        return ! empty($query->get_groups());
    }

    public function get_priority(): int
    {
        return 10;
    }

    public function get_arguments(TableScreen $table_screen): array
    {
        $upgrade_url = new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'banner-acf');

        $field_count = $this->count_fields($table_screen);
        $label = $table_screen->get_labels()->get_plural();

        return [
            'badge'          => __('Admin Columns Pro', 'codepress-admin-columns'),
            'title'          => _n(
                'Your ACF field is hidden on this screen',
                'Your ACF fields are hidden on this screen',
                $field_count,
                'codepress-admin-columns'
            ),
            'description'    => sprintf(
                _n(
                    '%d ACF field is not visible here. Pro turns it into a column you can sort, filter, edit, and export - no code needed.',
                    '%d ACF fields are not visible here. Pro turns each one into a column you can sort, filter, edit, and export - no code needed.',
                    $field_count,
                    'codepress-admin-columns'
                ),
                $field_count
            ),
            'quote'          => [
                'text' => __('A super intuitive life saver!', 'codepress-admin-columns'),
                'cite' => __('Elliot Condon, creator of Advanced Custom Fields', 'codepress-admin-columns'),
            ],
            'features_label' => __('With Pro, your ACF fields become:', 'codepress-admin-columns'),
            'features'       => [
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
            'upgrade_cta'    => sprintf(
                '%s - %s',
                __('Show ACF fields in your table', 'codepress-admin-columns'),
                sprintf(
                /* translators: %s: price (e.g. €79) */
                    __('from %s/year', 'codepress-admin-columns'),
                    '€79'
                )
            ),
            'integrations'   => [],
            'promo_url'      => $upgrade_url->get_url(),
        ];
    }

    private function create_query(TableScreen $table_screen): ?Query
    {
        if ($table_screen instanceof PostType) {
            return new Location\Post((string)$table_screen->get_post_type());
        }

        if ($table_screen instanceof TableScreen\User) {
            return new Location\User();
        }

        return null;
    }

    private function count_fields(TableScreen $table_screen): int
    {
        $query = $this->create_query($table_screen);

        if ( ! $query) {
            return 0;
        }

        $count = 0;

        foreach ($query->get_groups() as $group) {
            $fields = acf_get_fields($group['key']);

            if (is_array($fields)) {
                $count += count($fields);
            }
        }

        return $count;
    }

}
