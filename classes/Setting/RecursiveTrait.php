<?php

declare(strict_types=1);

namespace AC\Setting;

trait RecursiveTrait
{

    abstract public function get_children(): SettingCollection;

    public function is_parent(): bool
    {
        return false;
    }

}