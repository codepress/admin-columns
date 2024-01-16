<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\Expression\Specification;

class Message extends Settings\Column
{

    public function __construct(AC\Column $column, string $label, string $message, Specification $conditions = null)
    {
        $this->name = 'message';
        $this->label = $label;
        $this->input = new AC\Setting\Input\Custom('message', [
            'message' => $message,
        ]);

        parent::__construct(
            $column,
            $conditions
        );
    }

}