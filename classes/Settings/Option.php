<?php

namespace AC\Settings;

use AC\Storage\KeyValuePair;

class Option
{

    private $name;

    private $storage;

    public function __construct(string $name, KeyValuePair $storage = null)
    {
        $this->name = $name;
        $this->storage = $storage ?: new GeneralOption();
    }

    public function is_empty(): bool
    {
        return in_array($this->get(), [null, false], true);
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get()
    {
        $values = $this->storage->get();

        if ( ! $values || ! array_key_exists($this->name, $values)) {
            return null;
        }

        return $values[$this->name];
    }

    public function save($value)
    {
        $values = $this->storage->get();

        if (false === $values) {
            $values = [];
        }

        $values[$this->name] = $value;

        $this->storage->save($values);
    }

    public function delete()
    {
        $values = $this->storage->get();

        if (empty($values)) {
            return;
        }

        unset($values[$this->name]);

        $this->storage->save($values);
    }

}