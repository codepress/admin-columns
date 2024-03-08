<?php

declare(strict_types=1);

namespace AC\Setting\Control\Input;

use AC\Setting\AttributeCollection;
use AC\Setting\Control\Input;

final class Custom extends Input
{

    private $data;

    public function __construct(
        string $name,
        string $type = null,
        array $data = [],
        $value = null,
        string $placeholder = null,
        AttributeCollection $attributes = null
    ) {
        if ($type === null) {
            $type = $name;
        }

        parent::__construct($name, $type, $value, $placeholder, $attributes);

        $this->data = $data;
    }

    public function get_data(): array
    {
        return $this->data;
    }

}