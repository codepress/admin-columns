<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class IdCollectionFromArrayOrString implements Formatter
{

    public function format(Value $value): ValueCollection
    {
        $raw = $value->get_value();

        if ( ! $raw) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if (is_string($raw)) {
            $raw = [$raw];
        }

        $ids = array_filter($raw, 'is_numeric');

        return ValueCollection::from_ids($value->get_id(), $ids);
    }

}