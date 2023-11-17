<?php

declare(strict_types=1);

namespace AC\ListScreenFactory;

// TODO remove
final class Aggregate extends BaseFactory
{

    // @var ListScreenFactory[]
    //    private static $factories = [];
    //
    //    public static function add(ListScreenFactory $factory): void
    //    {
    //        array_unshift(self::$factories, $factory);
    //    }
    //
    //    public function create(ListKey $key, array $settings = []): ListScreen
    //    {
    //        foreach (self::$factories as $factory) {
    //            if ($factory->can_create($key)) {
    //                return $factory->create($key, $settings);
    //            }
    //        }
    //
    //        throw InvalidListScreenException::from_invalid_key($key);
    //    }
    //
    //    public function can_create(ListKey $key): bool
    //    {
    //        foreach (self::$factories as $factory) {
    //            if ($factory->can_create($key)) {
    //                return (bool)apply_filters('ac/list_screen/key/is_active', true, (string)$key);
    //            }
    //        }
    //
    //        return false;
    //    }

}