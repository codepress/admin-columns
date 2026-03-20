<?php

declare(strict_types=1);

namespace AC\Storage;

class OptionFactory implements OptionDataFactory
{

    public function create(string $key): OptionData
    {
        return new Option($key);
    }

}