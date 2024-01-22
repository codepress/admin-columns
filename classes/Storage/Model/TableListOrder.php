<?php

declare(strict_types=1);

namespace AC\Storage\Model;

use AC\Preferences\Preference;
use AC\Storage\UserOption;

class TableListOrder extends Preference
{

    public function __construct(int $user_id = null)
    {
        parent::__construct(new UserOption('list_order', $user_id));
    }
}