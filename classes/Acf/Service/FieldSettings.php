<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Column;
use AC\Column\ColumnIdGenerator;
use AC\ColumnCollection;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\Config;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class FieldSettings extends AbstractFieldSettings
{

    // TODO test variant, like multiple post_object, gallery etc.
    private const FIELD_TYPE_MAP = [
        'checkbox'         => FieldType::TYPE_ARRAY,
        'color_picker'     => FieldType::TYPE_COLOR,
        'date_picker'      => FieldType::TYPE_DATE,
        'date_time_picker' => FieldType::TYPE_DATE,
        'oembed'           => FieldType::TYPE_URL,
        'file'             => FieldType::TYPE_MEDIA,
        'image'            => FieldType::TYPE_IMAGE,
        'number'           => FieldType::TYPE_NUMERIC,
        'post_object'      => FieldType::TYPE_POST,
        'page_link'        => FieldType::TYPE_POST,
        'range'            => FieldType::TYPE_NUMERIC,
        'text'             => FieldType::TYPE_TEXT,
        'true_false'       => FieldType::TYPE_BOOLEAN,
        'url'              => FieldType::TYPE_URL,
        'user'             => FieldType::TYPE_USER,
        'wysiwyg'          => FieldType::TYPE_HTML,
    ];

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

    protected function render_tab_early_exit(array $field, string $type): bool
    {
        if ($this->is_pro_only_field_type($type)) {
            $this->render_pro_section($field);

            return true;
        }

        return false;
    }

    private function is_pro_only_field_type(string $type): bool
    {
        return in_array($type, self::PRO_ONLY_ACF_TYPES, true);
    }

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
                    '<div style="background:#eaf5fc;border:1px solid #c8e3f6;border-radius:4px;padding:10px 14px;color:#184a6a;font-size:13px;line-height:1.5;"><strong>%s</strong><br>%s <a href="%s" target="_blank">%s</a></div>',
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
        acf_render_field_setting(
            $field,
            [
                'label'             => '',
                'type'              => 'message',
                'name'              => 'admin_columns_upsell',
                'message'           => sprintf(
                    '<div style="background:#eaf5fc;border:1px solid #c8e3f6;border-radius:4px;padding:10px 14px;color:#184a6a;font-size:13px;line-height:1.5;"><strong>%s</strong><br>%s <a href="%s" target="_blank">%s</a></div>',
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
        $labels        = [];

        foreach ($table_screens as $table_screen) {
            $labels[] = $table_screen->get_labels()->get_plural();
        }

        $labels = array_slice($labels, 0, 3);

        if ($labels) {
            $last    = strtolower(array_pop($labels));
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

    protected function is_field_type_supported(string $type): bool
    {
        return in_array($type, self::SUPPORTED_ACF_TYPES, true);
    }

    protected function is_column_for_field(Column $column, array $field): bool
    {
        if ($column->get_type() !== 'column-meta') {
            return false;
        }

        $setting = $column->get_setting('field');

        return $setting
               && $setting->has_input()
               && (string)$setting->get_input()->get_value() === ($field['name'] ?? '');
    }

    protected function add_column_in_list_screens(TableScreen $table_screen, array $field): void
    {
        $factory = $this->find_column_factory($table_screen, 'column-meta');

        if ( ! $factory) {
            return;
        }

        foreach ($this->storage->find_all_by_table_id($table_screen->get_id()) as $list_screen) {
            if ($list_screen->is_read_only()) {
                continue;
            }

            if ($this->has_column_for_field($list_screen, $field)) {
                continue;
            }

            $new_column = $factory->create(new Config([
                'name'       => (string)(new ColumnIdGenerator())->generate(),
                'type'       => 'column-meta',
                'field'      => $field['name'] ?? '',
                'label'      => $field['label'] ?? '',
                'field_type' => self::FIELD_TYPE_MAP[$field['type'] ?? ''] ?? '',
            ]));

            $columns = new ColumnCollection(iterator_to_array($list_screen->get_columns()));
            $columns->add($new_column);

            $list_screen->set_columns($columns);
            $this->storage->save($list_screen);
        }
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

        $table_screen = reset($table_screens);
        $table_id     = $table_screen->get_id();
        $list_screen  = $this->storage->find_all_by_table_id($table_id)->first();

        if ( ! $list_screen) {
            return;
        }

        $url   = EditorUrlFactory::create($table_id, false, $list_screen->get_id());
        $title = $list_screen->get_title() ?: $table_screen->get_labels()->get_plural();

        acf_render_field_setting(
            $field,
            [
                'label'             => false,
                'type'              => 'message',
                'name'              => 'admin_columns_editor_link',
                'message'           => sprintf(
                    '<p>%s</p><p><a href="%s" class="button button-primary" target="_blank">%s &rarr;</a></p>',
                    sprintf(
                    /* translators: %s: table view name */
                        esc_html__(
                            'Manage the label, position, width, and other advanced settings in the column editor for %s.',
                            'codepress-admin-columns'
                        ),
                        '<strong>' . esc_html($title) . '</strong>'
                    ),
                    esc_url((string)$url),
                    esc_html__('Customize Columns', 'codepress-admin-columns')
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

}
