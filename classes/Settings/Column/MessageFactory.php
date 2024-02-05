<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\Type\Attribute;
use AC\Settings\Component;
use AC\Settings\Form\ComponentFactory;

class MessageFactory implements ComponentFactory
{

    private $label;

    private $message;

    public function __construct(string $label, string $message)
    {
        $this->label = $label;
        $this->message = $message;
    }

    public function create(): Component
    {
        return new Component(
            'message',
            $this->label,
            '',
            new AttributeCollection([
                new Attribute('message', $this->message),
            ])
        );
    }

}