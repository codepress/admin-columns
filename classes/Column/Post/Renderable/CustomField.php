<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

use AC\Column\Renderable;

class CustomField implements Renderable
{

    private $formatter;

    public function __construct(Renderable\ValueFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function render($id): string
    {
        return $this->formatter->format($id, $id);
    }


}