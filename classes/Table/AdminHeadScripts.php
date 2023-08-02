<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Registerable;

class AdminHeadScripts implements Registerable
{

    private static $style_blocks = [];

    public function register(): void
    {
        add_action('admin_print_scripts', [$this, 'render']);
    }

    public static function add(string $style_blocks): void
    {
        self::$style_blocks[] = $style_blocks;
    }

    public function render(): void
    {
        if ( ! self::$style_blocks) {
            return;
        }

        foreach (self::$style_blocks as $block) {
            echo $block;
        }
    }

}