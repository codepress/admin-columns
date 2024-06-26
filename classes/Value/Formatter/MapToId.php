<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class MapToId implements Formatter
{

    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function format(Value $value): Value
    {
        $id_value = $this->formatter->format($value);

        echo '<pre>';
        print_r($id_value);
        echo '</pre>';

        if ($id_value->get_value() && is_numeric($id_value->get_value())) {
            return new Value($id_value->get_value());
        }

        throw new ValueNotFoundException();
    }

}