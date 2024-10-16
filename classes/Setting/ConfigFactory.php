<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Column;

class ConfigFactory
{

    public function create(Column $column): Config
    {
        $data = [];

        foreach ($column->get_settings() as $component) {
            $data = $this->get_data($component, $data);
        }

        return new Config($data);
    }

    protected function get_data(Component $component, array $data): array
    {
        if ($component->has_input()) {
            $input = $component->get_input();

            $data[$input->get_name()] = $input->get_value();
        }

        if ($component->has_children()) {
            foreach ($component->get_children() as $child) {
                $data = $this->get_data($child, $data);
            }
        }

        return $data;
    }

}