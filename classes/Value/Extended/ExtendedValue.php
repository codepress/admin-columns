<?php

declare(strict_types=1);

namespace AC\Value\Extended;

use AC\Column;
use AC\ListScreen;
use AC\Value\ExtendedValueLink;

interface ExtendedValue
{

    public function render($id, array $params, Column $column, ListScreen $list_screen): string;

    public function can_render(string $view): bool;

    public function get_link($id, string $label): ExtendedValueLink;

}