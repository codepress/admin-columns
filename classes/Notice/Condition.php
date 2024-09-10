<?php

declare(strict_types=1);

namespace AC\Notice;

interface Condition
{

    public function assert(): bool;

}