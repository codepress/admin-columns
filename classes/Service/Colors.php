<?php

declare(strict_types=1);

namespace AC\Service;

use AC\Admin\Colors\Shipped\ColorUpdater;
use AC\Admin\Colors\StyleInjector;
use AC\Registerable;

final class Colors implements Registerable
{

    private $shipped_colors_updater;

    private $style_injector;

    public function __construct(ColorUpdater $shipped_colors_updater, StyleInjector $style_injector)
    {
        $this->shipped_colors_updater = $shipped_colors_updater;
        $this->style_injector = $style_injector;
    }

    public function register(): void
    {
        add_action('admin_init', function () {
            $this->shipped_colors_updater->update();
        });
        add_action('admin_head', function () {
            $this->style_injector->inject_style();
        });
    }

}