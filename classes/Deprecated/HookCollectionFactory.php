<?php

declare(strict_types=1);

namespace AC\Deprecated;

class HookCollectionFactory
{

    //  TODO replace hooks that contain $column

    public function create_filters(): HookCollection
    {
        // TODO move hooks to create_hooks method
        return new HookCollection([
            // Replaced
            new Hook('ac/column/value', '5.0', 'ac/v2/column/value'),
            new Hook('ac/column/value/sanitize', '5.0', 'ac/v2/column/value/sanitize'),
            new Hook('ac/column_types', '5.0', 'ac/v2/column_types'),
            new Hook('ac/column/audio_player/valid_mime_types', '5.0', 'ac/v2/column/audio_player/valid_mime_types'),
            new Hook('ac/columns_stored', '5.0', 'ac/v2/columns_stored'),
            new Hook('ac/headings/label', '5.0', 'ac/v2/headings/label'),
            new Hook('ac/table/list_screen', '5.0', 'ac/v2/table/list_screen'),

            // Replaced Pro
            new Hook('ac/export/value', '7.0', 'acp/v2/export/value'),
            new Hook('ac/export/value/escape', '7.0', 'acp/v2/export/value/escape'),
            new Hook('acp/editing/persistent', '7.0', 'acp/v2/editing/persistent'),
            new Hook('acp/editing/post_statuses', '7.0', 'acp/v2/editing/post_statuses'),
            new Hook('acp/editing/save_value', '7.0', 'acp/v2/editing/save_value'),
            new Hook('acp/editing/settings/post_types', '7.0', 'acp/v2/editing/custom_field/post_types'),
            new Hook('acp/editing/value', '7.0', 'acp/v2/editing/value'),
            new Hook('acp/editing/view', '7.0', 'acp/v2/editing/view'),
            new Hook(
                'acp/editing/bulk/updated_rows_per_iteration',
                '7.0',
                'acp/v2/editing/bulk/updated_rows_per_iteration'
            ),
            new Hook(
                'acp/delete/bulk/deleted_rows_per_iteration',
                '7.0',
                'acp/v2/delete/bulk/deleted_rows_per_iteration'
            ),
            new Hook('acp/export/is_active', '7.0', 'acp/v2/export/is_active'),
            new Hook('acp/horizontal_scrolling/enable', '7.0', 'acp/v2/horizontal_scrolling/enable'),
            new Hook('acp/search/filters', '7.0', 'acp/v2/search/filters'),
            new Hook('acp/sorting/model', '7.0', 'acp/v2/sorting/model'),
            new Hook(
                'acp/sorting/remember_last_sorting_preference',
                '7.0',
                'acp/v2/sorting/remember_last_sorting_preference'
            ),
            new Hook('acp/wc/column/product/sales/statuses', '7.0', 'acp/v2/wc/column/product/sales/statuses'),
            new Hook('acp/quick_add/saved', '7.0', 'acp/v2/quick_add/saved'),
            new Hook('acp/acf/after_get_field_options', '7.0', 'acp/v2/acf/after_get_field_options'),
            new Hook('acp/acf/before_get_field_options', '7.0', 'acp/v2/acf/before_get_field_options'),

            // Removed
            new Hook('ac/column/separator', '5.0'),
            new Hook('ac/headings', '5.0'),
            new Hook('ac/column_group', '5.0'),
            new Hook('ac/column/custom_field/field_types', '5.0'),
            new Hook('ac/read_only_message', '5.0'),
            new Hook('ac/column/settings/column_types', '5.0'),
            new Hook('ac/column/header', '5.0'),
            new Hook('ac/column/settings', '5.0'),
            new Hook('ac/list_screen/preferencess', '5.0'),

            // Removed Pro
            new Hook('ac/export/column/disable', '7.0'),
            new Hook('acp/admin/enable_submenu', '7.0'),
            new Hook('acp/editing/inline/deprecated_style', '7.0'),
            new Hook('acp/editing/view_settings', '7.0'),
            new Hook('acp/sorting/post_status', '7.0'),
            new Hook('acp/admin/settings/hide_on_screen', '7.0'),
        ]);
    }

    public function create_actions(): HookCollection
    {
        return new HookCollection([
            new Hook('ac/ready', '5.0'),
        ]);
    }

}