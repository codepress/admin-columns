<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\Uri;
use InvalidArgumentException;

class External extends Uri
{

    public function __construct(string $url)
    {
        parent::__construct($url);

        $this->validate();
    }

    private function validate(): void
    {
        if ( ! ac_helper()->string->starts_with($this->url, 'https')) {
            throw new InvalidArgumentException('Not https');
        }
    }

}