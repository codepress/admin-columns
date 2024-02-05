<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

use AC\Column\Renderable;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Excerpt implements Renderable
{

    private $formatter;

    public function __construct(Formatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function render($id): string
    {
        return (string)$this->formatter->format(
            new Value(
                $id,
                ac_helper()->post->excerpt((int)$id)
            )
        );
    }

}