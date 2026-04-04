<?php

declare(strict_types=1);

namespace AC\Form\Element;

class Radio extends Checkbox
{

    protected function get_type(): string
    {
        return 'radio';
    }

}