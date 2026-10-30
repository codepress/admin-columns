<?php

declare(strict_types=1);

namespace AC\Nonce;

use AC\Form\Nonce;

class TableRows extends Nonce
{
    public function __construct()
    {
        parent::__construct('ac_get_table_rows', 'ac_table_rows_nonce');
    }

}
