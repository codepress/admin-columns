<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Acf\AcfColumnFactory;
use AC\Acf\FieldGroup\TableScreenResolver;
use AC\Column;
use AC\ColumnCollection;
use AC\ColumnTypeRepository;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\TableScreen;
use AC\Type\ListScreenIdGenerator;
use AC\Type\TableId;

class FieldSettingsSync implements Registerable
{

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

    private Storage $storage;

    private TableScreenResolver $table_screen_resolver;

    private AcfColumnFactory $acf_column_factory;

    private ColumnTypeRepository $column_type_repository;

    private ListScreenIdGenerator $list_screen_id_generator;

    public function __construct(
        Storage $storage,
        TableScreenResolver $table_screen_resolver,
        AcfColumnFactory $acf_column_factory,
        ColumnTypeRepository $column_type_repository,
        ListScreenIdGenerator $list_screen_id_generator
    ) {
        $this->storage = $storage;
        $this->table_screen_resolver = $table_screen_resolver;
        $this->acf_column_factory = $acf_column_factory;
        $this->column_type_repository = $column_type_repository;
        $this->list_screen_id_generator = $list_screen_id_generator;
    }

    public function register(): void
    {
        if ( ! class_exists('acf', false) && ! class_exists('ACF', false)) {
            return;
        }

        add_action('save_post_acf-field-group', [$this, 'on_field_group_save'], 20);
        add_action('ac/list_screen/saved', [$this, 'on_list_screen_saved'], 10, 2);
        add_action('ac/list_screen/deleted', [$this, 'on_list_screen_deleted']);
    }

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
            $selected_table_screens = $field['admin_columns_table_screens'] ?? [];
            $is_enabled = ! empty($field['admin_columns_enabled'])
                          && $this->is_field_supported($field);

