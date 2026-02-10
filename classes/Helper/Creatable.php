<?php

declare(strict_types=1);

namespace AC\Helper;

abstract class Creatable
{

    final public function __construct()
    {
    }

    /**
     * @return static
     */
    final public static function create()
    {
        return new static();
    }

}