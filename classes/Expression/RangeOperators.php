<?php

declare(strict_types=1);

namespace AC\Expression;

interface RangeOperators
{

    public const BETWEEN = 'between';

    public const NOT_BETWEEN = 'not_between';

    public const BETWEEN_EXCLUSIVE = 'between_exclusive';
    public const NOT_BETWEEN_EXCLUSIVE = 'not_between_exclusive';

}