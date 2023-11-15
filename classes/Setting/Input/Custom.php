<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;
use AC\Setting\Setting;

final class Custom implements Input
{

    private $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function get_type(): string
    {
        return $this->setting->get_name();
    }

}