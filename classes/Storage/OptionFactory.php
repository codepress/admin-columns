<?php

namespace AC\Storage;

class OptionFactory implements OptionDataFactory
{

    public function create(string $key): OptionData
    {
        // TODO
        return new Option($key);
    }

}