<?php

declare(strict_types=1);

namespace AC\Setting;

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

    private function encode_setting(Setting $setting): array
    {
        $input = $setting->get_input();

        $encoded = [
            'name'        => $setting->get_name(),
            'label'       => $setting->get_label(),
            'description' => $setting->get_description(),
            'input'       => [
                'type' => $input->get_type(),
            ],
        ];

        if ($input instanceof Input\Multiple) {
            $encoded['input']['options'] = $this->encode_options($input->get_options());

            if ($input->is_multiple()) {
                $encoded['input']['multiple'] = true;
            }
        }

        if ($setting instanceof Recursive) {
            $encoded['children'] = [];

            foreach ($setting->get_children() as $child) {
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