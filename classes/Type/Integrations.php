<?php

namespace AC\Type;

use AC\Collection;
use AC\Integration;

class Integrations extends Collection
{

    public function __construct(array $data = [])
    {
        foreach ($data as $integration) {
            $this->add($integration);
        }
    }

    public function add(Integration $integration): void
    {
        $this->data[] = $integration;
    }

    public function current(): Integration
    {
        return current($this->data);
    }

}