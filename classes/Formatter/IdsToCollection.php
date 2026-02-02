<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class IdsToCollection implements Formatter
{

    public function format(Value $value): ValueCollection
    {
        $ids = $value->get_value();

        if (is_string($ids)) {
            return ValueCollection::from_ids($value->get_id(), $this->get_ids_from_string($ids));
        }

        if (is_array($ids)) {
            return ValueCollection::from_ids($value->get_id(), $this->sanitise_ids($ids));
        }

        throw new ValueNotFoundException($value->get_id());
    }

    private function sanitise_ids(array $ids): array
    {
        return array_map('absint', array_filter($ids, 'is_numeric'));
    }

    private function get_ids_from_string(string $value): ?array
    {
        if (str_contains($value, ',')) {
            return $this->sanitise_ids(explode(',', $value));
        }

        if (is_serialized($value)) {
            $ids = @unserialize($value);

            if (is_array($ids)) {
                return $this->sanitise_ids($ids);
            }
        }

        if (is_numeric($value)) {
            return [absint($value)];
        }

        return null;
    }

}