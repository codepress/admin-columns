<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\OrSpecification;
use AC\Expression\StringComparisonSpecification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Option;
use AC\Setting\Type\Value;

class CustomFieldType extends Recursive
{

    public const NAME = 'field_type';

    public const TYPE_DEFAULT = '';
    public const TYPE_ARRAY = 'array';
    public const TYPE_BOOLEAN = 'checkmark';
    public const TYPE_COLOR = 'color';
    public const TYPE_COUNT = 'count';
    public const TYPE_DATE = 'date';
    public const TYPE_IMAGE = 'image';
    public const TYPE_MEDIA = 'library_id';
    public const TYPE_NON_EMPTY = 'has_content';
    public const TYPE_NUMERIC = 'numeric';
    public const TYPE_POST = 'title_by_id';
    public const TYPE_TEXT = 'excerpt';
    public const TYPE_URL = 'link';
    public const TYPE_USER = 'user_by_id';

    public function __construct()
    {
        parent::__construct(
            'field_type',
            __('Field Type', 'codepress-admin-columns'),
            __('This will determine how the value will be displayed.', 'codepress-admin-columns'),
            AC\Setting\Component\Input\OptionFactory::create_select(
                'field_type',
                $this->get_field_type_options(),
                '',
                null,
                true
            )
        );
    }

    public function get_children(): SettingCollection
    {
        return new SettingCollection([
            // TODO
            new StringLimit(
                StringComparisonSpecification::equal(self::TYPE_TEXT)
            ),
            new NumberFormat(
                StringComparisonSpecification::equal(self::TYPE_NUMERIC)
            ),
            new Date(
                StringComparisonSpecification::equal(self::TYPE_DATE)
            ),
            new DateFormat(
                StringComparisonSpecification::equal(self::TYPE_DATE)
            ),
            new Image(
                new OrSpecification([
                    StringComparisonSpecification::equal(self::TYPE_IMAGE),
                    StringComparisonSpecification::equal(self::TYPE_MEDIA),
                ])
            ),
            new MediaLink(
                new OrSpecification([
                    StringComparisonSpecification::equal(self::TYPE_IMAGE),
                    StringComparisonSpecification::equal(self::TYPE_MEDIA),
                ])
            ),
            new LinkLabel(
                StringComparisonSpecification::equal(self::TYPE_URL)
            ),
        ]);
    }

    protected function get_field_type_options(): OptionCollection
    {
        $groups = [
            'basic'      => __('Basic', 'codepress-admin-columns'),
            'relational' => __('Relational', 'codepress-admin-columns'),
            'choice'     => __('Choice', 'codepress-admin-columns'),
            'multiple'   => __('Multiple', 'codepress-admin-columns'),
            'custom'     => __('Custom', 'codepress-admin-columns'),
        ];

        $collection = new OptionCollection();
        $collection->add(
            new AC\Setting\Component\Type\Option(__('Default', 'codepress-admin-columns'), '')
        );

        foreach ($this->get_field_types() as $group => $options) {
            foreach ($options as $value => $label) {
                $collection->add(
                    new AC\Setting\Component\Type\Option(
                        $label,
                        $value,
                        $groups[$group] ?? $group
                    )
                );
            }
        }

        return $collection;
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        switch ((string)$options->get($this->name)) {
            case self::TYPE_COLOR:
                $value = (new AC\Setting\Formatter\Color())->format($value, $options);

                break;
            case self::TYPE_DATE:
                $timestamp = ac_helper()->date->strtotime($value->get_value());

                if ($timestamp) {
                    $value = $value->with_value(date('c', $timestamp));
                }

                break;
            case self::TYPE_IMAGE :
                // TODO David, in the old formatter, we could return a valueCollection here and it would format each option, can we deal with that? Old example code below
                //                return new Collection($this->get_values_from_array_or_string($value));
                $value = new Value((int)$value->get_value(), $value->get_value());
        }

        return parent::format($value, $options);
    }

    // TODO

