<?php

declare(strict_types=1);

namespace AC\Expression;

trait TypeTrait
{

    protected string $type;

    protected function export_type(): array
    {
        return [
            TypeSpecification::TYPE => $this->type,
        ];
    }

}