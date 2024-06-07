<?php

namespace AC\Settings\General;

use AC\Storage\GeneralOption;

class EditButton
{

    private $storage;

    public function __construct(GeneralOption $storage)
    {
        $this->storage = $storage;
    }

    public function get_name(): string
    {
        return 'show_edit_button';
    }

    public function is_enabled(): bool
    {
        return in_array(
            $this->storage->find('show_edit_button'),
            [null, '1'],
            true
        );
    }

}