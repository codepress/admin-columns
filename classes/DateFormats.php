<?php

declare(strict_types=1);

namespace AC;

/**
 * Ensure same formats for date storage and retrieval.
 *
 * The default for all storage is the DATE_MYSQL_TIME constant
 */
interface DateFormats
{

    public const DATE_MYSQL_TIME = 'Y-m-d H:i:s';
    public const DATE_MYSQL = 'Y-m-d';

}