<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

/**
 * Takes various raw values that represent one or more image attachments and formats them into a ValueCollection of
 * attachment IDs or URLs. It prioritizes parsing for attachment IDs first. If no valid IDs are found, it will then
 * attempt to parse for image URLs.
 * Supported input value types:
 * - A single attachment ID (integer or string) or a single image URL (string)
 * - A comma-separated string of attachment IDs or image URLs
 * - An array of attachment IDs and/or image URLs
 */
class ImageToCollection implements Formatter
{

    public function format(Value $value): ValueCollection
    {
        $source = $value->get_value();

        if ( ! $source) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        if (is_string($source)) {
            $source = $this->parse_string($source);
        }

        if ( ! is_array($source)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $ids = $this->filter_ids($source);

        if ($ids) {
            return ValueCollection::from_ids($value->get_id(), $ids);
        }

        $urls = $this->filter_urls($source);

        if ($urls) {
            return new ValueCollection($value->get_id(), array_map([$this, 'create_value'], $urls));
        }

        throw ValueNotFoundException::from_id($value->get_id());
    }

    private function filter_ids(array $ids): array
    {
        return array_map('absint', array_filter($ids, 'is_numeric'));
    }

    private function filter_urls(array $urls): array
    {
        return array_filter($urls, [ac_helper()->string, 'is_image']);
    }

    private function parse_string(string $value): array
    {
        if (str_contains($value, ',')) {
            return explode(',', $value);
        }

        return [$value];
    }

    private function create_value(string $value): Value
    {
        return new Value($value);
    }

}