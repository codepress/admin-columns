<?php

declare(strict_types=1);

namespace AC\Setting\Control\Input;

use AC\Setting\Control\AttributeCollection;
use AC\Setting\Control\Input;

class Open extends Input
{

    protected $append;

    public function __construct(
        string $name,
        string $type = null,
        string $default = null,
        string $placeholder = null,
        AttributeCollection $attributes = null,
        string $append = null
    ) {
        if (null === $type) {
            $type = 'text';
        }

        parent::__construct($name, $type, $default, $placeholder, $attributes);

        $this->append = $append;
    }

    public function has_append(): bool
    {
        return $this->append !== null;
    }

    public function get_append(): ?string
    {
        return $this->append;
    }

}