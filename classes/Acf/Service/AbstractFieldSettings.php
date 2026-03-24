<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Acf\FieldGroup\TableScreenResolver;
use AC\Column;
use AC\Column\ColumnFactory;
use AC\ColumnCollection;
use AC\ColumnFactories\Aggregate;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\TableId;

abstract class AbstractFieldSettings implements Registerable
{

    protected Storage $storage;

    protected Aggregate $column_factory;

    protected TableScreenResolver $table_screen_resolver;

    public function __construct(Storage $storage, Aggregate $column_factory, TableScreenResolver $table_screen_resolver)
    {
        $this->storage = $storage;
        $this->column_factory = $column_factory;
        $this->table_screen_resolver = $table_screen_resolver;
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

        if ($this->render_tab_early_exit($field, $type)) {
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

        $this->render_tab_content($field, $enabled_condition);
        $this->render_editor_links($field, $enabled_condition);
    }

    protected function render_tab_early_exit(array $field, string $type): bool
    {
        return false;
    }

    abstract protected function render_tab_content(array $field, array $enabled_condition): void;

    abstract protected function is_field_type_supported(string $type): bool;

    abstract protected function is_column_for_field(Column $column, array $field): bool;

    abstract protected function add_column_in_list_screens(TableScreen $table_screen, array $field): void;

    public function on_field_group_save(int $post_id): void
    {
        $fields = acf_get_fields($post_id);
        $group = acf_get_field_group($post_id);

        if ( ! $fields || ! $group) {
            return;
        }

        $table_screens = $this->table_screen_resolver->resolve($group);

        if ( ! $table_screens) {
            return;
        }

        foreach ($fields as $field) {
            $is_enabled = ! empty($field['admin_columns_enabled'])
                          && $this->is_field_type_supported($field['type']);

            foreach ($table_screens as $table_screen) {
                if ($is_enabled) {
                    $this->add_column_in_list_screens($table_screen, $field);
                } else {
                    $this->remove_column_from_list_screens($table_screen->get_id(), $field);
                }
            }
        }
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

    protected function get_column_label_for_list_screen(ListScreen $list_screen, array $field): ?string
    {
        foreach ($list_screen->get_columns() as $column) {
            if ($this->is_column_for_field($column, $field)) {
                $label_setting = $column->get_setting('label');

                if ($label_setting && $label_setting->has_input()) {
                    return $label_setting->get_input()->get_value();
                }
            }
        }

        return null;
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

        $links = [];

        foreach ($table_screens as $table_screen) {
            $table_id = $table_screen->get_id();

            foreach ($this->storage->find_all_by_table_id($table_id) as $list_screen) {
                if ( ! $this->has_column_for_field($list_screen, $field)) {
                    continue;
                }

                $url = EditorUrlFactory::create($table_id, false, $list_screen->get_id());
                $title = $list_screen->get_title() ?: $table_screen->get_labels()->get_plural();

                $links[(string)$list_screen->get_id()] = sprintf(
                    '<a href="%s" target="_blank" class="button button-secondary">%s</a>',
                    esc_url((string)$url),
                    esc_html($title)
                );
            }
        }

        if ( ! $links) {
            return;
        }

        $selected_ids = array_filter((array)($field['admin_columns_list_screens'] ?? []));

        if ($selected_ids) {
            $ordered = [];

            foreach ($selected_ids as $id) {
                if (isset($links[$id])) {
                    $ordered[$id] = $links[$id];
                }
            }

            $links = $ordered + array_diff_key($links, $ordered);
        }

        $message = sprintf(
            '<p>%s</p>',
            implode(' ', $links)
        );

        acf_render_field_setting(
            $field,
            [
                'label'             => __('Customize this column per view', 'codepress-admin-columns'),
                'instructions'      => __('Opens the column editor for the selected view.', 'codepress-admin-columns'),
                'type'              => 'message',
                'name'              => 'admin_columns_editor_link',
                'message'           => $message,
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    protected function has_column_for_field(ListScreen $list_screen, array $field): bool
    {
        foreach ($list_screen->get_columns() as $column) {
            if ($this->is_column_for_field($column, $field)) {
                return true;
            }
        }

        return false;
    }

    protected function remove_column_from_list_screens(TableId $table_id, array $field): void
    {
        foreach ($this->storage->find_all_by_table_id($table_id) as $list_screen) {
            if ($list_screen->is_read_only()) {
                continue;
            }

            $filtered = [];
            $found = false;

            foreach ($list_screen->get_columns() as $column) {
                if ($this->is_column_for_field($column, $field)) {
                    $found = true;

                    continue;
                }

                $filtered[] = $column;
            }

            if ($found) {
                $list_screen->set_columns(new ColumnCollection($filtered));
                $this->storage->save($list_screen);
            }
        }
    }

    protected function find_column_factory(TableScreen $table_screen, string $column_type): ?ColumnFactory
    {
        foreach ($this->column_factory->create($table_screen) as $factory) {
            if ($factory->get_column_type() === $column_type) {
                return $factory;
            }
        }

        return null;
    }

}
