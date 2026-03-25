<?php

declare(strict_types=1);

namespace AC\Acf\Service;

use AC\Acf\FieldGroup\TableScreenResolver;
use AC\Column;
use AC\ListScreen;
use AC\ListScreenRepository\Storage;
use AC\Registerable;
use AC\TableScreen;
use AC\Type\EditorUrlFactory;
use AC\Type\Uri;
use AC\Type\Url\Site;
use AC\Type\Url\UtmTags;

class FieldSettings implements Registerable
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

    private Storage $storage;

    private TableScreenResolver $table_screen_resolver;

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
        if ($this->is_pro_only_field($field)) {
            $this->render_pro_section($field);

            return;
        }

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
                'instructions' => __('Display this field as a column in the admin list table.', 'codepress-admin-columns'),
                'type'         => 'true_false',
                'name'         => 'admin_columns_enabled',
                'ui'           => 1,
            ]
        );

        $this->render_tab_content($field, $enabled_condition);
        $this->render_editor_links($field, $enabled_condition);
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

    private function render_tab_content(array $field, array $enabled_condition): void
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
        if ($field['type'] === 'select' && ! empty($field['multiple'])) {
            return true;
        }

        return $this->is_sub_field($field) || in_array($field['type'], self::PRO_ONLY_ACF_TYPES, true);
    }

    private function is_field_supported(array $field): bool
    {
        return in_array($field['type'], self::SUPPORTED_ACF_TYPES, true);
    }

    private function is_sub_field(array $field): bool
    {
        return isset($field['parent_repeater'])
               || isset($field['parent_group'])
               || isset($field['parent_layout'])
               || isset($field['_clone']);
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

    private function render_editor_links(array $field, array $enabled_condition): void
    {
        $table_screens = $this->resolve_table_screens($field);

        if ( ! $table_screens) {
            return;
        }

        $selected_table_screens = $field['admin_columns_table_screens'] ?? null;

        $is_single_table_screen = count($table_screens) === 1;

        $rows = [];

        foreach ($table_screens as $table_screen) {
            if ($selected_table_screens && ! in_array((string)$table_screen->get_id(), (array)$selected_table_screens, true)) {
                continue;
            }

            $title = $table_screen->get_labels()->get_plural();

            $label = $is_single_table_screen
                ? __('Edit Column →', 'codepress-admin-columns')
                : sprintf(__('Edit %s Column →', 'codepress-admin-columns'), $title);

            $rows[] = sprintf(
                '<a href="%s" style="margin-right: 8px;display:inline-block;" class="button" target="_blank">%s</a>',
                esc_url((string)$this->create_editor_url($table_screen, $field)),
                $label
            );
        }

        if ( ! $rows) {
            $this->render_editor_link_setting($field, $this->create_editor_button(reset($table_screens), $field), $enabled_condition);

            return;
        }

        $this->render_editor_link_setting($field, implode('', $rows), $enabled_condition);
    }

    private function create_editor_url(TableScreen $table_screen, array $field): Uri
    {
        $list_screen = $this->find_list_screen_with_field($table_screen, $field);

        $url = EditorUrlFactory::create($table_screen->get_id(), false, $list_screen ? $list_screen->get_id() : null);

        if ($list_screen) {
            $column = $this->find_column_for_field($list_screen, $field);

            if ($column) {
                $url = $url->with_arg('open_columns', (string)$column->get_id());
            }
        }

        return $url;
    }

    private function create_editor_button(TableScreen $table_screen, array $field): string
    {
        return sprintf(
            '<a href="%s" class="button" target="_blank">%s</a>',
            esc_url((string)$this->create_editor_url($table_screen, $field)),
            esc_html__('Open Column Editor', 'codepress-admin-columns')
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

    private function render_editor_link_setting(array $field, string $buttons, array $enabled_condition): void
    {
        acf_render_field_setting(
            $field,
            [
                'label'             => __('Manage Column Settings', 'codepress-admin-columns'),
                'type'              => 'message',
                'name'              => 'admin_columns_editor_link',
                'message'           => sprintf(
                    '<p class="description">%s</p>%s',
                    esc_html__('Open the column editor to manage the label, position, width, and other advanced settings.', 'codepress-admin-columns'),
                    $buttons
                ),
                'conditional_logic' => $enabled_condition,
            ]
        );
    }

    /**
     * @return TableScreen[]
     */
    private function resolve_table_screens(array $field): array
    {
        static $cache = [];

        $parent = $field['parent'] ?? 0;

        if ( ! isset($cache[$parent])) {
            $group = acf_get_field_group($parent);
            $cache[$parent] = $group ? $this->table_screen_resolver->resolve($group) : [];
        }

        return $cache[$parent];
    }

    private function find_column_for_field(ListScreen $list_screen, array $field): ?Column
    {
        foreach ($list_screen->get_columns() as $column) {
            if ($this->is_column_for_field($column, $field)) {
                return $column;
            }
        }

        return null;
    }

    private function has_column_for_field(ListScreen $list_screen, array $field): bool
    {
        return $this->find_column_for_field($list_screen, $field) !== null;
    }

    private function render_table_screen_select(array $field, array $enabled_condition): void
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

}
