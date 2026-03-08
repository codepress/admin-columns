<?php

declare(strict_types=1);

namespace AC\Notice\DismissHandler;

use AC\Notice\DismissHandler;
use AC\Storage\Timestamp;

final class TimestampDismiss implements DismissHandler
{

    private Timestamp $storage;

    private int $duration;

    public function __construct(Timestamp $storage, int $duration)
    {
        $this->storage = $storage;
        $this->duration = $duration;
    }

    public function dismiss(): void
    {
        $this->storage->save(time() + $this->duration);
    }

}
