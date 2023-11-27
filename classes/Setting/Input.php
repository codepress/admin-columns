<?php

declare(strict_types=1);

namespace AC\Setting;

abstract class Input
{

    protected $type;

    protected $default;

    protected $placeholder;

    protected $class;

    public function __construct(
        string $type,
        $default = null,
        string $placeholder = null,
        string $class = null
    ) {
        $this->type = $type;
        $this->default = $default;
        $this->placeholder = $placeholder;
        $this->class = $class;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function has_default(): bool
    {
        return $this->default !== null;
    }

    public function get_default()
    {
        return $this->default;
    }

    public function has_placeholder(): bool
    {
        return $this->placeholder !== null;
    }

    public function get_placeholder(): ?string
    {
        return $this->placeholder;
    }

    public function has_class(): bool
    {
        return $this->class !== null;
    }

    public function get_class(): ?string
    {
        return $this->class;
    }

}