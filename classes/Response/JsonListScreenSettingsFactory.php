<?php

declare(strict_types=1);

namespace AC\Response;

use AC;
use AC\ListScreen;
use AC\Setting\Encoder;
use AC\Storage\EncoderFactory;
use AC\Type\Url\Preview;

// TODO Stefan should not be the case here as well, defaults

class JsonListScreenSettingsFactory
{

    private EncoderFactory $encoder_factory;

    //private AC\ColumnTypeRepository $type_repository;

    private AC\ColumnGroups $column_groups;

    public function __construct(
        EncoderFactory $encoder_factory,
        AC\ColumnTypeRepository $type_repository,
        AC\ColumnGroups $column_groups
    ) {
        $this->encoder_factory = $encoder_factory;
        $this->type_repository = $type_repository;
        $this->column_groups = $column_groups;
    }

    public function create(ListScreen $list_screen, bool $is_stored = true, bool $is_template = false): Json
    {
        $encoder = $this->encoder_factory->create()
                                         ->set_list_screen($list_screen);

        $table_screen = $list_screen->get_table_screen();

        return (new Json())->set_parameters([
            'read_only'       => $list_screen->is_read_only(),
            'table_url'       => $is_template
                ? (string)new Preview($list_screen->get_table_url(), 'columns')
                : (string)$list_screen->get_table_url(),
            'settings'        => $encoder->encode(),
            'column_types'    => $this->get_column_types($table_screen),
            'column_settings' => $this->encode_column_settings($list_screen->get_columns()),
            'is_stored'       => $is_stored,
            'is_template'     => $is_template,
            'labels'          => [
                'singular' => $table_screen->get_labels()->get_singular(),
                'plural'   => $table_screen->get_labels()->get_plural(),
            ],
        ]);
    }

    private function get_column_types(AC\TableScreen $table_screen): array
    {
        $column_types = [];

        $original_types = $this->get_original_types($table_screen);

        foreach ($this->type_repository->find_all($table_screen) as $column) {
            $group = $this->column_groups->find($column->get_group());
            $label = $this->get_clean_label($column);

            $column_type = [
                'label'            => $label,
                'value'            => $column->get_type(),
                'group'            => $group ? $group->get_label() : __('Default', 'codepress-admin-columns'),
                'group_key'        => $column->get_group(),
                'original'         => in_array($column->get_type(), $original_types, true),
                'searchable_label' => $label,
            ];

            if ('custom' !== $column->get_group()) {
                $column_type['searchable_label'] .= ' ' . $column_type['group'];
            }

            $column_types[] = $column_type;
        }

        usort($column_types, function ($a, $b) {
            // ignore original columns
            if ($a['original'] || $b['original']) {
                return 0;
            }

            return strcasecmp($a['label'], $b['label']);
        });

        return $column_types;
    }

    private function get_original_types(AC\TableScreen $table_screen): array
    {
        $types = [];
        foreach ($this->type_repository->find_all_original($table_screen) as $column) {
            $types[] = $column->get_type();
        }

        return $types;
    }

    private function get_clean_label(AC\Column $column): string
    {
        $label = $column->get_label();

        if (strip_tags($label) === '') {
            $label = ucfirst(str_replace('_', ' ', $column->get_type()));
        }

        return strip_tags($label);
    }

    private function encode_column_settings(AC\ColumnIterator $columns): array
    {
        $settings = [];

        foreach ($columns as $column) {
            $settings[(string)$column->get_id()] = (new Encoder($column->get_settings()))->encode();
        }

        return $settings;
    }

}