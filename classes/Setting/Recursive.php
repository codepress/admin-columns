<?php

declare(strict_types=1);

namespace AC\Setting;

// TODO David maybe add an indent level or is indent option to render it closer to the parent setting?
interface Recursive extends Setting
{

    public function has_children(): bool;

    public function get_children(): SettingCollection;

}