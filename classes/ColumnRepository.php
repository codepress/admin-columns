<?php

declare(strict_types=1);

namespace AC;

interface ColumnRepository
{

    public function find_all(): ColumnCollection;

}