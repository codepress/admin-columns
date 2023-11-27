<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;

class Open extends Input
{

    protected $append;

    public function __construct(
        string $type,
        string $default = null,
        string $placeholder = null,
        string $class = null,
        string $append = null
    ) {
        parent::__construct($type, $default, $placeholder, $class);

        $this->append = $append;
    }

    public static function create_text(
        $default = null,
        string $placeholder = null,
        string $class = null,
        string $append = null
    ): self {
        return new self('text', $default, $placeholder, $class, $append);
    }

    public function has_append(): bool
    {
        return $this->append !== null;
    }

    public function get_append(): ?string
    {
        return $this->append;
    }

}