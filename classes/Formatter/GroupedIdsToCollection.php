<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class GroupedIdsToCollection implements Formatter
{

    /**
     * @throws ValueNotFoundException
     */
    public function format(Value $value): ValueCollection
    {
        $collection = (new IdsToCollection())->format($value);

        return $this->group_by_extension($collection);
    }

    private function group_by_extension(ValueCollection $collection): ValueCollection
    {
        $groups = [];

        foreach ($collection as $item) {
            $file = get_attached_file((int)(string)$item);
            $ext  = $file ? strtolower((string)pathinfo($file, PATHINFO_EXTENSION)) : '';

            $groups[$ext][] = $item;
        }

        $new_collection = new ValueCollection($collection->get_id());

        foreach (array_merge(...array_values($groups)) as $item) {
            $new_collection->add($item);
        }

        return $new_collection;
    }

}
