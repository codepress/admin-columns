<?php

declare(strict_types=1);

namespace AC\Setting;

trait RecursiveTrait
{

    public function has_children(): bool
    {
        return true;
    }

    abstract public function get_children(): SettingCollection;

}