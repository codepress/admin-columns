<?php

declare(strict_types=1);

namespace AC\Expression;

abstract class OperatorExpression extends Specification
{

    public const OPERATOR = 'operator';

    protected string $operator;

    public function __construct(string $operator)
    {
        $this->operator = $operator;
    }

    public function export(): array
    {
        return array_merge([
            self::OPERATOR => $this->operator,
        ], parent::export());
    }

}