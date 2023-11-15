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
        $encoded = [
            'name'        => $setting->get_name(),
            'label'       => $setting->get_label(),
            'description' => $setting->get_description(),
        ];

        if ($setting instanceof Multiple) {
            $encoded['options'] = [
                'has_other_option' => $setting->has_other_option(),
                'options'          => $this->encode_options($setting->get_options()),
            ];
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