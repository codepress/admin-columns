<?php

declare(strict_types=1);

namespace AC\Acf;

use AC\Column;
use AC\Column\ColumnIdGenerator;
use AC\ColumnFactories\Aggregate;
use AC\Setting\ComponentFactory\DateSaveFormat;
use AC\Setting\ComponentFactory\FieldType;
use AC\Setting\Config;
use AC\TableScreen;

class AcfColumnFactory
{

    private const FIELD_TYPE_MAP = [
        'button_group'     => FieldType::TYPE_SELECT,
        'checkbox'         => FieldType::TYPE_SELECT,
        'color_picker'     => FieldType::TYPE_COLOR,
        'date_picker'      => FieldType::TYPE_DATE,
        'date_time_picker' => FieldType::TYPE_DATE,
        'file'             => FieldType::TYPE_MEDIA,
        'image'            => FieldType::TYPE_IMAGE,
        'number'           => FieldType::TYPE_NUMERIC,
        'oembed'           => FieldType::TYPE_URL,
        'page_link'        => FieldType::TYPE_POST,
        'post_object'      => FieldType::TYPE_POST,
        'radio'            => FieldType::TYPE_SELECT,
        'range'            => FieldType::TYPE_NUMERIC,
        'select'           => FieldType::TYPE_SELECT,
        'text'             => FieldType::TYPE_TEXT,
        'true_false'       => FieldType::TYPE_BOOLEAN,
        'url'              => FieldType::TYPE_URL,
        'user'             => FieldType::TYPE_USER,
        'wysiwyg'          => FieldType::TYPE_HTML,
    ];

    private Aggregate $column_factories;

    public function __construct(Aggregate $column_factories)
    {
        $this->column_factories = $column_factories;
    }

    public function create(TableScreen $table_screen, array $field): ?Column
    {
        $factory = $this->find_column_factory($table_screen);

        if ( ! $factory) {
            return null;
        }

        $config = $this->create_config($field);

        if (null === $config) {
            return null;
        }

        return $factory->create(new Config($config));
    }

    private function create_config(array $field): ?array
    {
        $meta_key = $field['name'] ?? null;

        if (empty($meta_key)) {
            return null;
        }

        $type = $field['type'] ?? '';

        $config = [
            'name'       => (string)(new ColumnIdGenerator())->generate(),
            'type'       => 'column-meta',
            'field'      => $meta_key,
            'label'      => $this->resolve_label($field, $type, $meta_key),
            'field_type' => self::FIELD_TYPE_MAP[$type] ?? '',
        ];

        switch ($type) {
            case 'select':
            case 'button_group':
                $config['is_multiple'] = ! empty($field['multiple']) ? 'on' : 'off';
                $config['select_options'] = self::encode_choices($field);
                break;
            case 'checkbox':
                $config['is_multiple'] = 'on';
                $config['select_options'] = self::encode_choices($field);
                break;
            case 'radio':
                $config['select_options'] = self::encode_choices($field);
                break;
            case 'file':
                if ( ! empty($field['multiple'])) {
                    $config['is_multiple'] = 'on';
                }
                break;
            case 'date_picker':
                $config['date_save_format'] = Service\DateSaveFormat::DATE_FORMAT;
                break;
            case 'date_time_picker':
                $display_format = $field['display_format'] ?? null;

                $config['date_save_format'] = DateSaveFormat::FORMAT_DATETIME;
                $config['date_format'] = $display_format ?: 'wp_date_format';
                break;
        }

        $prepend = $field['prepend'] ?? '';
        $append = $field['append'] ?? '';

        if ($prepend !== '') {
            $config['before'] = $prepend;
        }

        if ($append !== '') {
            $config['after'] = $append;
        }

        return $config;
    }

    private static function encode_choices(array $field): string
    {
        $choices = $field['choices'] ?? [];

        if ( ! $choices) {
            return '';
        }

        $options = [];

        foreach ($choices as $value => $label) {
            $options[] = [
                'value' => (string)$value,
                'label' => (string)$label,
            ];
        }

        return (string)json_encode($options);
    }

    private function resolve_label(array $field, string $type, string $meta_key): string
    {
        $label = trim($field['label'] ?? '');

        if ($label !== '') {
            return $label;
        }

        $type_object = acf_get_field_type($type);

        if ($type_object && ! empty($type_object->label)) {
            return $type_object->label;
        }

        return $meta_key;
    }

    private function find_column_factory(TableScreen $table_screen): ?Column\ColumnFactory
    {
        foreach ($this->column_factories->create($table_screen) as $factory) {
            if ($factory->get_column_type() === 'column-meta') {
                return $factory;
            }
        }

        return null;
    }

}
