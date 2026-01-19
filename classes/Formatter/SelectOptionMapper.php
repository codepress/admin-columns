<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Setting\Config;

class SelectOptionMapper extends MapOptionLabel
{

    public function __construct(Config $config)
    {
        $data = json_decode($config->get('select_options', '')) ?? [];
        $options = [];

        foreach ($data as $option) {
            $options[$option->value] = $option->label;
        }

        parent::__construct($options);
    }

}