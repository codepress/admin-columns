<?php

namespace AC\Helper\Select;

use AC\ArrayIterator;
use LogicException;

/**
 * @property $array Option[]|OptionGroup[]
 */
class Options extends ArrayIterator
{

    public function __construct(array $options)
    {
        parent::__construct($options);

        $this->validate();
    }

    private function validate(): void
    {
        foreach ($this as $option) {
            if ( ! $option instanceof Option && ! $option instanceof OptionGroup) {
                throw new LogicException('Only Option and OptionGroup objects allowed.');
            }
        }
    }

    public static function create_from_array(array $array): self
    {
        $options = [];

        foreach ($array as $key => $value) {
            $options[] = new Option((string)$key, (string)$value);
        }

        return new self($options);
    }

}