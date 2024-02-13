<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

final class AggregateBuilderFactory
{

    public function create(): AggregateBuilder
    {
        return new AggregateBuilder();
    }

}