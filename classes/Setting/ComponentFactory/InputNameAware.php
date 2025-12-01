<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

interface InputNameAware
{

    /**
     * Exposes the name the input will get, so value can be retrieved from the config without building the component
     */
    public function get_name(): string;

}