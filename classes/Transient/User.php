<?php

namespace AC\Transient;

use AC\Storage;
use AC\Transient;

class User extends Transient
{

    public function __construct(string $key)
    {
        parent::__construct($key);

        $this->option = new Storage\UserMeta($key);
        $this->timestamp = new Storage\Timestamp(
            new Storage\UserMeta($key . '_timestamp')
        );
    }
}