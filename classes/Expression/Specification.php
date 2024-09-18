<?php

declare(strict_types=1);

namespace AC\Expression;

use ReflectionClass;

abstract class Specification
{

    public const OPERATOR = 'operator';
    public const SPECIFICATION = 'specification';

    protected string $operator;

    public function __construct(string $operator)
    {
        $this->operator = $operator;
    }

    abstract public function is_satisfied_by($value): bool;

    public function and_specification(Specification $specification): self
    {
        return new AndSpecification([$this, $specification]);
    }

    public function or_specification(Specification $specification): self
    {
        return new OrSpecification([$this, $specification]);
    }

    public function not(): self
    {
        return new NotSpecification($this);
    }

    public function export(): array
    {
        return [
            self::SPECIFICATION => $this->get_specification(),
            self::OPERATOR      => $this->operator,
        ];
    }

    private function get_specification(): string
    {
        $specification = strtolower(
            preg_replace(
                '/(?<!^)[A-Z]/',
                '_$0',
                (new ReflectionClass($this))->getShortName()
            )
        );

        $needle = '_specification';

        if (str_ends_with($specification, $needle)) {
            $specification = substr($specification, 0, -strlen($needle));
        }

        return $specification;
    }

}