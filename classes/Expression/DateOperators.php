<?php

declare(strict_types=1);

namespace AC\Expression;

interface DateOperators
{

    public const DATE_IS = 'date_is';
    public const DATE_IS_BEFORE = 'date_is_before';
    public const DATE_IS_AFTER = 'date_is_after';
    public const DATE_BETWEEN = 'date_between';
    public const FUTURE = 'future';
    public const PAST = 'past';
    public const TODAY = 'today';
    public const WITHIN_DAYS = 'within_days';
    public const GT_DAYS_AGO = 'gt_days_ago';
    public const LT_DAYS_AGO = 'lt_days_ago';

}