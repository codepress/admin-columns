<?php

declare(strict_types=1);

namespace AC\Admin\Colors\Type;

use InvalidArgumentException;

final class Color
{

    private string $color;

    private string $name;

    public function __construct(string $color, string $name)
    {
        $this->color = $color;
        $this->name = $name;

        $this->validate();
    }

    private function validate(): void
    {
        if ( ! preg_match('/^#([a-f0-9]{3}){1,2}\b$/i', $this->color)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid decimal number.', $this->color));
        }
    }

    public function get_color(): string
    {
        return $this->color;
    }

    public function get_name(): string
    {
        return $this->name;
    }

}