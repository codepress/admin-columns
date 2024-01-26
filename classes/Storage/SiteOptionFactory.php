<?php

namespace AC\Storage;

class SiteOptionFactory implements OptionDataFactory
{

    public function create(string $key): OptionData
    {
        return new SiteOption($key);
    }

}