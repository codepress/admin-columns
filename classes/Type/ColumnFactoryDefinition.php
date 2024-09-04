<?php

declare(strict_types=1);

namespace AC\Type;

final class ColumnFactoryDefinition
{

    private $factory;

    private $parameters;

    public function __construct(string $factory, array $parameters = [])
    {
        $this->factory = $factory;
        $this->parameters = $parameters;
    }

    public function get_factory(): string
    {
        return $this->factory;
    }

    public function get_parameters(): array
    {
        return $this->parameters;
    }

}