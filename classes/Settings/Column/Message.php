<?php

namespace AC\Settings\Column;

use AC;
<<<<<<< HEAD
use AC\Setting\Component;
use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\AttributeFactory;
=======
use AC\Expression\Specification;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings;

class Message extends Settings\Column
{

<<<<<<< HEAD
    // TODO Discuss with Stefan e.g. custom setting
    public function __construct(AC\Column $column, string $label, string $message, Specification $conditions = null)
    {
        $this->name = 'message';
        $this->label = $label;
        $this->input = new Element('message', null, null, new AttributeCollection([
            AttributeFactory::create_data('message', $message),
        ]));

=======
    public function __construct(string $label, string $message, Specification $conditions = null)
    {
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
        parent::__construct(
            'message',
            $label,
            '',
            new AC\Setting\Input\Custom('message', [
                'message' => $message,
            ]),
            $conditions
        );
    }

}