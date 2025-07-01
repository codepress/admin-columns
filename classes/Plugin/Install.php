<?php

declare(strict_types=1);

namespace AC\Plugin;

interface Install
{

    /**
     * Idempotent call to set up Admin Columns
     */
    public function install(): void;

}