<?php

namespace AC\Helper;

use AC\Formatter\ImplodeRecursive;
use AC\Type\Value;

class Arrays extends Creatable
{

    public function add_nested_value(array $keys, $value, array $result = []): array
    {
        $key = array_shift($keys);

        if ($keys) {
            $value = $this->add_nested_value(
                $keys,
                $value,
                is_array($result[$key]) ? $result[$key] : []
            );
        }

        $result[$key] = $value;

        return $result;
    }

    public function get_nested_value(array $array, array $keys)
    {
        foreach ($keys as $key) {
            if ( ! isset($array[$key])) {
                return null;
            }

            $array = $array[$key];
        }

        return $array;
    }

    /**
     * @deprecated 7.0
     */
    public function implode_recursive(string $glue, array $pieces): string
    {
        _deprecated_function(__METHOD__, '7.0', ImplodeRecursive::class);

        return (new ImplodeRecursive($glue))->format(new Value($pieces));
    }

    /**
     * Indents any object as long as it has a unique id and that of its parent.
     */
    public function indent(
        array $array,
        int $parentId = 0,
        string $parentKey = 'post_parent',
        string $selfKey = 'ID',
        string $childrenKey = 'children'
    ): array {
        $indent = [];

        $i = 0;
        foreach ($array as $v) {
            if ($v->$parentKey == $parentId) {
                $indent[$i] = $v;
                $indent[$i]->$childrenKey = $this->indent($array, $v->$selfKey, $parentKey, $selfKey);

                $i++;
            }
        }

        return $indent;
    }

    /**
     * Remove empty values from array
     */
    public function filter(array $array): array
    {
        return array_filter($array, [Strings::create(), 'is_not_empty']);
    }

    /**
     * Insert element into array at specific position
     */
    public function insert(array $array, array $insert, $position): array
    {
        $new = [];
        foreach ($array as $key => $value) {
            $new[$key] = $value;
            if ($key === $position) {
                $new = array_merge($new, $insert);
            }
        }

        return $new;
    }

    public function is_associative($array): bool
    {
        _deprecated_function(__METHOD__, '7.0');

        if ( ! is_array($array)) {
            return false;
        }

        foreach ($array as $key => $value) {
            if (is_string($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @deprecated 7.0
     */
    public function get_duplicates(): array
    {
        _deprecated_function(__METHOD__, '7.0');

        return [];
    }

    /**
     * @deprecated 7.0
     */
    public function get_integers_from_mixed(): array
    {
        _deprecated_function(__METHOD__, '7.0');

        return [];
    }

    /**
     * @deprecated 7.0
     */
    public function key_replace(): array
    {
        _deprecated_function(__METHOD__, '7.0');

        return [];
    }

}