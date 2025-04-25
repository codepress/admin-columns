<?php

declare(strict_types=1);

namespace AC\Setting\Context;

use AC\Setting\Config;
use AC\Setting\Context;

class CustomField extends Context
{

    private string $field_type;

    private string $meta_key;

    public function __construct(Config $config, string $field_type, string $meta_key)
    {
        parent::__construct($config);

        $this->field_type = $field_type;
        $this->meta_key = $meta_key;
    }

    public function get_field_type(): string
    {
        return $this->field_type;
    }

    public function get_meta_key(): string
    {
        return $this->meta_key;
    }

}