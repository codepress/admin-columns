<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;

final class Text extends Single
{

    public function get_type(): string
    {
        return 'text';
    }

}