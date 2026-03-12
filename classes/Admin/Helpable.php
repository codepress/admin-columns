<?php

declare(strict_types=1);

namespace AC\Admin;

interface Helpable
{

    /**
     * @return HelpTab[]
     */
    public function get_help_tabs(): array;

}