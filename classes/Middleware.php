<?php

declare(strict_types=1);

namespace AC;

interface Middleware
{

    public function handle(Request $request): void;

}