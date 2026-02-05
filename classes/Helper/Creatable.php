<?php

declare(strict_types=1);

namespace AC\Helper;

abstract class Creatable
{

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

}