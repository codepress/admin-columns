<?php

declare(strict_types=1);

namespace AC\Asset\Script\Inline;

final class Position
{

    public const BEFORE = 'before';
    public const AFTER = 'after';

    private string $position;

    private function __construct(string $position)
    {
        $this->position = $position;
    }

    public static function before(): self
    {
        return new self(self::BEFORE);
    }

    public static function after(): self
    {
        return new self(self::AFTER);
    }

    public function __toString(): string
    {
        return $this->position;
    }

}