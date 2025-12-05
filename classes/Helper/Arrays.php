<?php

namespace AC\Helper;

class Arrays
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

    // TODO turn into Formatter
    public function implode_recursive(string $glue, $pieces): string
    {
        if (is_scalar($pieces)) {
            return (string)$pieces;
        }

        if ( ! is_array($pieces)) {
            return '';
        }

        $scalars = [];

        foreach ($pieces as $r_pieces) {
            if (is_array($r_pieces)) {
                $scalars[] = $this->implode_recursive($glue, $r_pieces);
            }
            if (is_scalar($r_pieces)) {
                $scalars[] = (string)$r_pieces;
            }
        }

        return implode($glue, array_filter($scalars, 'strlen'));
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
        return array_filter($array, [ac_helper()->string, 'is_not_empty']);
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
        _deprecated_function(__METHOD__, 'NEWVERSION');

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

    public function get_duplicates(array $array): array
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return [];
    }

    /**
     * Returns all integers from an array or comma separated string
     */
    public function get_integers_from_mixed($mixed): array
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return [];
    }

    public function implode_associative(array $array, $glue): string
    {
        _deprecated_function(__METHOD__, '5.7.1');

        return '';
    }

    public function key_replace($input, $old_key, $new_key): array
    {
        _deprecated_function(__METHOD__, 'NEWVERSION');

        return [];
    }

}