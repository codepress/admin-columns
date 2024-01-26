<?php

declare(strict_types=1);

namespace AC\Setting\Component\Input;

use AC\Setting\Component\Input;

// TODO David remove
class Custom extends Input
{

    private $data;

    public function __construct(
        string $type,
        array $data = [],
        $default = null,
        string $placeholder = null,
        string $class = null
    ) {
        parent::__construct($type, $type, $default, $placeholder, $class);

        $this->data = $data;
    }

    public function has_data(): bool
    {
        return ! empty($this->data);
    }

    public function get_data(): array
    {
        return $this->data;
    }

}