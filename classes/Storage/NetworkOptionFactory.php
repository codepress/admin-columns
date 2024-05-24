<?php

namespace AC\Storage;

final class NetworkOptionFactory implements KeyValueFactory
{

    public function create(string $key): KeyValuePair
    {
        return new SiteOption($key);
    }

}