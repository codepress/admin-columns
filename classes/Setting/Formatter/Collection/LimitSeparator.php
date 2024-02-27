<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Collection;

use AC\Setting\CollectionFormatter;
use AC\Setting\Config;
use AC\Setting\Type\Value;

class LimitSeparator implements CollectionFormatter
{

    private $separator;

    private $limit;

    public function __construct(string $separator = null, int $limit = 0)
    {
        if (null === $separator) {
            $separator = ', ';
        }

        $this->separator = $separator;
        $this->limit = $limit;
    }

    public static function create_by_config(Config $config): self
    {
        switch ($config->get('separator')) {
            case 'white_space' :
                $separator = ' ';
                break;
            case 'newline' :
                $separator = '<br>';
                break;
            case 'horizontal_rule' :
                $separator = '<hr>';
                break;
            case 'none';
                $separator = '';
                break;
            case 'comma' :
            default :
                $separator = ', ';
        }

        return new self(
            $separator,
            (int)$config->get('number_of_items')
        );
    }

    public function format(Value $value): Value
    {
        $values = [];

        foreach ($value->get_value() as $_value) {
            $values[] = (string)$_value;
        }

        return $value->with_value(
            ac_helper()->html->more($values, $this->limit, $this->separator)
        );
    }

}