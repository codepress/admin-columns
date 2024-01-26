<?php

declare(strict_types=1);

namespace AC\Storage;

interface EncoderFactory
{

    public function create(): Encoder;

}