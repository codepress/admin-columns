<?php

declare(strict_types=1);

namespace AC\TableScreenFactory;

use AC\TableScreen;
use AC\TableScreenFactory;
use AC\Type\TableId;
use InvalidArgumentException;
use WP_Screen;

class Aggregate implements TableScreenFactory
{

    /**
     * @var TableScreenFactory[]
     */
    private static array $factories = [];

    public static function add(TableScreenFactory $factory): void
    {
        array_unshift(self::$factories, $factory);
    }

    public function create(TableId $id): TableScreen
    {
        foreach (self::$factories as $factory) {
            if ($factory->can_create($id)) {
                return $factory->create($id);
            }
        }

        throw new InvalidArgumentException();
    }

    public function can_create(TableId $id): bool
    {
        foreach (self::$factories as $factory) {
            if ($factory->can_create($id)) {
                return (bool)apply_filters('ac/list_screen/key/is_active', true, (string)$id);
            }
        }

        return false;
    }

    public function create_from_wp_screen(WP_Screen $screen): TableScreen
    {
        foreach (self::$factories as $factory) {
            if ($factory->can_create_from_wp_screen($screen)) {
                return $factory->create_from_wp_screen($screen);
            }
        }

        throw new InvalidArgumentException();
    }

    public function can_create_from_wp_screen(WP_Screen $screen): bool
    {
        foreach (self::$factories as $factory) {
            if ($factory->can_create_from_wp_screen($screen)) {
                return (bool)apply_filters('ac/list_screen/is_active', true, $screen);
            }
        }

        return false;
    }

}