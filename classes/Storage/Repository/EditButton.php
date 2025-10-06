<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Settings\GeneralOption;

class EditButton
{

    private GeneralOption $storage;

    public function __construct(GeneralOption $storage)
    {
        $this->storage = $storage;
    }

    public function is_active(): bool
    {
        $value = $this->storage->get('show_edit_button');

        return in_array($value, ['1', null], true);
    }

}