            foreach ($table_screens as $table_screen) {
                if ($is_enabled && (empty($selected_table_screens) || in_array((string)$table_screen->get_id(), (array)$selected_table_screens, true))) {
                    $this->add_column_in_list_screens($table_screen, $field);
                } else {
                    $this->remove_column_from_list_screens($table_screen->get_id(), $field);
                }
            }
        }
    }

    public function on_list_screen_saved(ListScreen $list_screen, ?ListScreen $previous): void
    {
        $table_id = $list_screen->get_table_screen()->get_id();

        foreach ($this->get_supported_fields_for_table($table_id) as $field) {
            $was_present = $previous && $this->has_column_for_field($previous, $field);
            $is_present = $this->has_column_for_field($list_screen, $field);

            if ($was_present && ! $is_present && ! $this->field_has_column_in_other_list_screens($table_id, $list_screen->get_id(), $field)) {
                $this->disable_field_for_table($field, $table_id);
            }

            if ( ! $was_present && $is_present && ! $this->is_field_enabled_for_table($field, $table_id)) {
                $this->enable_field_for_table($field, $table_id);
            }
        }
    }

    public function on_list_screen_deleted(ListScreen $list_screen): void
    {
        $table_id = $list_screen->get_table_screen()->get_id();

        foreach ($this->get_enabled_fields_for_table($table_id) as $field) {
            if ( ! $this->has_column_for_field($list_screen, $field)) {
                continue;
            }

            if ( ! $this->field_has_column_in_other_list_screens($table_id, $list_screen->get_id(), $field)) {
                $this->disable_field_for_table($field, $table_id);
            }
        }
    }

    private function add_column_in_list_screens(TableScreen $table_screen, array $field): void
    {
        $column = $this->acf_column_factory->create($table_screen, $field);

        if ( ! $column) {
            return;
        }

        $has_writable = false;

        foreach ($this->storage->find_all_by_table_id($table_screen->get_id()) as $list_screen) {
            if ($list_screen->is_read_only()) {
                continue;
            }

            $has_writable = true;

            if ($this->has_column_for_field($list_screen, $field)) {
                continue;
            }

            $columns = ColumnCollection::from_iterator($list_screen->get_columns());
            $columns->add($column);

            $list_screen->set_columns($columns);
            $this->storage->save($list_screen);
        }

        if ( ! $has_writable) {
            $columns = $this->column_type_repository->find_all_by_original($table_screen);
            $columns->add($column);

            $this->storage->save(
                new ListScreen(
                    $this->list_screen_id_generator->generate(),
                    $this->get_field_group_title($field),
                    $table_screen,
                    $columns
                )
            );
        }
    }

    private function remove_column_from_list_screens(TableId $table_id, array $field): void
    {
        foreach ($this->storage->find_all_by_table_id($table_id) as $list_screen) {
            if ($list_screen->is_read_only()) {
                continue;
            }

            $this->remove_column_from_single_list_screen($list_screen, $field);
        }
    }

    private function remove_column_from_single_list_screen(ListScreen $list_screen, array $field): void
    {
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

    /**
     * @param mixed $exclude_id
     */
    private function field_has_column_in_other_list_screens(TableId $table_id, $exclude_id, array $field): bool
    {
        foreach ($this->storage->find_all_by_table_id($table_id) as $other) {
            if ((string)$other->get_id() === (string)$exclude_id) {
                continue;
            }

            if ($this->has_column_for_field($other, $field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array[]
     */
    private function get_enabled_fields_for_table(TableId $table_id): array
    {
        return $this->get_fields_for_table($table_id, static function (array $field): bool {
            return ! empty($field['admin_columns_enabled']);
        });
    }

    private function has_column_for_field(ListScreen $list_screen, array $field): bool
    {
        foreach ($list_screen->get_columns() as $column) {
            if ($this->is_column_for_field($column, $field)) {
                return true;
            }
        }

        return false;
    }

    private function is_column_for_field(Column $column, array $field): bool
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

    private function is_field_supported(array $field): bool
    {
        if ($this->is_sub_field($field) || in_array($field['type'], self::PRO_ONLY_ACF_TYPES, true)) {
            return false;
        }

        if ($field['type'] === 'select' && ! empty($field['multiple'])) {
            return false;
        }

        return in_array($field['type'], self::SUPPORTED_ACF_TYPES, true);
    }

    private function is_sub_field(array $field): bool
    {
        return isset($field['parent_repeater'])
               || isset($field['parent_group'])
               || isset($field['parent_layout'])
               || isset($field['_clone']);
    }

    private function get_field_group_title(array $field): string
    {
        $parent = $field['parent'] ?? 0;
        $group = acf_get_field_group($parent) ?: [];

        return $group['title'] ?? '';
    }

    /**
     * @return array[]
     */
    private function get_supported_fields_for_table(TableId $table_id): array
    {
        return $this->get_fields_for_table($table_id, [$this, 'is_field_supported']);
    }

    /**
     * @return array[]
     */
    private function get_fields_for_table(TableId $table_id, callable $filter): array
    {
        $fields = [];

        foreach (acf_get_field_groups() as $group) {
            $table_screens = $this->table_screen_resolver->resolve($group);

            $matches = false;

            foreach ($table_screens as $table_screen) {
                if ((string)$table_screen->get_id() === (string)$table_id) {
                    $matches = true;

                    break;
                }
            }

            if ( ! $matches) {
                continue;
            }

            foreach (acf_get_fields($group['ID']) as $field) {
                if ($filter($field)) {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }

    private function is_field_enabled_for_table(array $field, TableId $table_id): bool
    {
        if (empty($field['admin_columns_enabled'])) {
            return false;
        }

        $table_screens = array_filter((array)($field['admin_columns_table_screens'] ?? []));

        return empty($table_screens) || in_array((string)$table_id, $table_screens, true);
    }

    private function enable_field_for_table(array $field, TableId $table_id): void
    {
        $field['admin_columns_enabled'] = 1;

        $table_screens = array_filter((array)($field['admin_columns_table_screens'] ?? []));

        if ( ! in_array((string)$table_id, $table_screens, true)) {
            $table_screens[] = (string)$table_id;
        }

        $field['admin_columns_table_screens'] = array_values($table_screens);

        acf_update_field($field);
    }

    private function disable_field_for_table(array $field, TableId $table_id): void
    {
        $table_screens = array_filter((array)($field['admin_columns_table_screens'] ?? []));
        $table_screens = array_values(array_diff($table_screens, [(string)$table_id]));

        $field['admin_columns_table_screens'] = $table_screens;

        if (empty($table_screens)) {
            $field['admin_columns_enabled'] = 0;
        }

        acf_update_field($field);
    }

}
