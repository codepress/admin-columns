<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Type\Value;
use AC\Type\ValueCollection;

final class ExplodeToCollection extends ArrayToCollection
{

    private string $separator;

    public function __construct(string $separator)
    {
        $this->separator = $separator;
    }

    public function format(Value $value): ValueCollection
    {
        $result = explode($this->separator, (string)$value);

        if (empty($result)) {
            throw new ValueNotFoundException('No values found');
        }

        return parent::format($value->with_value($result));
    }

}