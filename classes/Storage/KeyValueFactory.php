<?php

namespace AC\Storage;

interface KeyValueFactory
{

    public function create(string $key): KeyValuePair;

}