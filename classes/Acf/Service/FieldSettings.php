<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Column\ColumnFactory;
use AC\Column\ColumnIdGenerator;
use AC\ColumnCollection;
use AC\ColumnFactories\Aggregate;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\Setting\Config;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\TableId;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class FieldSettings implements Registerable
{

    private const PRO_ONLY_ACF_TYPES = [
        'checkbox',
        'flexible_content',
        'gallery',
        'google_map',
        'group',
        'link',
        'page_link',
        'post_object',
        'relationship',
        'repeater',
        'taxonomy',
        'user',
    ];

    private const SUPPORTED_ACF_TYPES = [
        'text',
        'textarea',
        'number',
        'range',
        'email',
        'url',
        'password',
        'image',
        'file',
        'wysiwyg',
        'oembed',
        'select',
        'radio',
        'button_group',
        'true_false',
        'date_picker',
        'date_time_picker',
        'time_picker',
        'color_picker',
    ];

    private const FIELD_TYPE_MAP = [
        'number'           => 'numeric',
        'range'            => 'numeric',
        'url'              => 'link',
        'image'            => 'image',
        'file'             => 'library_id',
        'true_false'       => 'checkmark',
        'date_picker'      => 'date',
        'date_time_picker' => 'date',
        'color_picker'     => 'color',
        'text'             => 'excerpt',
    ];

    private Storage $storage;

    private Aggregate $column_factory;

    public function __construct(Storage $storage, Aggregate $column_factory)
    {
        $this->storage = $storage;
        $this->column_factory = $column_factory;
    }

    public function register(): void
    {
        if ( ! class_exists('acf', false)) {
            return;
        }

        add_filter('acf/field_group/additional_field_settings_tabs', [$this, 'add_tab']);
        add_action('acf/field_group/render_field_settings_tab/admin_columns', [$this, 'render_tab']);
        add_action('save_post_acf-field-group', [$this, 'on_field_group_save'], 20);
    }

    public function add_tab(array $tabs): array
    {
        $tabs['admin_columns'] = __('List Table Columns', 'codepress-admin-columns');

        return $tabs;
    }

    public function render_tab(array $field): void
    {
        $type = $field['type'] ?? '';

        if ($this->is_pro_only_field_type($type)) {
            $this->render_pro_upsell();

            return;
        }

        if ( ! $this->is_supported_field_type($type)) {
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
                'instructions' => __('Display this field as a column in the admin list table for this post type.', 'codepress-admin-columns'),
                'type'         => 'true_false',
                'name'         => 'admin_columns_enabled',
                'ui'           => 1,
            ]
        );

        acf_render_field_setting(
            $field,
            [
                'label'             => __('Column Label', 'codepress-admin-columns'),
                'type'              => 'message',
                'name'              => 'admin_columns_label_info',
                'message'           => sprintf(
                    '<strong>%s</strong><br><span style="color:#666;font-size:12px;">%s</span>',
                    esc_html($field['label'] ?? ''),
                    esc_html__('The column label is taken from the ACF field label and can be changed in the column editor.', 'codepress-admin-columns')
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );

        acf_render_field_setting(
            $field,
            [
                'label'             => '',
                'type'              => 'message',
                'name'              => 'admin_columns_upsell',
                'message'           => sprintf(
                    '<div style="background:#eaf5fc;border:1px solid #c8e3f6;border-radius:4px;padding:10px 14px;color:#184a6a;font-size:13px;line-height:1.5;"><strong>%s</strong><br>%s</div>',
                    esc_html__('More available in Admin Columns Pro', 'codepress-admin-columns'),
                    esc_html__('Make this column sortable, editable, or customize how values are displayed in the list table.', 'codepress-admin-columns')
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );

        $this->render_editor_links($field, $enabled_condition);
    }

    private function render_editor_links(array $field, array $enabled_condition): void
    {
        if (empty($field['admin_columns_enabled'])) {
            return;
        }

        $group = acf_get_field_group($field['parent']);

        if ( ! $group) {
            return;
        }

        $post_types = $this->get_post_types_from_group($group);

        if ( ! $post_types) {
            return;
        }

        $messages = [];

        foreach ($post_types as $post_type) {
            $table_id = new TableId($post_type);
            $list_screens = $this->storage->find_all_by_table_id($table_id);
            $post_type_object = get_post_type_object($post_type);
            $post_type_label = $post_type_object ? $post_type_object->labels->name : $post_type;

            foreach ($list_screens as $list_screen) {
                $url = EditorUrlFactory::create($table_id, false, $list_screen->get_id());
                $title = $list_screen->get_title() ?: $post_type_label;

                $messages[] = sprintf(
                    '<p>%s</p><p><a href="%s" class="button button-primary" target="_blank">%s &rarr;</a></p>',
                    sprintf(
                    /* translators: %s: post type or layout name */
                        esc_html__('Control column position, width, and other advanced settings in the column editor for %s.', 'codepress-admin-columns'),
                        '<strong>' . esc_html($title) . '</strong>'
                    ),
                    esc_url((string)$url),
                    esc_html__('Open Column Editor', 'codepress-admin-columns')
                );
            }
        }

        if ( ! $messages) {
            return;
        }

        acf_render_field_setting(
            $field,
            [
                'label'             => __('Customize in Admin Columns', 'codepress-admin-columns'),
                'type'              => 'message',
                'name'              => 'admin_columns_editor_link',
                'message'           => implode('', $messages),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    public function on_field_group_save(int $post_id): void
    {
        $fields = acf_get_fields($post_id);
        $group = acf_get_field_group($post_id);

        if ( ! $fields || ! $group) {
            return;
        }

        $post_types = $this->get_post_types_from_group($group);

        foreach ($fields as $field) {
            $is_enabled = ! empty($field['admin_columns_enabled'])
                          && $this->is_supported_field_type($field['type']);

            foreach ($post_types as $post_type) {
                $table_id = new TableId($post_type);

                if ($is_enabled) {
                    $label = $field['label'];

                    $this->add_column_to_list_screens($table_id, $field['name'], $label, $field['type']);
                } else {
                    $this->remove_column_from_list_screens($table_id, $field['name']);
                }
            }
        }
    }

    private function is_supported_field_type(string $type): bool
    {
        return in_array($type, self::SUPPORTED_ACF_TYPES, true);
    }

    private function is_pro_only_field_type(string $type): bool
    {
        return in_array($type, self::PRO_ONLY_ACF_TYPES, true);
    }

    private function render_pro_upsell(): void
    {
        $url = (new UtmTags(new Site(Site::PAGE_ADDON_ACF), 'acf-field-settings-upsell'))->get_url();

        printf(
            '<div class="acf-field"><div class="acf-label"></div><div class="acf-input"><div style="background:#eaf5fc;border:1px solid #c8e3f6;border-radius:4px;padding:10px 14px;color:#184a6a;font-size:13px;line-height:1.5;"><strong>%s</strong><br>%s <a href="%s" target="_blank">%s</a></div></div></div>',
            esc_html__('This field type is supported in Admin Columns Pro', 'codepress-admin-columns'),
            esc_html__('Add this field as a column with sorting, filtering, and inline editing.', 'codepress-admin-columns'),
            esc_url($url),
            esc_html__('Learn more', 'codepress-admin-columns')
        );
    }

    private function get_post_types_from_group(array $group): array
    {
        $post_types = [];

        if (empty($group['location']) || ! is_array($group['location'])) {
            return [];
        }

        foreach ($group['location'] as $or_group) {
            foreach ($or_group as $rule) {
                if ('post_type' !== ($rule['param'] ?? '')) {
                    continue;
                }

                $value = $rule['value'] ?? '';
                $operator = $rule['operator'] ?? '';

                if ('==' === $operator && 'all' !== $value && '' !== $value) {
                    $post_types[] = $value;
                }
            }
        }

        return array_unique($post_types);
    }

    private function add_column_to_list_screens(TableId $table_id, string $meta_key, string $label, string $acf_type): void
    {
        $list_screens = $this->storage->find_all_by_table_id($table_id);

        foreach ($list_screens as $list_screen) {
            if ($list_screen->is_read_only()) {
                continue;
            }

            if ($this->has_column_for_meta_key($list_screen, $meta_key)) {
                continue;
            }

            $factory = $this->find_column_factory($list_screen->get_table_screen());

            if ( ! $factory) {
                continue;
            }

            $new_column = $factory->create(new Config([
                'name'       => (string)(new ColumnIdGenerator())->generate(),
                'type'       => 'column-meta',
                'field'      => $meta_key,
                'label'      => $label,
                'field_type' => self::FIELD_TYPE_MAP[$acf_type] ?? '',
            ]));

            $columns = new ColumnCollection(iterator_to_array($list_screen->get_columns()));
            $columns->add($new_column);

            $list_screen->set_columns($columns);
            $this->storage->save($list_screen);
        }
    }

    private function remove_column_from_list_screens(TableId $table_id, string $meta_key): void
    {
        $list_screens = $this->storage->find_all_by_table_id($table_id);

        foreach ($list_screens as $list_screen) {
            if ($list_screen->is_read_only()) {
                continue;
            }

            if ( ! $this->has_column_for_meta_key($list_screen, $meta_key)) {
                continue;
            }

            $filtered = [];

            foreach ($list_screen->get_columns() as $column) {
                if ($column->get_type() === 'column-meta') {
                    $setting = $column->get_setting('field');

                    if ($setting && $setting->has_input() && (string)$setting->get_input()->get_value() === $meta_key) {
                        continue;
                    }
                }

                $filtered[] = $column;
            }

            $list_screen->set_columns(new ColumnCollection($filtered));
            $this->storage->save($list_screen);
        }
    }

    private function has_column_for_meta_key(ListScreen $list_screen, string $meta_key): bool
    {
        foreach ($list_screen->get_columns() as $column) {
            if ($column->get_type() !== 'column-meta') {
                continue;
            }

            $setting = $column->get_setting('field');

            if ($setting && $setting->has_input() && (string)$setting->get_input()->get_value() === $meta_key) {
                return true;
            }
        }

        return false;
    }

    private function find_column_factory(TableScreen $table_screen): ?ColumnFactory
    {
        foreach ($this->column_factory->create($table_screen) as $factory) {
            if ($factory->get_column_type() === 'column-meta') {
                return $factory;
            }
        }

        return null;
    }

}
