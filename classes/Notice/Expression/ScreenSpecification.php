<?php

declare(strict_types=1);

namespace AC\Notice\Expression;

use AC\Expression\Specification;
use AC\Screen;

final class ScreenSpecification extends Specification
{
    private array $screens;

    public function __construct(string $operator, array $screens = [])
    {
        parent::__construct($operator);

        $this->screens = $screens;
    }

    public function export(): array
    {
        return array_merge([

        ], parent::export());
    }

}