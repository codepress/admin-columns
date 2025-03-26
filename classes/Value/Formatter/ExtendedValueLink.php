<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
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
        return $value->with_value(
            $this->factory->create(
                $this->label,
                $value->get_id(),
            )->render()
        );
    }

}