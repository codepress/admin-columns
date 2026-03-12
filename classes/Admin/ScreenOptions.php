<?php

declare(strict_types=1);

namespace AC\Admin;

interface ScreenOptions
{

    /**
     * @return ScreenOption[]
     */
    public function get_screen_options(): array;

}