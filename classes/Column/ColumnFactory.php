<?php

namespace AC\Column;

use AC\Column;
use AC\Setting\Config;

interface ColumnFactory
{

    public function create(Config $config): Column;

    public function get_column_type(): string;

    public function get_label(): string;

}