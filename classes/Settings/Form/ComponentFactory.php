<?php

declare(strict_types=1);

namespace AC\Settings\Form;

use AC\Settings\Component;

interface ComponentFactory
{

    public function create(): Component;

}