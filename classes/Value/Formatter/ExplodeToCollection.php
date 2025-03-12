<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

final class ExplodeToCollection implements Formatter
{

    private string $separator;

    public function __construct(string $separator)
    {
        $this->separator = $separator;
    }

    public function format(Value $value): ValueCollection
    {
        $result = explode($this->separator, (string)$value->get_value());

        if (empty($result)) {
            throw new ValueNotFoundException('No values found');
        }

        $collection = new ValueCollection($value->get_id(), []);

        foreach ($result as $item) {
            $collection->add(new Value($item));
        }

        return $collection;
    }

}