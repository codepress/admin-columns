<?php

declare(strict_types=1);

namespace AC;

interface Expirable
{

    public function is_expired(?int $timestamp = null): bool;

}