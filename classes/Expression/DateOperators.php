<?php

declare(strict_types=1);

namespace AC\Expression;

interface DateOperators
{

    public const FUTURE = 'future';
    public const PAST = 'past';
    public const TODAY = 'today';
    public const WITHIN_DAYS = 'within_days';
    public const GT_DAYS_AGO = 'gt_days_ago';
    public const LT_DAYS_AGO = 'lt_days_ago';

}