<?php

declare(strict_types=1);

namespace AC\Expression;

interface ComparisonOperators
{

    public const GREATER_THAN = 'greater_than';
    public const GREATER_THAN_EQUAL = 'greater_than_equal';
    public const LESS_THAN = 'less_than';
    public const LESS_THAN_EQUAL = 'less_than_equal';
    public const EQUAL = 'equal';
    public const NOT_EQUAL = 'not_equal';

}