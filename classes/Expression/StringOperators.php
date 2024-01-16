<?php

declare(strict_types=1);

namespace AC\Expression;

interface StringOperators
{

    public const CONTAINS = 'contains';
    public const NOT_CONTAINS = 'not_contains';
    public const STARTS_WITH = 'starts_with';
    public const ENDS_WITH = 'ends_with';

}