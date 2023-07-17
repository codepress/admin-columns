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
            new Filter('cac/headings/label', '3.0', 'cac-columns-custom'),
            new Filter('cac/column/meta/value', '3.0', 'cac-column-meta-value'),
            new Filter('cac/column/meta/types', '3.0', 'cac-column-meta-types'),
            new Filter('cac/settings/tabs', '3.0', 'cac-settings-tabs'),
            new Filter('cac/editable/is_column_editable', '3.0', 'cac-editable-is_column_editable'),
            new Filter('cac/editable/editables_data', '3.0', 'cac-editable-editables_data'),
            new Filter('cac/editable/options', '3.0', 'cac-editable-editables_data'),
            new Filter('cac/inline-edit/ajax-column-save/value', '3.0', 'cac-inline-edit-ajax-column-save-value'),
            new Filter('cac/addon/filtering/options', '3.0', 'cac-addon-filtering-options'),
            new Filter('cac/addon/filtering/dropdown_top_label', '3.0', 'cac-addon-filtering-dropdown_top_label'),
            new Filter('cac/addon/filtering/taxonomy/terms_args', '3.0', 'cac-addon-filtering-taxonomy-terms_args'),
            new Filter('cac/addon/filtering/dropdown_empty_option', '3.0', 'cac-addon-filtering-taxonomy-terms_args'),
            new Filter('cac/column/actions/action_links', '3.0', 'cac-column_actions-action_links'),
            new Filter('cac/acf/format_acf_value', '3.0', 'cac-acf-format_acf_value'),
            new Filter('cac/addon/filtering/taxonomy/terms_args', '3.0'),
            new Filter('cac/column/meta/use_text_input', '3.0'),
            new Filter('cac/hide_renewal_notice', '3.0'),
            new Filter('acp/network_settings/groups', '3.4'),
            new Filter('acp/settings/groups', '3.4'),
        ];

        $hooks[] = new Filter('cac/columns/custom', '3.0', 'cac-columns-custom');

        foreach ($this->get_types() as $type) {
            $hooks[] = new Filter('cac/columns/custom/type=' . $type, '3.0', 'cac-columns-custom');
        }

        foreach (get_post_types() as $post_type) {
            $hooks[] = new Filter('cac/columns/custom/post_type=' . $post_type, '3.0', 'cac-columns-custom');
        }

        $hooks[] = new Filter('cac/column/value', '3.0', 'cac-column-value');

        foreach ($this->get_types() as $type) {
            $hooks[] = new Filter('cac/column/value/' . $type, '3.0', 'cac-column-value');
        }

        $hooks[] = new Filter('cac/editable/column_value', '3.0', 'cac-editable-column_value');
        $hooks[] = new Filter('cac/editable/column_save', '3.0', 'cac-editable-column_save');

        return $hooks;
    }

    private function get_types(): array
    {
        return ['post', 'user', 'comment', 'link', 'media'];
    }

    /**
     * @return Hook[]
     */
    private function get_actions(): array
    {
        return [
            new Action('cac/admin_head', '3.0', 'cac-admin_head'),
            new Action('cac/loaded', '3.0', 'cac-loaded'),
            new Action('cac/inline-edit/after_ajax_column_save', '3.0', 'cacinline-editafter_ajax_column_save'),
            new Action('cac/settings/after_title', '3.0'),
            new Action('cac/settings/form_actions', '3.0'),
            new Action('cac/settings/sidebox', '3.0'),
            new Action('cac/settings/form_columns', '3.0'),
            new Action('cac/settings/after_columns', '3.0'),
            new Action('cac/column/settings_meta', '3.0'),
            new Action('cac/settings/general', '3.0'),
            new Action('cpac_messages', '3.0'),
            new Action('cac/settings/after_menu', '3.0'),
            new Action('ac/settings/general', '3.4'),
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