<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Config;
use AC\Settings\Setting;
use AC\Settings\SettingFactory;

class MessageFactory implements SettingFactory
{

    private $label;

    private $message;

    public function __construct(string $label, string $message)
    {
        $this->label = $label;
        $this->message = $message;
    }

    public function create(Config $config, Specification $specification = null): Setting
    {
        return new Message(
            $this->label,
            $this->message,
            $specification
        );
    }

}