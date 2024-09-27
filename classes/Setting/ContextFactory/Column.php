<?php

declare(strict_types=1);

namespace AC\Setting\ContextFactory;

use AC;
use AC\Setting\Context;
use AC\Setting\ContextFactory;

class Column implements ContextFactory
{

    public function create(AC\Column $column): Context
    {
        $data = [];

        foreach ($column->get_settings() as $component) {
            $data = $this->get_data($component, $data);
        }

        return new Context(new AC\Setting\Config($data));
    }

    protected function get_data(AC\Setting\Component $component, array $data): array
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