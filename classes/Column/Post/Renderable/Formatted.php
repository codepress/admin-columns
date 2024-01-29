<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

use AC\Column\Renderable;
use AC\Column\Renderable\ValueFormatter;

abstract class Formatted implements Renderable
{

    private $formatter;

    public function __construct(ValueFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    abstract protected function get_pre_formatted_value($id);

    public function render($id): string
    {
        return $this->formatter->format(
            $this->get_pre_formatted_value($id),
            $id
        ) ?: '&ndash;';
    }

}