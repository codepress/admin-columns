<?php

declare(strict_types=1);

namespace AC\Check\Integration;

interface UsageAwareNotice
{

    public function is_usage_detected(): bool;

}
