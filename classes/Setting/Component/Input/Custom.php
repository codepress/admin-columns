<?php

declare(strict_types=1);

namespace AC\Setting\Component\Input;

use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\Input;

// TODO David remove
class Custom extends Input
{

    private $data;

    public function __construct(
        string $name,
        array $data = [],
        $default = null,
        string $placeholder = null,
        AttributeCollection $attributes = null
    ) {
        parent::__construct($name, 'custom', $default, $placeholder, $attributes);

        $this->data = $data;
    }

    public function get_data(): array
    {
        return $this->data;
    }

}