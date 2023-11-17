<?php

declare(strict_types=1);

namespace AC\Setting\Input;

use AC\Setting\Input;

// TODO David put the options here instead of in the setting? That solves some issues for decoration / trees
final class Multiple implements Input
{

    private $type;

    private $multiple;

    private function __construct(string $type, bool $multiple = false)
    {
        $this->type = $type;
        $this->multiple = $multiple;
    }

    public static function create_select(bool $multiple = false): self
    {
        return new self('select', $multiple);
    }

    public static function create_radio(): self
    {
        return new self('radio');
    }

    public static function create_toggle(): self
    {
        return new self('toggle');
    }

    public static function create_checkbox(): self
    {
        return new self('checkbox', true);
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function is_multiple(): bool
    {
        return $this->multiple;
    }

}