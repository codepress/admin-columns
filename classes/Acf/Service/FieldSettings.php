<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Column;
use AC\Column\ColumnIdGenerator;
use AC\ColumnCollection;
use AC\Setting\Config;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class FieldSettings extends AbstractFieldSettings
{

    // TODO test variant, like multiple post_object, gallery etc.
    private const FIELD_TYPE_MAP = [
        'checkbox'         => 'checkmark',
        'color_picker'     => 'color',
        'date_picker'      => 'date',
        'date_time_picker' => 'date',
        'file'             => 'library_id',
        'image'            => 'image',
        'number'           => 'numeric',
        'post_object'      => 'title_by_id',
        'range'            => 'numeric',
        'text'             => 'excerpt',
        'true_false'       => 'checkmark',
        'url'              => 'link',
        'user'             => 'user_by_id',
        'wysiwyg'          => 'html',
    ];

    private const PRO_ONLY_ACF_TYPES = [
        'flexible_content',
        'gallery',
        'google_map',
        'group',
        'link',
        'page_link',
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

    public function render_tab(array $field): void
    {
        $type = $field['type'] ?? '';

        if ($this->is_pro_only_field_type($type)) {
            $this->render_pro_upsell($field);

            return;
        }

        if ( ! $this->is_field_type_supported($type)) {
            acf_render_field_setting(
                $field,
                [
                    'label'   => __('Not Supported', 'codepress-admin-columns'),
                    'type'    => 'message',
                    'name'    => 'admin_columns_unsupported',
                    'message' => __('This field type cannot be used as an admin column.', 'codepress-admin-columns'),
                ]
            );

            return;
        }

        $enabled_condition = [
            [
                [
                    'field'    => 'admin_columns_enabled',
                    'operator' => '==',
                    'value'    => '1',
                ],
            ],
        ];

        acf_render_field_setting(
            $field,
            [
                'label'        => __('Add as Admin Column', 'codepress-admin-columns'),
                'instructions' => $this->get_toggle_instructions($field),
                'type'         => 'true_false',
                'name'         => 'admin_columns_enabled',
                'ui'           => 1,
            ]
        );

        acf_render_field_setting(
            $field,
            [
                'label'             => '',
                'type'              => 'message',
                'name'              => 'admin_columns_upsell',
                'message'           => sprintf(
                    '<div style="background:#eaf5fc;border:1px solid #c8e3f6;border-radius:4px;padding:10px 14px;color:#184a6a;font-size:13px;line-height:1.5;"><strong>%s</strong><br>%s<br><a href="%s" target="_blank">%s</a></div>',
                    esc_html__('Do more with this column', 'codepress-admin-columns'),
                    esc_html__('Make this column sortable, editable, filterable, exportable to CSV - all from the list table.', 'codepress-admin-columns'),
                    esc_url((string)(new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'acf-field-settings-upsell'))->get_url()),
                    esc_html__('Learn more about Admin Columns Pro', 'codepress-admin-columns')
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );

        $this->render_editor_links($field, $enabled_condition);
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
        $list_screens = $this->storage->find_all_by_table_id($table_screen->get_id());

        foreach ($list_screens as $list_screen) {
            if ($list_screen->is_read_only()) {
                continue;
            }

            if ($this->has_column_for_field($list_screen, $field)) {
                continue;
            }

            $factory = $this->find_column_factory($table_screen, 'column-meta');

            if ( ! $factory) {
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

        $group = acf_get_field_group($field['parent'] ?? 0);

        if ( ! $group) {
            return;
        }

        $table_screens = $this->table_screen_resolver->resolve($group);

        if ( ! $table_screens) {
            return;
        }

        $table_screen = reset($table_screens);
        $table_id = $table_screen->get_id();
        $list_screen = $this->storage->find_all_by_table_id($table_id)->first();

        if ( ! $list_screen) {
            return;
        }

        $url = EditorUrlFactory::create($table_id, false, $list_screen->get_id());
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
                        esc_html__('Manage the label, position, width, and other advanced settings in the column editor for %s.', 'codepress-admin-columns'),
                        '<strong>' . esc_html($title) . '</strong>'
                    ),
                    esc_url((string)$url),
                    esc_html__('Customize Columns', 'codepress-admin-columns')
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    private function is_pro_only_field_type(string $type): bool
    {
        return in_array($type, self::PRO_ONLY_ACF_TYPES, true);
    }

    private function render_pro_upsell(array $field): void
    {
        $url = (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'acf-field-settings-upsell'))->get_url();

        echo '<div style="pointer-events:none;opacity:0.6;margin-bottom: 20px;">';
        acf_render_field_setting(
            $field,
            [
                'label'        => __('Add as Admin Column', 'codepress-admin-columns'),
                'instructions' => __('Display this field as a column in the admin list table.', 'codepress-admin-columns'),
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
                    esc_html__('Add this field as a column with sorting, filtering, and inline editing.', 'codepress-admin-columns'),
                    esc_url($url),
                    esc_html__('Learn more', 'codepress-admin-columns')
                ),
            ]
        );
    }

}
