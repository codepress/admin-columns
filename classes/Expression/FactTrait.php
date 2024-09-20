<?php

declare(strict_types=1);

namespace AC\Expression;

trait FactTrait
{

    /**
     * @var mixed
     */
    protected $fact;

    protected function export_fact(): array
    {
        return [
            FactSpecification::FACT => $this->fact,
        ];
    }

}