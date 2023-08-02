<?php

declare(strict_types=1);

namespace AC\Table;

use AC\Registerable;

class AdminHeadScripts implements Registerable
{

    private static $rules = [];

    public function register(): void
    {
        add_action('admin_print_scripts', [$this, 'render']);
    }

    public function add(string $css_rule): void
    {
        self::$rules[] = $css_rule;
    }

    public function render(): void
    {
        if ( ! self::$rules) {
            return;
        }

        echo '<style>';

        foreach (self::$rules as $rule) {
            echo $rule;
        }

        echo '</style>';
    }

}