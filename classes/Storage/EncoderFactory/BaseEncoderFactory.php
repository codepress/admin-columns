<?php

declare(strict_types=1);

namespace AC\Storage\EncoderFactory;

use AC;
use AC\Plugin\Version;
use AC\Storage\Encoder\BaseEncoder;

class BaseEncoderFactory implements AC\Storage\EncoderFactory
{

    protected $version;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    public function create(): AC\Storage\Encoder
    {
        return new BaseEncoder($this->version);
    }

}