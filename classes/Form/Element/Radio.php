<?php

namespace AC\Form\Element;

class Radio extends Checkbox
{

    protected function get_type(): string
    {
        return 'radio';
    }

}