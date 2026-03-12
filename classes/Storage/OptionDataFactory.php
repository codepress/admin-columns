<?php

declare(strict_types=1);

namespace AC\Storage;

interface OptionDataFactory
{

    public function create(string $key): OptionData;

}