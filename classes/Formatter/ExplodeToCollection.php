<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Type\Value;
use AC\Type\ValueCollection;
use InvalidArgumentException;

final class ExplodeToCollection extends ArrayToCollection
{

    private string $separator;

    public function __construct(string $separator)
    {
        parent::__construct();

        if ('' === $separator) {
            throw new InvalidArgumentException('Separator can not be empty.');
        }

        $this->separator = $separator;
    }

    public function format(Value $value): ValueCollection
    {
        $explode = (string)$value;

        if (empty($explode)) {
            throw new ValueNotFoundException('No values found');
        }

        $result = explode($this->separator, $explode);

        if ($result === ['']) {
            throw new ValueNotFoundException('No values found');
        }

        return parent::format(
            $value->with_value($result)
        );
    }

}
