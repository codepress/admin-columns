<?php

declare(strict_types=1);

namespace AC\Type\Url;

use AC\Type\Url;
use InvalidArgumentException;

class External implements Url
{

    private $url;

    public function __construct(string $url)
    {
        $this->url = $url;

        $this->validate();
    }

    private function validate(): void
    {
        if ( ! ac_helper()->string->starts_with($this->url, 'https')) {
            throw new InvalidArgumentException('Not https');
        }
    }

    public function get_url(): string
    {
        return $this->url;
    }

}