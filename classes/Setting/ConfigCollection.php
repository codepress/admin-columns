<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;

class ConfigCollection extends Collection implements Countable
{

    public function __construct(array $configs = [])
    {
        array_map([$this, 'add'], $configs);
    }

    public function add(Config $config): void
    {
        $this->data[] = $config;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public static function create_from_array(array $data): ConfigCollection
    {
        $collection = [];

        foreach ($data as $config) {
            if ( ! $config instanceof Config) {
                $config = new Config($config);
            }

            $collection[] = $config;
        }

        return new self($collection);
    }

    public function current(): Config
    {
        return current($this->data);
    }

}