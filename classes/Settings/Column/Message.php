<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Settings;

class Message extends Settings\Column
{

    public function __construct(string $label, string $message, Specification $conditions = null)
    {
        parent::__construct(
            'message',
            $label,
            '',
            new AC\Setting\Input\Custom('message', [
                'message' => $message,
            ]),
            $conditions
        );
    }

}