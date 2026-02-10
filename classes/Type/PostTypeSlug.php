<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

final class PostTypeSlug
{

    private string $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;

        $this->validate();
    }

    private function validate(): void
    {
        if ($this->post_type === '') {
            throw new InvalidArgumentException('Post type slug cannot be empty');
        }
    }

    public function __toString(): string
    {
        return $this->post_type;
    }

    public function equals(string $other): bool
    {
        return $this->post_type === $other;
    }

}