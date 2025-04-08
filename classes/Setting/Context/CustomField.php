<?php

declare(strict_types=1);

namespace AC\Setting\Context;

use AC\Setting\Context;

class CustomField extends Context
{

    public function get_field_type(): string
    {
        return $this->get('field_type', '');
    }

    public function get_meta_key(): string
    {
        return $this->get('meta_key', '');
    }

}