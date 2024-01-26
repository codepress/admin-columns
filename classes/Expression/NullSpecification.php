<?php

declare(strict_types=1);

namespace AC\Expression;

final class NullSpecification implements Specification
{

    use SpecificationTrait;

    public function is_satisfied_by(string $value): bool
    {
        return true;
    }

    public function get_rules(string $value): array
    {
        return [
            Rules::TYPE => 'null',
        ];
    }

}