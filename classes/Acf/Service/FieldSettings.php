<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Column;
use AC\ListScreen;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class FieldSettings extends AbstractFieldSettings
{

    private const UPSELL_BOX_STYLE = 'background:#eaf5fc;border:1px solid #c8e3f6;border-radius:4px;padding:10px 14px;color:#184a6a;font-size:13px;line-height:1.5;';

    private const PRO_ONLY_ACF_TYPES = [
        'flexible_content',
        'gallery',
        'google_map',
        'group',
        'link',
        'relationship',
        'repeater',
        'taxonomy',
    ];

    private const SUPPORTED_ACF_TYPES = [
        'button_group',
        'checkbox',
        'color_picker',
        'date_picker',
        'date_time_picker',
        'email',
        'file',
        'image',
        'number',
        'oembed',
        'page_link',
        'password',
        'post_object',
        'radio',
        'range',
        'select',
        'text',
        'textarea',
        'time_picker',
        'true_false',
        'url',
        'user',
        'wysiwyg',
    ];

    private function render_pro_section(array $field): void
    {
        $url = (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'acf-field-settings-upsell'))->get_url();

        echo '<div style="pointer-events:none;opacity:0.6;margin-bottom: 20px;">';
        acf_render_field_setting(
            $field,
            [
                'label'        => __('Add as Admin Column', 'codepress-admin-columns'),
                'instructions' => __(
                    'Display this field as a column in the admin list table.',
                    'codepress-admin-columns'
                ),
                'type'         => 'true_false',
                'name'         => 'admin_columns_enabled',
                'ui'           => 1,
            ]
        );
        echo '</div>';

        acf_render_field_setting(
            $field,
            [
                'label'   => '',
                'type'    => 'message',
                'name'    => 'admin_columns_pro_upsell',
                'message' => sprintf(
                    '<div style="' . self::UPSELL_BOX_STYLE . '"><strong>%s</strong><br>%s <a href="%s" target="_blank">%s</a></div>',
                    esc_html__('This field type is supported in Admin Columns Pro', 'codepress-admin-columns'),
                    esc_html__(
                        'Add this field as a column with sorting, filtering, and inline editing.',
                        'codepress-admin-columns'
                    ),
                    esc_url($url),
                    esc_html__('Learn more', 'codepress-admin-columns')
                ),
            ]
        );
    }

    protected function render_tab_content(array $field, array $enabled_condition): void
    {
        $this->render_table_screen_select($field, $enabled_condition);

        acf_render_field_setting(
            $field,
            [
                'label'             => '',
                'type'              => 'message',
                'name'              => 'admin_columns_upsell',
                'message'           => sprintf(
                    '<div style="' . self::UPSELL_BOX_STYLE . '"><strong>%s</strong><br>%s <a href="%s" target="_blank">%s</a></div>',
                    esc_html__('Unlock powerful column features', 'codepress-admin-columns'),
                    esc_html($this->get_features_description($field)),
                    esc_url((string)(new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'acf-field-settings'))->get_url()),
                    esc_html__('Get Admin Columns Pro →', 'codepress-admin-columns')
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    private function get_features_description(array $field): string
    {
        $table_screens = $this->resolve_table_screens($field);
        $labels = [];

        foreach ($table_screens as $table_screen) {
            $labels[] = $table_screen->get_labels()->get_plural();
        }

        $labels = array_slice($labels, 0, 3);

        if ($labels) {
            $last = strtolower(array_pop($labels));
            $content = $labels
                ? strtolower(implode(', ', $labels)) . ' & ' . $last
                : $last;

            return sprintf(
                __(
                    'Make this column editable, sortable, and filterable - and manage your %s faster right from the overview.',
                    'codepress-admin-columns'
                ),
                $content
            );
        }

        return __(
            'Make this column editable, sortable, and filterable - and manage your content faster right from the overview.',
            'codepress-admin-columns'
        );
    }

    private function is_pro_only_field(array $field): bool
    {
        return $this->is_sub_field($field) || in_array($field['type'], self::PRO_ONLY_ACF_TYPES, true);
    }

    public function render_tab(array $field): void
    {
        if ($this->is_pro_only_field($field)) {
            $this->render_pro_section($field);

            return;
        }

        parent::render_tab($field);
    }

    protected function is_field_supported(array $field): bool
    {
        if ($this->is_pro_only_field($field)) {
            return false;
        }

        if ($field['type'] === 'select' && '1' === $field['multiple']) {
            return false;
        }

        return in_array($field['type'], self::SUPPORTED_ACF_TYPES, true);
    }

    protected function is_column_for_field(Column $column, array $field): bool
    {
        $meta_key = $field['name'] ?? '';

        if ( ! $meta_key || $column->get_type() !== 'column-meta') {
            return false;
        }

        $setting = $column->get_setting('field');

        return $setting
               && $setting->has_input()
               && (string)$setting->get_input()->get_value() === $meta_key;
    }

    protected function render_editor_links(array $field, array $enabled_condition): void
    {
        if (empty($field['admin_columns_enabled'])) {
            return;
        }

        $table_screens = $this->resolve_table_screens($field);

        if ( ! $table_screens) {
            return;
        }

        if (count($table_screens) === 1) {
            $this->render_single_editor_link($field, reset($table_screens), $enabled_condition);

            return;
        }

        $this->render_multi_editor_links($field, $table_screens, $enabled_condition);
    }

    private function render_single_editor_link(array $field, TableScreen $table_screen, array $enabled_condition): void
    {
        $list_screen = $this->storage->find_all_by_table_id($table_screen->get_id())->first();

        if ( ! $list_screen) {
            return;
        }

        $url = EditorUrlFactory::create($table_screen->get_id(), false, $list_screen->get_id());
        $column = $this->find_column_for_field($list_screen, $field);

        if ($column) {
            $url = $url->with_arg('open_columns', (string)$column->get_id());
        }

        acf_render_field_setting(
            $field,
            [
                'label'             => __('Manage Column Settings', 'codepress-admin-columns'),
                'type'              => 'message',
                'name'              => 'admin_columns_editor_link',
                'message'           => sprintf(
                    '<p class="description">%s</p></p><a href="%s" class="button" target="_blank">%s</a>',
                    esc_html__('Open the column editor to manage the label, position, width, and other advanced settings.', 'codepress-admin-columns'),
                    esc_url((string)$url),
                    esc_html__('Edit Column →', 'codepress-admin-columns')
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    private function find_list_screen_with_field(TableScreen $table_screen, array $field): ?ListScreen
    {
        foreach ($this->storage->find_all_by_table_id($table_screen->get_id()) as $list_screen) {
            if ($this->has_column_for_field($list_screen, $field)) {
                return $list_screen;
            }
        }

        return null;
    }

    private function render_multi_editor_links(array $field, array $table_screens, array $enabled_condition): void
    {
        $rows = [];

        foreach ($table_screens as $table_screen) {
            $list_screen = $this->find_list_screen_with_field($table_screen, $field);

            if ( ! $list_screen) {
                continue;
            }

            $url = EditorUrlFactory::create($table_screen->get_id(), false, $list_screen->get_id());
            $column = $this->find_column_for_field($list_screen, $field);

            if ($column) {
                $url = $url->with_arg('open_columns', (string)$column->get_id());
            }
            $title = $table_screen->get_labels()->get_plural();

            $rows[] = sprintf(
                '<a href="%s" style="margin-right: 8px;display:inline-block;" class="button" target="_blank">%s</a>',
                esc_url((string)$url),
                esc_html(sprintf(__('Edit %s Column →', 'codepress-admin-columns'), $title))
            );
        }

        if ( ! $rows) {
            return;
        }

        acf_render_field_setting(
            $field,
            [
                'label'             => __('Manage Column Settings', 'codepress-admin-columns'),
                'type'              => 'message',
                'name'              => 'admin_columns_editor_link',
                'message'           => sprintf(
                    '<p class="description">%s</p>%s',
                    esc_html__('Open the column editor to manage the label, position, width, and other advanced settings.', 'codepress-admin-columns'),
                    implode('', $rows)
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

}
