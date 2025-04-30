<?php

declare(strict_types=1);

namespace AC\Deprecated;

class HookCollectionFactory
{

    public function create_filters(): HookCollection
    {
        $hooks = [];
        $free_filters = [
            'ac/column/value'                         => 'ac/column/render',
            'ac/column/value/sanitize'                => 'ac/column/render/sanitize',
            'ac/column/audio_player/valid_mime_types' => 'ac/column/audio_mime_types',
            'ac/headings/label'                       => 'ac/column/heading/label',

            // Removed Free
            'ac/column/separator'                     => null,
            'ac/headings'                             => null,
            'ac/column_group'                         => null,
            'ac/column/custom_field/field_types'      => null,
            'ac/read_only_message'                    => null,
            'ac/column/settings/column_types'         => null,
            'ac/column/header'                        => null,
            'ac/column/settings'                      => null,
            'ac/list_screen/preferences'              => null,
        ];

        foreach ($free_filters as $old => $new) {
            $hooks[] = new Hook($old, '5.0', $new);
        }

        $pro_filters = [
            'acp/custom_field/stored_date_format'          => 'ac/custom_field/stored_date_format',
            'acp/display_licence'                          => 'ac/display_licence',
            'ac/export/value'                              => 'ac/export/render',
            'ac/export/value/escape'                       => 'ac/export/render/escape',
            'ac/export/headers'                            => 'ac/export/row_headers',
            'acp/export/is_active'                         => 'ac/export/is_active',
            'acp/export/file_name'                         => 'ac/export/file_name',
            'acp/editing/persistent'                       => 'ac/editing/persistent',
            'acp/editing/post_statuses'                    => 'ac/editing/post_statuses',
            'acp/editing/save_value'                       => 'ac/editing/save_value',
            'acp/editing/settings/post_types'              => 'ac/editing/custom_field/post_types',
            'acp/editing/value'                            => 'ac/editing/input_value',
            'acp/editing/view'                             => 'ac/editing/view',
            'acp/editing/bulk/show_confirmation'           => 'ac/editing/bulk/show_confirmation',
            'acp/editing/bulk/is_active'                   => 'ac/editing/bulk/active',
            'acp/editing/bulk/updated_rows_per_iteration'  => 'ac/editing/bulk/updated_rows_per_iteration',
            'acp/delete/bulk/deleted_rows_per_iteration'   => 'ac/delete/bulk/deleted_rows_per_iteration',
            'acp/delete/reassign_user'                     => 'ac/delete/reassign_user',
            'acp/horizontal_scrolling/enable'              => 'ac/horizontal_scrolling/enable',
            'acp/quick_add/enable'                         => 'ac/quick_add/enable',
            'acp/resize_columns/active'                    => 'ac/resize_columns/active',
            'acp/search/is_active'                         => 'ac/search/enable',
            'acp/search/filters'                           => 'ac/search/filters',
            'acp/sorting/custom_field/date_type'           => 'ac/sorting/custom_field/date_type',
            'acp/sorting/default'                          => 'ac/sorting/default',
            'acp/sorting/model'                            => 'ac/sorting/model',
            'acp/sorting/remember_last_sorting_preference' => 'ac/sorting/remember_last_sorting_preference',
            'acp/sticky_header/enable'                     => 'ac/sticky_header/enable',
            'acp/table/query_args_whitelist'               => 'ac/table/query_args_whitelist',

            // Integration specific
            'acp/acf/export/repeater/delimiter'            => 'ac/acf/export/repeater/delimiter',
            'acp/gravityforms/create_default_set'          => 'ac/gravityforms/create_default_set',
            'acp/wc/column/product/sales/statuses'         => 'ac/wc/column/product/sales/statuses',
            'acp/wc/show_product_variations'               => 'ac/wc/show_product_variations',

            // Removed Pro
            'ac/export/column/disable'                     => null,
            'acp/admin/enable_submenu'                     => null,
            'acp/editing/inline/deprecated_style'          => null,
            'acp/editing/view_settings'                    => null,
            'acp/sorting/post_status'                      => null,
        ];

        foreach ($pro_filters as $old => $new) {
            $hooks[] = new Hook($old, '7.0', $new);
        }

        return new HookCollection($hooks);
    }

    public function create_actions(): HookCollection
    {
        $hooks = [];
        $free_actions = [
            'ac/column_types'   => 'ac/column/types',
            'ac/columns_stored' => 'ac/columns/stored',
        ];

        foreach ($free_actions as $old => $new) {
            $hooks[] = new Hook($old, '5.0', $new);
        }

        $pro_actions = [
            'acp/column_types'                  => 'ac/column/types/pro',
            'acp/acf/after_get_field_options'   => 'ac/acf/after_get_field_options',
            'acp/acf/before_get_field_options'  => 'ac/acf/before_get_field_options',
            'acp/admin/settings/hide_on_screen' => null,
            'acp/quick_add/saved'               => 'ac/quick_add/saved',
            'acp/list_screen/deleted'           => 'ac/list_screen/deleted',
            'acp/editing/saved'                 => 'ac/editing/saved',
            'acp/editing/before_save'           => 'ac/editing/before_save',
            'acp/admin/settings/table_elements' => 'ac/admin/settings/table_elements',
        ];

        foreach ($pro_actions as $old => $new) {
            $hooks[] = new Hook($old, '7.0', $new);
        }

        return new HookCollection($hooks);
    }

}