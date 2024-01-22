<?php

namespace AC\Storage;

interface OptionDataFactory
{

    public function create(string $key): OptionData;

}