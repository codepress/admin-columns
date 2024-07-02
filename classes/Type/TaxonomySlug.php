<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

class TaxonomySlug
{

    private $taxonomy;

    public function __construct(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;

        $this->validate();
    }

    private function validate(): void
    {
        if ('' === $this->taxonomy) {
            throw new InvalidArgumentException('Taxonomy slug cannot be empty');
        }
    }

    public function __toString(): string
    {
        return $this->taxonomy;
    }

}