<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Value\ExtendedValueLinkFactory;

final class ExtendedValueLink implements Formatter
{

    private ExtendedValueLinkFactory $factory;

    private string $label;

    public function __construct(ExtendedValueLinkFactory $factory, string $label)
    {
        $this->factory = $factory;
        $this->label = $label;
    }

    public function format(Value $value)
    {
        if ( ! $value->get_value()) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->factory->create(
                $this->label,
                $value->get_id(),
            )->render()
        );
    }

}