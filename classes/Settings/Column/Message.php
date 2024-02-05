<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Settings;

class Message extends Settings\Setting
{

    // TODO Discuss with Stefan e.g. custom setting
    public function __construct(string $label, string $message, Specification $conditions = null)
    {
        parent::__construct(
            $label,
            '',
            null,
            // TODO create a component
            //            new Element('message', null, null, new AttributeCollection([
            //                AttributeFactory::create_data('message', $message),
            //            ]),
            $conditions
        );
    }

}