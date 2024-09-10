<?php

declare(strict_types=1);

namespace AC\Notice\Condition;

use AC\Notice\Condition;
use DateTimeImmutable;

final class DateAfter implements Condition
{

    private $date;

    public function __construct(DateTimeImmutable $date)
    {
        $this->date = $date;
    }

    public function assert(DateTimeImmutable $value): bool
    {
        return $value > $this->date;
    }

}