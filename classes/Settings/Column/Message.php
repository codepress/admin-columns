<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component;
use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\AttributeFactory;
use AC\Settings;
use ACP\Expression\Specification;

class Message extends Settings\Column
{

    // TODO Discuss with Stefan e.g. custom setting
    public function __construct(AC\Column $column, string $label, string $message, Specification $conditions = null)
    {
        $this->name = 'message';
        $this->label = $label;
        $this->input = new Element('message', null, null, new AttributeCollection([
            AttributeFactory::create_data('message', $message),
        ]));

        parent::__construct(
            $column,
            $conditions
        );
    }

}