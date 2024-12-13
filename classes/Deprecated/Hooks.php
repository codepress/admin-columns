<?php

namespace AC\Deprecated;

use AC\Deprecated\Hook\Action;
use AC\Deprecated\Hook\Filter;
use AC\Transient;

class Hooks
{

    public function get_count(bool $force_update = false): int
    {
        $cache = new Transient('ac-deprecated-message-count');

        if ($force_update || $cache->is_expired()) {
            $cache->save($this->get_deprecated_count(), WEEK_IN_SECONDS);
        }

        return (int)$cache->get();
    }

    /**
     * @return Filter[]
     */
    private function get_filters(): array
    {
        $hooks = [
            // Altered to V2
            new Filter('ac/column/value', '5.0', 'ac/v2/column/value'),
            new Filter('ac/column/value/sanitize', '5.0', 'ac/v2/column/value/sanitize'),
            new Filter('ac/column_types', '5.0', 'ac/v2/column_types'),
            new Filter('ac/column/audio_player/valid_mime_types', '5.0', 'ac/v2/column/audio_player/valid_mime_types'),

            // Removed
            new Filter('ac/column/separator', '5.0'),
            new Filter('ac/headings', '5.0'),
            new Filter('ac/column_group', '5.0'),
            // Removed

            //            TODO replace these hooks that contain $column
            //            ac/column/audio_player/valid_mime_types
            //            ac/headings/label
            //            ac/list_screen/preferences
            //            ac/headings
            //            ac/column/video_player/valid_mime_types
            //            ac/column/settings/column_types
            //            ac/column_group

            // TODO More hooks to check
            //            ac/column/custom_field/use_text_input
            //            ac/column/custom_field/field_types

            // TODO remove
            //            new Filter('cac/headings/label', '3.0', 'cac-columns-custom'),
            //            new Filter('cac/column/meta/value', '3.0', 'cac-column-meta-value'),
            //            new Filter('cac/column/meta/types', '3.0', 'cac-column-meta-types'),
            //            new Filter('cac/settings/tabs', '3.0', 'cac-settings-tabs'),
            //            new Filter('cac/editable/is_column_editable', '3.0', 'cac-editable-is_column_editable'),
            //            new Filter('cac/editable/editables_data', '3.0', 'cac-editable-editables_data'),
            //            new Filter('cac/editable/options', '3.0', 'cac-editable-editables_data'),
            //            new Filter('cac/inline-edit/ajax-column-save/value', '3.0', 'cac-inline-edit-ajax-column-save-value'),
            //            new Filter('cac/addon/filtering/options', '3.0', 'cac-addon-filtering-options'),
            //            new Filter('cac/addon/filtering/dropdown_top_label', '3.0', 'cac-addon-filtering-dropdown_top_label'),
            //            new Filter('cac/addon/filtering/taxonomy/terms_args', '3.0', 'cac-addon-filtering-taxonomy-terms_args'),
            //            new Filter('cac/addon/filtering/dropdown_empty_option', '3.0', 'cac-addon-filtering-taxonomy-terms_args'),
            //            new Filter('cac/column/actions/action_links', '3.0', 'cac-column_actions-action_links'),
            //            new Filter('cac/acf/format_acf_value', '3.0', 'cac-acf-format_acf_value'),
            //            new Filter('cac/addon/filtering/taxonomy/terms_args', '3.0'),
            //            new Filter('cac/column/meta/use_text_input', '3.0'),
            //            new Filter('cac/hide_renewal_notice', '3.0'),
            //            new Filter('acp/network_settings/groups', '3.4'),
            //            new Filter('acp/settings/groups', '3.4'),
        ];

        return $hooks;
    }

    /**
     * @return Hook[]
     */
    private function get_actions(): array
    {
        return [
            new Action('ac/ready', '5.0'),
        ];
    }

    /**
     * @return Hook[]
     */
    public function get_deprecated_filters(): array
    {
        return $this->check_deprecated_hooks($this->get_filters());
    }

    /**
     * @return Hook[]
     */
    public function get_deprecated_actions(): array
    {
        return $this->check_deprecated_hooks($this->get_actions());
    }

    /**
     * @param Hook[] $hooks
     *
     * @return Hook[]
     */
    private function check_deprecated_hooks(array $hooks): array
    {
        $deprecated = [];

        foreach ($hooks as $hook) {
            if ($hook->has_hook()) {
                $deprecated[] = $hook;
            }
        }

        return $deprecated;
    }

    public function get_deprecated_count(): int
    {
        return count($this->get_deprecated_actions()) + count($this->get_deprecated_filters());
    }

}