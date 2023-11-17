<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;

final class Custom implements Input
{

    private $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function get_type(): string
    {
        return $this->type;
    }

}