    //    public function get_dependent_settings()
    //    {
    //        $settings = [];
    //
    //        switch ($this->get_field_type()) {
    //            case self::TYPE_DATE :
    //                $settings[] = new Date($this->column);
    //                $settings[] = new DateFormat($this->column);
    //
    //                break;
    //            case self::TYPE_IMAGE  :
    //            case self::TYPE_MEDIA :
    //                $settings[] = new Image($this->column);
    //                $settings[] = new MediaLink($this->column);
    //
    //                break;
    //            case self::TYPE_TEXT :
    //                $settings[] = new StringLimit($this->column);
    //
    //                break;
    //            case self::TYPE_URL :
    //                $settings[] = new LinkLabel($this->column);
    //
    //                break;
    //            case self::TYPE_NUMERIC :
    //                $settings[] = new NumberFormat($this->column);
    //                break;
    //        }
    //
    //        return $settings;
    //    }
    //
    //    public function create_view()
    //    {
    //        $select = $this->create_element('select');
    //
    //        $select->set_attribute('data-refresh', 'column')
    //               ->set_options($this->get_grouped_options())
    //               ->set_description($this->get_description());
    //
    //        $tooltip = __('This will determine how the value will be displayed.', 'codepress-admin-columns');
    //
    //        if ( ! in_array($this->get_field_type(), [null, ''], true)) {
    //            $tooltip .= '<em>' . __('Type', 'codepress-admin-columns') . ': ' . $this->get_field_type() . '</em>';
    //        }
    //
    //        return new View([
    //            'label'   => __('Field Type', 'codepress-admin-columns'),
    //            'tooltip' => $tooltip,
    //            'setting' => $select,
    //        ]);
    //    }
    //
    //    private function get_description_object_ids($input)
    //    {
    //        $description = sprintf(
    //            __("Uses one or more %s IDs to display information about it.", 'codepress-admin-columns'),
    //            '<em>' . $input . '</em>'
    //        );
    //        $description .= ' ' . __("Multiple IDs should be separated by commas.", 'codepress-admin-columns');
    //
    //        return $description;
    //    }
    //
    //    public function get_description()
    //    {
    //        $description = false;
    //
    //        switch ($this->get_field_type()) {
    //            case self::TYPE_POST :
    //                $description = $this->get_description_object_ids(__("Post Type", 'codepress-admin-columns'));
    //
    //                break;
    //            case self::TYPE_USER :
    //                $description = $this->get_description_object_ids(__("User", 'codepress-admin-columns'));
    //
    //                break;
    //        }
    //
    //        return $description;
    //    }
    //
    //    /**
    //     * Get possible field types
    //     * @return array
    //     */
    protected function get_field_types(): array
    {
        $grouped_types = [
            'basic'      => [
                self::TYPE_COLOR   => __('Color', 'codepress-admin-columns'),
                self::TYPE_DATE    => __('Date', 'codepress-admin-columns'),
                self::TYPE_TEXT    => __('Text', 'codepress-admin-columns'),
                self::TYPE_IMAGE   => __('Image', 'codepress-admin-columns'),
                self::TYPE_URL     => __('URL', 'codepress-admin-columns'),
                self::TYPE_NUMERIC => __('Number', 'codepress-admin-columns'),
            ],
            'choice'     => [
                self::TYPE_NON_EMPTY => __('Has Content', 'codepress-admin-columns'),
                self::TYPE_BOOLEAN   => __('True / False', 'codepress-admin-columns'),
            ],
            'relational' => [
                self::TYPE_MEDIA => __('Media', 'codepress-admin-columns'),
                self::TYPE_POST  => __('Post', 'codepress-admin-columns'),
                self::TYPE_USER  => __('User', 'codepress-admin-columns'),
            ],
            'multiple'   => [
                self::TYPE_COUNT => __('Number of Fields', 'codepress-admin-columns'),
                self::TYPE_ARRAY => sprintf(
                    '%s / %s',
                    __('Multiple Values', 'codepress-admin-columns'),
                    __('Serialized', 'codepress-admin-columns')
                ),
            ],
        ];

        /**
         * Filter the available custom field types for the meta (custom field) field
         *
         * @param array $field_types Available custom field types ([type] => [label])
         *
         * @since 3.0
         */
        $grouped_types['custom'] = apply_filters('ac/column/custom_field/field_types', []);

        foreach ($grouped_types as $k => $fields) {
            natcasesort($grouped_types[$k]);
        }

        return $grouped_types;
    }
    //
    //    /**
    //     * @return array
    //     */
    //    private function get_grouped_options()
    //    {
    //        $field_types = $this->get_field_type_options();
    //
    //        foreach ($field_types as $fields) {
    //            asort($fields);
    //        }
    //
    //        $groups = [
    //            'basic'      => __('Basic', 'codepress-admin-columns'),
    //            'relational' => __('Relational', 'codepress-admin-columns'),
    //            'choice'     => __('Choice', 'codepress-admin-columns'),
    //            'multiple'   => __('Multiple', 'codepress-admin-columns'),
    //            'custom'     => __('Custom', 'codepress-admin-columns'),
    //        ];
    //
    //        $grouped_options = [];
    //        foreach ($field_types as $group => $fields) {
    //            if ( ! $fields) {
    //                continue;
    //            }
    //
    //            $grouped_options[$group]['title'] = $groups[$group];
    //            $grouped_options[$group]['options'] = $fields;
    //        }
    //
    //        // Default option comes first
    //        $grouped_options = array_merge(['' => __('Default', 'codepress-admin-columns')], $grouped_options);
    //
    //        return $grouped_options;
    //    }
    //
    //    /**
    //     * @param string|array $string
    //     *
    //     * @return array
    //     */
    //    private function get_values_from_array_or_string($string)
    //    {
    //        $string = ac_helper()->array->implode_recursive(',', $string);
    //
    //        return ac_helper()->string->comma_separated_to_array($string);
    //    }
    //
    //    /**
    //     * @param string|array $mixed
    //     *
    //     * @return array
    //     */
    //    private function get_ids_from_array_or_string($mixed): array
    //    {
    //        $string = ac_helper()->array->implode_recursive(',', $mixed);
    //
    //        return ac_helper()->string->string_to_array_integers($string);
    //    }
    //
    //    public function format($value, $original_value)
    //    {
    //        switch ($this->get_field_type()) {
    //            case self::TYPE_ARRAY :
    //                if (ac_helper()->array->is_associative($value)) {
    //                    return sprintf(
    //                        '<div data-component="ac-json" data-json="%s" ></div>',
    //                        esc_attr(json_encode($value))
    //                    );
    //                }
    //
    //                return ac_helper()->array->implode_recursive(__(', '), $value);
    //            case self::TYPE_DATE :
    //                $timestamp = ac_helper()->date->strtotime($value);
    //                if ($timestamp) {
    //                    return date('c', $timestamp);
    //                }
    //
    //                return $value;
    //            case self::TYPE_POST :
    //                $values = [];
    //                foreach ($this->get_ids_from_array_or_string($value) as $id) {
    //                    $post = get_post($id);
    //                    $values[] = ac_helper()->html->link(get_edit_post_link($post), $post->post_title);
    //                }
    //
    //                return implode(ac_helper()->html->divider(), $values);
    //            case self::TYPE_USER :
    //                $values = [];
    //                foreach ($this->get_ids_from_array_or_string($value) as $id) {
    //                    $user = get_userdata($id);
    //                    $values[] = ac_helper()->html->link(
    //                        get_edit_user_link($id),
    //                        ac_helper()->user->get_display_name($user)
    //                    );
    //                }
    //
    //                return implode(ac_helper()->html->divider(), $values);
    //            case self::TYPE_IMAGE :
    //
    //                return new Collection($this->get_values_from_array_or_string($value));
    //            case self::TYPE_MEDIA :
    //
    //                return new Collection($this->get_ids_from_array_or_string($value));
    //            case self::TYPE_BOOLEAN :
    //                $is_true = ! empty($value) && 'false' !== $value && '0' !== $value;
    //
    //                if ($is_true) {
    //                    return ac_helper()->icon->dashicon(['icon' => 'yes', 'class' => 'green']);
    //                }
    //
    //                return ac_helper()->icon->dashicon(['icon' => 'no-alt', 'class' => 'red']);
    //            case self::TYPE_COLOR :
    //
    //                if ($value && is_scalar($value)) {
    //                    return ac_helper()->string->get_color_block($value);
    //                }
    //
    //                return false;
    //            case self::TYPE_COUNT :
    //                if ($this->column instanceof AC\Column\Meta) {
    //                    $value = $this->column->get_meta_value($original_value, $this->column->get_meta_key(), false);
    //
    //                    if ($value) {
    //                        if (1 === count($value) && is_array($value[0])) {
    //                            // Value contains a single serialized array with multiple values
    //                            return count($value[0]);
    //                        }
    //
    //                        // Count multiple usage of meta keys
    //                        return count($value);
    //                    }
    //                }
    //
    //                return false;
    //            case self::TYPE_NON_EMPTY :
    //
    //                return ac_helper()->icon->yes_or_no($value, $value);
    //            default :
    //                return ac_helper()->array->implode_recursive(__(', '), $value);
    //        }
    //    }
    //

}