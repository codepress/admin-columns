<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\MetaType;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\ComponentCollection;
use AC\Setting\Type\Value;
use AC\Settings\Setting;

class CustomField extends Setting implements Formatter, AC\Setting\Recursive
{

    public const NAME = 'custom_field';

    private $settings;

    private $field;

    private $meta_type;

    public function __construct(
        string $field,
        MetaType $meta_type,
        ComponentCollection $settings,
        Specification $specification = null
    ) {
        parent::__construct(
            OptionFactory::create_select_remote(
                'field',
                'ac-custom-field-keys',
                $field,
                __('Select', 'codepress-admin-columns')
            ),
            __('Field', 'codepress-admin-columns'),
            __('Custom field key', 'codepress-admin-columns'),
            $specification
        );

        $this->settings = $settings;
        $this->field = $field;
        $this->meta_type = $meta_type;
    }

    public function format(Value $value): Value
    {
        if ( ! $this->field) {
            return $value;
        }

        $field = $this->field;

        // Backwards compatible for WordPress Settings API not storing fields starting with _
        if ($field && 0 === strpos($field, 'cpachidden')) {
            $field = substr($field, strlen('cpachidden'));
        }

        // TODO test serialized data
        $value = $value->with_value(
            get_metadata((string)$this->meta_type, (int)$value->get_id(), $field, true)
        );

        return Aggregate::from_settings($this->settings)->format($value);
    }

    public function get_children(): ComponentCollection
    {
        return $this->settings;
    }

    public function is_parent(): bool
    {
        return false;
    }





    // TODO
    //    const NAME = 'custom_field';
    //
    //    /**
    //     * @var string
    //     */
    //    private $field;
    //
    //    protected function define_options()
    //    {
    //        return ['field'];
    //    }
    //
    //    /**
    //     * @return View
    //     */
    //    public function create_view()
    //    {
    //        $view = new View([
    //            'label'   => __('Field', 'codepress-admin-columns'),
    //            'setting' => $this->get_setting_field(),
    //        ]);
    //
    //        return $view;
    //    }
    //
    //    protected function set_name()
    //    {
    //        $this->name = self::NAME;
    //    }
    //
    //    private function use_text_field()
    //    {
    //        return (bool)apply_filters('ac/column/custom_field/use_text_input', false);
    //    }
    //
    //    /**
    //     * @return AC\Form\Element\Input
    //     */
    //    private function get_settings_field_text()
    //    {
    //        return $this->create_element('text', 'field')
    //                    ->set_attribute('placeholder', 'Custom field key');
    //    }
    //
    //    /**
    //     * @return AC\Form\Element\Select
    //     */
    //    private function get_settings_field_select()
    //    {
    //        $options = $this->get_field()
    //            ? [$this->get_field() => $this->get_field()]
    //            : [];
    //
    //        return $this->create_element('select', 'field')
    //                    ->set_attribute('data-selected', $this->get_field())
    //                    ->set_attribute('data-post_type', $this->get_post_type())
    //                    ->set_attribute('data-type', $this->get_meta_type())
    //                    ->set_options($options)
    //                    ->set_attribute('class', 'custom_field');
    //    }
    //
    //    /**
    //     * @return AC\Form\Element
    //     */
    //    protected function get_setting_field()
    //    {
    //        return $this->use_text_field()
    //            ? $this->get_settings_field_text()
    //            : $this->get_settings_field_select();
    //    }
    //
    //    public function get_dependent_settings()
    //    {
    //        return [new Settings\Column\CustomFieldType($this->column)];
    //    }
    //
    //    protected function get_meta_type()
    //    {
    //        return $this->column->get_meta_type();
    //    }
    //
    //    /**
    //     * @return string Post type
    //     */
    //    protected function get_post_type()
    //    {
    //        return $this->column->get_post_type();
    //    }
    //
    //    /**
    //     * @return string
    //     */
    //    public function get_field()
    //    {
    //        return $this->field;
    //    }
    //
    //    /**
    //     * @param string $field
    //     *
    //     * @return self
    //     */
    //    public function set_field($field)
    //    {
    //        // Backwards compatible for WordPress Settings API not storing fields starting with _
    //        if ($field && 0 === strpos($field, 'cpachidden')) {
    //            $field = substr($field, strlen('cpachidden'));
    //        }
    //
    //        $this->field = $field;
    //
    //        return $this;
    //    }
    //
    //    public function get_config(): ?array
    //    {
    //        return [
    //            'type'    => 'select',
    //            'options' => [
    //                'custom' => [
    //                    'label'   => 'Label',
    //                    'options' => [
    //                        'value'  => 's',
    //                        'dvalue' => 'ssdf',
    //                    ],
    //                ],
    //            ],
    //            'label'   => 'Custom Field',
    //        ];
    //    }

}