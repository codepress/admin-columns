<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Type\Value;

final class Wrapper implements Formatter
{

    private string $preprend;

    private string $append;

    public function __construct(string $preprend, string $append)
    {
        $this->preprend = $preprend;
        $this->append = $append;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            $this->preprend . $value . $this->append
        );
    }

}