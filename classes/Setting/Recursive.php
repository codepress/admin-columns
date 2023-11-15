<?php

declare(strict_types=1);

namespace AC\Setting;

interface Recursive extends Setting
{

    public function has_children(): bool;

    public function get_children(): SettingCollection;

}