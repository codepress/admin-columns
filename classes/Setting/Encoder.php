<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Control\Input\Custom;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\Open;
use AC\Setting\Control\Input\Option;
use AC\Setting\Control\OptionCollection;

final class Encoder
{

    private ComponentCollection $settings;

    public function __construct(ComponentCollection $settings)
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
            'type'  => $component->get_type(),
            'label' => $component->has_label() ? $component->get_label() : '',
        ];

        if ($component->has_description()) {
            $encoded['description'] = $component->get_description();
        }

        foreach ($component->get_attributes() as $attribute) {
            $encoded['attributes'][$attribute->get_name()] = $attribute->get_value();
        }

        if ($component->has_input()) {
            $input = $component->get_input();

            $encoded['conditions'] = $component->get_conditions()->export();

            $encoded['input'] = [
                'type' => $input->get_type(),
                'name' => $input->get_name(),
            ];

            if ($input->has_placeholder()) {
                $encoded['input']['placeholder'] = $input->get_placeholder();
            }

            foreach ($input->get_attributes() as $attribute) {
                $encoded['input']['attributes'][$attribute->get_name()] = $attribute->get_value();
            }

            if ($input->has_value()) {
                $encoded['input']['default'] = $input->get_value();
            }

            if ($input instanceof Open && $input->has_append()) {
                $encoded['input']['append'] = $input->get_append();
            }

            if ($input instanceof Option) {
                $encoded['input'] += [
                    'options' => $this->encode_options($input->get_options()),
                ];

                if ($input->is_multiple()) {
                    $encoded['input']['type'] = 'select_multiple';
                }
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

            if ($input instanceof Custom) {
                $encoded['input']['data'] = $input->get_data();
            }
        }

        if ($component->has_children()) {
            $encoded['is_parent'] = $component->get_children()->is_parent();
            $encoded['children'] = [];

            foreach ($component->get_children()->get_iterator() as $item) {
                $encoded['children'][] = $this->encode_setting($item);
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