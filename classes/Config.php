<?php

namespace AC;

abstract class Config extends ArrayIterator
{

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->validate_config();
    }

    /**
     * Assert this config is valid.
     */
    abstract protected function validate_config(): void;

}
