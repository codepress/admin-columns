<?php

declare(strict_types=1);

namespace AC\Storage;

interface Encoder
{

    public function encode(): array;

}