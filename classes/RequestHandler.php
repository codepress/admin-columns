<?php

declare(strict_types=1);

namespace AC;

interface RequestHandler
{

    public function handle(Request $request): void;

}