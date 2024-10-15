<?php

declare(strict_types=1);

namespace AC\Type;

class Uri implements QueryAware
{

    use QueryAwareTrait;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function with_arg(string $key, string $value): self
    {
        return new self(add_query_arg($key, $value, $this->url));
    }

}