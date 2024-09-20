<?php

declare(strict_types=1);

namespace AC;

final class RequestFactory
{

    public function create(): Request
    {
        return new Request();
    }

}