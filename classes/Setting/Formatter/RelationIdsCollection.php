<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

/**
 * Ensure that the value is a collection of (Post) Ids
 */
class RelationIdsCollection implements Formatter
{

    public function format(Value $value)
    {
        $raw_value = $value->get_value();

        if (empty($raw_value)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $post_ids = is_array($raw_value)
            ? $raw_value
            : [$raw_value];

        return ValueCollection::from_ids($value->get_id(), $post_ids);
    }

}