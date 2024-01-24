<?php

declare(strict_types=1);

namespace AC\Storage\Repository;

use AC\Settings\GeneralOption;
use AC\Settings\GeneralOptionFactory;

class EditButton
{

    private $option_factory;

    public function __construct()
    {
        $this->option_factory = new GeneralOptionFactory();
    }

    private function get_storage(): GeneralOption
    {
        return $this->option_factory->create();
    }

    public function is_active(): bool
    {
        $value = $this->get_storage()->get('show_edit_button');

        return in_array($value, ['1', null], true);
    }

}