<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Component\Input\Number;
use AC\Setting\Component\Input\Open;
use AC\Setting\Component\Input\Option;
use AC\Setting\Component\OptionCollection;

final class Encoder
{

    private $settings;

    public function __construct(SettingCollection $settings)
    {
        $this->settings = $settings;
    }

    public function encode(): array
    {
        $encoded = [];

        foreach ($this->settings as $setting) {
            $encoded[] = $this->encode_setting($setting);
        }

        return $encoded;
    }

    private function encode_setting(Component $component): array
    {
        $encoded = [
            'type' => $component->get_type(),
        ];

        foreach ($component->get_attributes() as $attribute) {
            $encoded['attributes'][$attribute->get_name()] = $attribute->get_value();
        }

        if ($component instanceof Setting) {
            $encoded['conditions'] = $component->get_conditions()->get_rules($component->get_name());

            $input = $component->get_input();

            $encoded['input'] = [
                'type' => $input->get_type(),
                'name' => $input->get_name(),
            ];

            if ($input->has_default()) {
                $encoded['input']['default'] = $input->get_default();
            }

            if ($input instanceof Open) {
                if ($input->has_append()) {
                    $encoded['input']['append'] = $input->get_append();
                }
            }

            if ($input instanceof Option) {
                $encoded['input'] += [
                    'options'  => $this->encode_options($input->get_options()),
                    'multiple' => $input->is_multiple(),
                ];
            }

            if ($input instanceof Number) {
                if ($input->has_min()) {
                    $encoded['input']['min'] = $input->get_min();
                }

                if ($input->has_max()) {
                    $encoded['input']['max'] = $input->get_max();
                }

                if ($input->has_step()) {
                    $encoded['input']['step'] = $input->get_step();
                }
            }
        }

        if ($component instanceof Recursive) {
            $encoded['is_parent'] = $component->is_parent();
            $encoded['children'] = [];

            foreach ($component->get_children() as $child) {
                $encoded['children'][] = $this->encode_setting($child);
            }
        }

        return $encoded;
    }

    private function encode_options(OptionCollection $options): array
    {
        $encoded = [];

        foreach ($options as $option) {
            $encoded[] = [
                'value' => $option->get_value(),
                'label' => $option->get_label(),
                'group' => $option->get_group(),
            ];
        }

        return $encoded;
    }

}