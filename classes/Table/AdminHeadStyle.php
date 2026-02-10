<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Helper;
use AC\Registerable;
use InvalidArgumentException;

class AdminHeadStyle implements Registerable
{

    private static array $style_blocks = [];

    public function register(): void
    {
        add_action('admin_print_scripts', [$this, 'render']);
    }

    public static function add(string $style_block): void
    {
        if ( ! Helper\Strings::create()->starts_with($style_block, '<style>') ||
             ! Helper\Strings::create()->ends_with($style_block, '</style>')) {
            throw new InvalidArgumentException('Block needs to be wrapped in "style" tags');
        }

        self::$style_blocks[] = $style_block;
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