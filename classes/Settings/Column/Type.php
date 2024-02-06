<?php

namespace AC\Settings\Column;

use AC\Setting\Component\Input\Custom;
use AC\Setting\Input;
use AC\Settings\Setting;

class Type extends Setting
{

    public function __construct()
    {
        parent::__construct(
            new Custom('type'),
            __('Type', 'codepress-admin-columns'),
            __('Choose a column type.', 'codepress-admin-columns'),
        );
    }


    // TODO remove

    //    /**
    //     * @var string
    //     */
    //    private $read_more_url;
    //
    //    /**
    //     * @var string
    //     */
    //    private $type;
    //
    //    protected function define_options()
    //    {
    //        return [
    //            'type' => $this->column->get_type(),
    //        ];
    //    }
    //
    //    public function set_read_more($url)
    //    {
    //        $this->read_more_url = $url;
    //    }
    //
    //    public function create_view()
    //    {
    //        $type = $this
    //            ->create_element('select')
    //            ->set_options($this->get_grouped_columns());
    //
    //        // Tooltip
    //        $tooltip = __('Choose a column type.', 'codepress-admin-columns');
    //
    //        if (defined('WP_DEBUG') && WP_DEBUG) {
    //            $tooltip .= '<em>' . __('Type', 'codepress-admin-columns') . ': ' . $this->column->get_type() . '</em>';
    //
    //            if ($this->column->get_name()) {
    //                $tooltip .= '<em>' . __('Name', 'codepress-admin-columns') . ': ' . $this->column->get_name() . '</em>';
    //            }
    //        }
    //
    //        $args = [
    //            'setting' => $type,
    //            'label'   => __('Type', 'codepress-admin-columns'),
    //            'tooltip' => $tooltip,
    //        ];
    //
    //        if ($this->read_more_url) {
    //            $args['read_more'] = $this->read_more_url;
    //        }
    //
    //        return new View($args);
    //    }
    //
    //    /**
    //     * Returns the type label as human readable: no tags, underscores and capitalized.
    //     *
    //     * @param AC\Column|null $column
    //     *
    //     * @return string
    //     */
    //    private function get_clean_label(AC\Column $column)
    //    {
    //        $label = $column->get_label();
    //
    //        if (0 === strlen(strip_tags($label))) {
    //            $label = ucfirst(str_replace('_', ' ', $column->get_type()));
    //        }
    //
    //        return strip_tags($label);
    //    }
    //
    //    /**
    //     * @return Groups
    //     */
    //    private function column_groups()
    //    {
    //        return AC\ColumnGroups::get_groups();
    //    }
    //
    //    /**
    //     * @return array
    //     */
    //    private function get_grouped_columns()
    //    {
    //        $columns = [];
    //
    //        $list_screen = $this->column->get_list_screen();
    //
    //        $columns_types = apply_filters(
    //            'ac/column/settings/column_types',
    //            $list_screen->get_column_types(),
    //            $this->column,
    //            $list_screen
    //        );
    //
    //        // get columns and sort them
    //        foreach ($columns_types as $column) {
    //            /**
    //             * @param string $group Group slug
    //             * @param Column $column
    //             */
    //            $group = apply_filters('ac/column_group', $column->get_group(), $column);
    //
    //            // Labels with html will be replaced by its name.
    //            $columns[$group][$column->get_type()] = $this->get_clean_label($column);
    //
    //            if ( ! $column->is_original()) {
    //                natcasesort($columns[$group]);
    //            }
    //        }
    //
    //        $grouped = [];
    //
    //        // create select options
    //        foreach ($this->column_groups()->get_all() as $group) {
    //            $slug = $group['slug'];
    //
    //            // hide empty groups
    //            if ( ! isset($columns[$slug])) {
    //                continue;
    //            }
    //
    //            if ( ! isset($grouped[$slug])) {
    //                $grouped[$slug]['title'] = $group['label'];
    //            }
    //
    //            $grouped[$slug]['options'] = $columns[$slug];
    //
    //            unset($columns[$slug]);
    //        }
    //
    //        // Add columns to a "default" group when it has an invalid group assigned
    //        foreach ($columns as $_columns) {
    //            foreach ($_columns as $name => $label) {
    //                $grouped['default']['options'][$name] = $label;
    //            }
    //        }
    //
    //        return $grouped;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_type()
    //    {
    //        return $this->type;
    //    }
    //
    //    /**
    //     * @param string $type
    //     *
    //     * @return bool
    //     */
    //    public function set_type($type)
    //    {
    //        $this->type = $type;
    //
    //        return true;
    //    }
    //
    //    public function get_config(): ?array
    //    {
    //        return [
    //            'type'    => 'type',
    //            'key'     => $this->get_name(),
    //            'label'   => __('Type', 'codepress-admin-columns'),
    //            'options' => $this->get_grouped_columns(),
    //        ];
    //    }
    //
    //    /**
    //     * @var string
    //     */
    //    private $read_more_url;
    //
    //    /**
    //     * @var string
    //     */
    //    private $type;
    //
    //    protected function define_options()
    //    {
    //        return [
    //            'type' => $this->column->get_type(),
    //        ];
    //    }
    //
    //    public function set_read_more($url)
    //    {
    //        $this->read_more_url = $url;
    //    }
    //
    //    public function create_view()
    //    {
    //        $type = $this
    //            ->create_element('select')
    //            ->set_options($this->get_grouped_columns());
    //
    //        // Tooltip
    //        $tooltip = __('Choose a column type.', 'codepress-admin-columns');
    //
    //        if (defined('WP_DEBUG') && WP_DEBUG) {
    //            $tooltip .= '<em>' . __('Type', 'codepress-admin-columns') . ': ' . $this->column->get_type() . '</em>';
    //
    //            if ($this->column->get_name()) {
    //                $tooltip .= '<em>' . __('Name', 'codepress-admin-columns') . ': ' . $this->column->get_name() . '</em>';
    //            }
    //        }
    //
    //        $args = [
    //            'setting' => $type,
    //            'label'   => __('Type', 'codepress-admin-columns'),
    //            'tooltip' => $tooltip,
    //        ];
    //
    //        if ($this->read_more_url) {
    //            $args['read_more'] = $this->read_more_url;
    //        }
    //
    //        return new View($args);
    //    }
    //
    //    /**
    //     * Returns the type label as human readable: no tags, underscores and capitalized.
    //     *
    //     * @param AC\Column|null $column
    //     *
    //     * @return string
    //     */
    //    private function get_clean_label(AC\Column $column)
    //    {
    //        $label = (string)$column->get_label();
    //
    //        if (strip_tags($label) === '') {
    //            $label = ucfirst(str_replace('_', ' ', $column->get_type()));
    //        }
    //
    //        return strip_tags($label);
    //    }
    //
    //    /**
    //     * @return Groups
    //     */
    //    private function column_groups()
    //    {
    //        return AC\ColumnGroups::get_groups();
    //    }
    //
    //    /**
    //     * @return array
    //     */
    //    private function get_grouped_columns()
    //    {
    //        $columns = [];
    //
    //        $list_screen = $this->column->get_list_screen();
    //
    //        $columns_types = apply_filters(
    //            'ac/column/settings/column_types',
    //            $list_screen->get_column_types(),
    //            $this->column,
    //            $list_screen
    //        );
    //
    //        // get columns and sort them
    //        foreach ($columns_types as $column) {
    //            /**
    //             * @param string $group Group slug
    //             * @param Column $column
    //             */
    //            $group = apply_filters('ac/column_group', $column->get_group(), $column);
    //
    //            // Labels with html will be replaced by its name.
    //            $columns[$group][$column->get_type()] = $this->get_clean_label($column);
    //
    //            if ( ! $column->is_original()) {
    //                natcasesort($columns[$group]);
    //            }
    //        }
    //
    //        $grouped = [];
    //
    //        // create select options
    //        foreach ($this->column_groups()->get_all() as $group) {
    //            $slug = $group['slug'];
    //
    //            // hide empty groups
    //            if ( ! isset($columns[$slug])) {
    //                continue;
    //            }
    //
    //            if ( ! isset($grouped[$slug])) {
    //                $grouped[$slug]['title'] = $group['label'];
    //            }
    //
    //            $grouped[$slug]['options'] = $columns[$slug];
    //
    //            unset($columns[$slug]);
    //        }
    //
    //        // Add columns to a "default" group when it has an invalid group assigned
    //        foreach ($columns as $_columns) {
    //            foreach ($_columns as $name => $label) {
    //                $grouped['default']['options'][$name] = $label;
    //            }
    //        }
    //
    //        return $grouped;
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_type():string
    //    {
    //        return $this->type;
    //    }
    //
    //    /**
    //     * @param string $type
    //     *
    //     * @return bool
    //     */
    //    public function set_type($type)
    //    {
    //        $this->type = (string)$type;
    //
    //        return true;
    //    }

}