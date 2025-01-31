<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;

final class HiddenInput extends Builder
{

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    protected function get_input(Config $config): ?Input
    {
        return new Input(
            $this->name,
            'hidden',
            (string)$config->get($this->name),
            null
        );
    }

    protected function get_type(Config $config): ?string
    {
        return 'input_only';
    }

}