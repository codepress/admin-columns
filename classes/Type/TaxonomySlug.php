<?php

declare(strict_types=1);

namespace AC\Type;

class TaxonomySlug
{

    private $taxonomy;

    public function __construct(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function __toString(): string
    {
        return $this->taxonomy;
    }

}