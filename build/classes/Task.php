<?php

declare(strict_types=1);

namespace AC\Build;

interface Task
{

    public function run(): void;

}