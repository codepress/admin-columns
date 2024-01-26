<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component;
use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\AttributeFactory;
use AC\Expression\Specification;
use AC\Settings;

class Message extends Settings\Column
{

    // TODO Discuss with Stefan e.g. custom setting
    public function __construct(string $label, string $message, Specification $conditions = null)
    {
        parent::__construct(
            'message',
            $label,
            '',
            // TODO create a component
            new Element('message', null, null, new AttributeCollection([
                AttributeFactory::create_data('message', $message),
            ]),
            $conditions
        );
    }

}