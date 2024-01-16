<?php

declare(strict_types=1);

namespace AC\Expression;

interface Specification
{

    public function is_satisfied_by(string $value): bool;

    public function get_rules(string $value): array;

    public function and_specification(Specification $specification): self;

    public function or_specification(Specification $specification): self;

    public function not(): self;

}