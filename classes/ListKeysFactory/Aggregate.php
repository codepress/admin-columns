<?php

declare(strict_types=1);

namespace AC\ListKeysFactory;

use AC\ListKeyCollection;
use AC\ListKeysFactory;

class Aggregate implements ListKeysFactory
{

    /**
     * @var ListKeysFactory[]
     */
    private static $factories = [];

    public static function add(ListKeysFactory $factory): void
    {
        array_unshift(self::$factories, $factory);
    }

    public function create(): ListKeyCollection
    {
        $keys = new ListKeyCollection();

        foreach (self::$factories as $factory) {
            foreach ($factory->create() as $key) {
                $keys->add($key);
            }
        }

        return $keys;
    }

}