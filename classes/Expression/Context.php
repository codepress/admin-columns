<?php

declare(strict_types=1);

namespace AC\Expression;

final class Context
{

    private array $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public function has(string $context): bool
    {
        return isset($this->data[$context]);
    }

    public function get(string $context)
    {
        return $this->data[$context] ?? null;
    }

}