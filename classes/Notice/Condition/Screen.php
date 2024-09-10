<?php

declare(strict_types=1);

namespace AC\Notice\Condition;

use AC;
use AC\Notice\Condition;

final class Screen implements Condition
{

    private $screen;

    public function __construct(AC\Screen $screen)
    {
        $this->screen = $screen;
    }

    public function assert(AC\Screen $value): bool
    {
        return $value->is_screen($this->screen->get_id());
    }

}