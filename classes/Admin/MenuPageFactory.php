<?php

declare(strict_types=1);

namespace AC\Admin;

interface MenuPageFactory
{

    public function create(array $args = []): string;

}