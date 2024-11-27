<?php

namespace AC\Type;

use AC\ArrayIterator;
use AC\Integration;

class Integrations extends ArrayIterator
{

    /**
     * @return Integration[]
     */
    public function all()
    {
        return parent::get_copy();
    }

    public function add(Integration $integration)
    {
        $this->array[] = $integration;

        return $this;
    }

    public function exists()
    {
        return ! empty($this->array);
    }

}