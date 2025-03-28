<?php

declare(strict_types=1);

namespace AC\Value;

final class ExtendedValueLinkFactory
{

    private array $attributes;

    private array $params;

    public function __construct(
        array $attributes = [],
        array $params = []
    ) {
        $this->attributes = $attributes;
        $this->params = $params;
    }

    public function create(string $label, int $id): ExtendedValueLink
    {
        return new ExtendedValueLink(
            $label,
            $id,
            'value',
            $this->attributes,
            $this->params
        );
    }

}