<?php

namespace AC\Settings\Column;

use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\Type\Attribute;
use AC\Settings;

class Message extends Settings\Component
{

    // TODO David remove, just use the factory
    // TODO Tobias remove, just use the factory
    public function __construct(string $label, string $message)
    {
        parent::__construct(
            'message',
            $label,
            '',
            new AttributeCollection([
                new Attribute('message', $message),
            ])
        );
    }

}