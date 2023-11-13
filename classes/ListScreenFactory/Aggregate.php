<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

use AC\Exception\InvalidListScreenException;
use AC\ListScreen;
use AC\ListScreenFactory;

final class Aggregate implements ListScreenFactory
{

    /**
     * @var ListScreenFactory[]
     */
    private static $factories = [];

    public static function add(ListScreenFactory $factory): void
    {
        array_unshift(self::$factories, $factory);
    }

    public function create(string $key, array $settings = []): ListScreen
    {
        foreach (self::$factories as $factory) {
            if ($factory->can_create($key)) {
                return $factory->create($key, $settings);
            }
        }

        throw InvalidListScreenException::from_invalid_key($key);
    }

    public function can_create(string $key): bool
    {
        foreach (self::$factories as $factory) {
            if ($factory->can_create($key)) {
                return (bool)apply_filters('ac/list_screen/key/is_active', true, $key);
            }
        }

        return false;
    }

}