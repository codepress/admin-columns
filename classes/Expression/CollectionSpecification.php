<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\OperatorNotFoundException;
use Traversable;

class CollectionSpecification extends OperatorExpression implements FactSpecification
{

    protected Traversable $fact;

    public function __construct(
        string $operator,
        Traversable $fact
    ) {
        parent::__construct($operator);

        $this->fact = $fact;
    }

    public function is_satisfied_by($value): bool
    {
        switch ($this->operator) {
            case CollectionOperators::CONTAINS:
            case CollectionOperators::NOT_CONTAINS:
                foreach ($this->fact as $item) {
                    if ($item === $value) {
                        return $this->operator === CollectionOperators::CONTAINS;
                    }
                }

                return $this->operator === CollectionOperators::NOT_CONTAINS;
        }

        throw new OperatorNotFoundException($this->operator);
    }

    public function export(): array
    {
        return array_merge([
            self::FACT => $this->fact,
        ], parent::export());
    }

}