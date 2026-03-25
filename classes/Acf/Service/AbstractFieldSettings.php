<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Acf\FieldGroup\TableScreenResolver;
use AC\Column;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\TableScreen;

abstract class AbstractFieldSettings implements Registerable
{

    protected Storage $storage;

    protected TableScreenResolver $table_screen_resolver;

    abstract protected function render_tab_content(array $field, array $enabled_condition): void;

    abstract protected function is_field_supported(array $field): bool;

    abstract protected function is_column_for_field(Column $column, array $field): bool;

    abstract protected function render_editor_links(array $field, array $enabled_condition): void;

    public function __construct(Storage $storage, TableScreenResolver $table_screen_resolver)
    {
        $this->storage = $storage;
        $this->table_screen_resolver = $table_screen_resolver;
    }

    public function register(): void
    {
        if ( ! class_exists('acf', false) && ! class_exists('ACF', false)) {
            return;
        }

        add_filter('acf/field_group/additional_field_settings_tabs', [$this, 'add_tab']);
        add_action('acf/field_group/render_field_settings_tab/admin_columns', [$this, 'render_tab']);
    }

    public function add_tab(array $tabs): array
    {
        $tabs['admin_columns'] = __('List Table Columns', 'codepress-admin-columns');

        return $tabs;
    }

    public function render_tab(array $field): void
    {
        if ( ! $this->is_field_supported($field)) {
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

        $this->render_tab_content($field, $enabled_condition);
        $this->render_editor_links($field, $enabled_condition);
    }

    protected function is_sub_field(array $field): bool
    {
        return isset($field['parent_repeater'])
               || isset($field['parent_group'])
               || isset($field['parent_layout'])
               || isset($field['_clone']);
    }

    protected function get_toggle_instructions(array $field): string
    {
        $base = __('Display this field as a column in the admin list table.', 'codepress-admin-columns');

        $label = $this->get_stored_column_label($field);

        if ($label === null) {
            return $base;
        }

        return $base . sprintf(
                '<br>' . __('Column label: %s', 'codepress-admin-columns'),
                '<strong>' . esc_html($label) . '</strong>'
            );
    }

    /**
     * @return TableScreen[]
     */
    protected function resolve_table_screens(array $field): array
    {
        static $cache = [];

        $parent = $field['parent'] ?? 0;

        if ( ! isset($cache[$parent])) {
            $group = acf_get_field_group($parent);
            $cache[$parent] = $group ? $this->table_screen_resolver->resolve($group) : [];
        }

        return $cache[$parent];
    }

    protected function get_stored_column_label(array $field): ?string
    {
        foreach ($this->resolve_table_screens($field) as $table_screen) {
            foreach ($this->storage->find_all_by_table_id($table_screen->get_id()) as $list_screen) {
                $label = $this->get_column_label_for_list_screen($list_screen, $field);

                if ($label !== null) {
                    return $label;
                }
            }
        }

        return null;
    }

    protected function find_column_for_field(ListScreen $list_screen, array $field): ?Column
    {
        foreach ($list_screen->get_columns() as $column) {
            if ($this->is_column_for_field($column, $field)) {
                return $column;
            }
        }

        return null;
    }

    protected function get_column_label_for_list_screen(ListScreen $list_screen, array $field): ?string
    {
        $column = $this->find_column_for_field($list_screen, $field);

        if ( ! $column) {
            return null;
        }

        $label_setting = $column->get_setting('label');

        if ($label_setting && $label_setting->has_input()) {
            return $label_setting->get_input()->get_value();
        }

        return null;
    }

    protected function has_column_for_field(ListScreen $list_screen, array $field): bool
    {
        return $this->find_column_for_field($list_screen, $field) !== null;
    }

    protected function render_table_screen_select(array $field, array $enabled_condition): void
    {
        $choices = $this->get_table_screen_choices($field);

        if (count($choices) <= 1) {
            return;
        }

        acf_render_field_setting(
            $field,
            [
                'label'             => __('Admin List Tables', 'codepress-admin-columns'),
                'instructions'      => __('Select which list table should include this column.', 'codepress-admin-columns'),
                'type'              => 'select',
                'name'              => 'admin_columns_table_screens',
                'choices'           => $choices,
                'default_value'     => array_keys($choices),
                'multiple'          => 1,
                'ui'                => 1,
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    protected function get_table_screen_choices(array $field): array
    {
        static $cache = [];

        $parent = $field['parent'] ?? 0;

        if (isset($cache[$parent])) {
            return $cache[$parent];
        }

        $table_screens = $this->resolve_table_screens($field);
        $choices = [];

        foreach ($table_screens as $table_screen) {
            $choices[(string)$table_screen->get_id()] = $table_screen->get_labels()->get_plural();
        }

        $cache[$parent] = $choices;

        return $choices;
    }

}
