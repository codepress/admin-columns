<?php

declare(strict_types=1);

namespace AC\Value\Extended;

use AC\Value\ExtendedValueLink;

interface ExtendedValue
{

    public function render(int $id, array $params): string;

    public function can_render(string $view): bool;

    public function get_link(int $id, string $label): ExtendedValueLink;
}