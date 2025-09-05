<?php

namespace AC\Type;

use DateTime;

class DateRange
{

    private DateTime $start;

    private DateTime $end;

    public function __construct(DateTime $start, DateTime $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function get_start(): DateTime
    {
        return $this->start;
    }

    public function get_end(): DateTime
    {
        return $this->end;
    }

    public function in_range(?DateTime $date = null): bool
    {
        if (null === $date) {
            $date = new DateTime();
        }

        return $date >= $this->start && $date <= $this->end;
    }

}