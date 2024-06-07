<?php

namespace AC\Storage;

final class OptionFactory implements KeyValueFactory
{

    public function create(string $key): KeyValuePair
    {
        return new Option($key);
    }

}