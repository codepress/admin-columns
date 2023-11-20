<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;

abstract class Single implements Input
{

    private $default;

    public function __construct(string $default = null)
    {
        $this->default = $default;
    }

    public function has_default(): bool
    {
        return $this->default !== null;
    }

    public function get_default(): ?string
    {
        return (string)$this->default;
    }

}