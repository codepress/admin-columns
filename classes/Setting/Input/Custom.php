<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;

final class Custom implements Input
{

    private $type;

    private $data;

    public function __construct(string $type, array $data = [])
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_data(): array
    {
        return $this->data;
    }

}