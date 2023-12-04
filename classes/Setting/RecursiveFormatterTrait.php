<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\Aggregate;
use AC\Setting\Type\Value;

trait RecursiveFormatterTrait
{

    abstract public function get_children(): SettingCollection;

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return Aggregate::from_settings($this->get_children())->format($value, $options);
    }

}