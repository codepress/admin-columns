<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Collection;

use AC\Setting\CollectionFormatter;
use AC\Setting\Config;
use AC\Setting\Type\Value;
use AC\Settings\Column\Separator as Setting;

// TODO default should be ,
// TODO make it available as constants here or in setting
class Separator implements CollectionFormatter
{

    private $separator;

    private $limit;

    public function __construct(string $separator = null, int $limit = 0)
    {
        if (null === $separator) {
            $separator = self::get_separator(Setting::DEFAULT);
        }

        $this->separator = $separator;
        $this->limit = $limit;
    }

    public static function create_from_config(Config $config): self
    {
        return new self(
            self::get_separator((string)$config->get('separator')),
            (int)$config->get('number_of_items')
        );
    }

    private static function get_separator(string $setting): string
    {
        switch ($setting) {
            case Setting::WHITE_SPACE :
                return ' ';
            case Setting::NEW_LINE :
                return '<br>';
            case Setting::HORIZONTAL_RULE :
                return '<hr>';
            case Setting::NONE;
                return '';
            case Setting::COMMA :
            default :
                return ', ';
        }
    }

    public function format(Value $value): Value
    {
        $values = [];

        foreach ($value->get_value() as $item) {
            $values[] = (string)$item;
        }

        return $value->with_value(
            ac_helper()->html->more($values, $this->limit, $this->separator)
        );
    }

}