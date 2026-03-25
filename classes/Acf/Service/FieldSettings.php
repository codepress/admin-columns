<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Acf\FieldGroup\TableScreenResolver;
use AC\Column;
use AC\Column\ColumnIdGenerator;
use AC\ColumnCollection;
use AC\ColumnFactories\Aggregate;
use AC\ColumnTypeRepository;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Setting\Config;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\ListScreenIdGenerator;
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

    private ColumnTypeRepository $column_type_repository;

    private ListScreenIdGenerator $list_screen_id_generator;

    public function __construct(
        Storage $storage,
        Aggregate $column_factory,
        TableScreenResolver $table_screen_resolver,
        ColumnTypeRepository $column_type_repository,
        ListScreenIdGenerator $list_screen_id_generator
    ) {
        parent::__construct($storage, $column_factory, $table_screen_resolver);

        $this->column_type_repository = $column_type_repository;
        $this->list_screen_id_generator = $list_screen_id_generator;
    }

    protected function render_tab_early_exit(array $field, string $type): bool
    {
        if ($this->is_pro_only_field_type($type)) {
            $this->render_pro_section($field);

            return true;
        }

        return false;
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
                __('Make this column editable, sortable, and filterable - and manage your %s faster right from the overview.', 'codepress-admin-columns'),
                $content
            );
        }

        return __('Make this column editable, sortable, and filterable - and manage your content faster right from the overview.', 'codepress-admin-columns');
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
        $selected_ids = array_filter((array)($field['admin_columns_table_screens'] ?? []));

        if ($selected_ids && ! in_array((string)$table_screen->get_id(), $selected_ids, true)) {
            $this->remove_column_from_list_screens($table_screen->get_id(), $field);

            return;
        }

        $factory = $this->find_column_factory($table_screen, 'column-meta');

        if ( ! $factory) {
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
            $columns->add($this->create_column($factory, $field));

            $list_screen->set_columns($columns);
            $this->storage->save($list_screen);
        }

        if ( ! $has_writable) {
            $columns = $this->column_type_repository->find_all_by_original($table_screen);
            $columns->add($this->create_column($factory, $field));

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

    private function create_column(Column\ColumnFactory $factory, array $field): Column
    {
        return $factory->create(new Config([
            'name'       => (string)(new ColumnIdGenerator())->generate(),
            'type'       => 'column-meta',
            'field'      => $field['name'] ?? '',
            'label'      => $field['label'] ?? '',
            'field_type' => self::FIELD_TYPE_MAP[$field['type'] ?? ''] ?? '',
        ]));
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

        $selected_ids = array_filter((array)($field['admin_columns_table_screens'] ?? []));
        $rows = [];

        foreach ($table_screens as $table_screen) {
            if ($selected_ids && ! in_array((string)$table_screen->get_id(), $selected_ids, true)) {
                continue;
            }

            $list_screen = $this->storage->find_all_by_table_id($table_screen->get_id())->first();

            if ( ! $list_screen) {
                continue;
            }

            $url = EditorUrlFactory::create($table_screen->get_id(), false, $list_screen->get_id());
            $title = $table_screen->get_labels()->get_plural();

            $rows[] = sprintf(
                '<tr>'
                . '<td style="padding:6px 12px 6px 0;vertical-align:middle;">'
                . '<strong>%s</strong><br>'
                . '<span >%s</span>'
                . '</td>'
                . '<td style="text-align:right;vertical-align:middle;white-space:nowrap;padding:6px 0 6px 12px;">'
                . '<a href="%s" class="button" target="_blank">%s</a>'
                . '</td>'
                . '</tr>',
                esc_html($title),
                esc_html(sprintf(__('Edit columns for the %s list table', 'codepress-admin-columns'), $title)),
                esc_url((string)$url),
                esc_html(sprintf(__('Open %s Columns →', 'codepress-admin-columns'), $title))
            );
        }

        if ( ! $rows) {
            return;
        }

        acf_render_field_setting(
            $field,
            [
                'label'             => false,
                'type'              => 'message',
                'name'              => 'admin_columns_editor_link',
                'message'           => sprintf(
                    '<p>%s</p><table style="width:100%%;border-collapse:collapse;"><tbody>%s</tbody></table>',
                    esc_html__('Open the column editor for any selected screen to manage the label, position, width, and other advanced settings.', 'codepress-admin-columns'),
                    implode('', $rows)
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    private function render_table_screen_select(array $field, array $enabled_condition): void
    {
        $choices = $this->get_table_screen_choices($field);

        if ( ! $choices) {
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

    private function get_table_screen_choices(array $field): array
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
