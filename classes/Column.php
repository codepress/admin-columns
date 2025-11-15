<?php

declare(strict_types=1);

namespace AC;

use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Context;
use AC\Setting\FormatterCollection;
use AC\Type\ColumnId;

interface Column
{

    public function get_id(): ColumnId;

    public function get_type(): string;

    public function get_label(): string;

    public function get_group(): string;

    public function get_setting(string $name): ?Component;

    public function get_settings(): ComponentCollection;

    public function get_formatters(): FormatterCollection;

    public function get_context(): Context;

}