<?php

declare(strict_types=1);

namespace AC\Setting;

interface Recursive
{

    public function get_children(): ComponentCollection;

    public function is_parent(): bool;

}