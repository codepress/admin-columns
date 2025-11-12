<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

class FieldTypeBasic extends FieldType
{

    protected function get_field_types(): array
    {
        $options = parent::get_field_types();

        unset(
            $options['relational'],
            $options['multiple']
        );

        return $options;
    }

}