<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Collection;

use AC\Setting\CollectionFormatter;
use AC\Setting\ComponentFactory\Separator as Setting;
use AC\Setting\Config;
use AC\Type\Value;
use AC\Type\ValueCollection;

// TODO default should be ,
// TODO make it available as constants here or in setting
class Separator implements CollectionFormatter
{

    private $separator;

    private $limit;

    public function __construct(string $separator = null, int $limit = 0)
    {
        if (null === $separator) {
            $separator = self::get_separator(', ');
        }

        $this->separator = $separator;
        $this->limit = $limit;
    }

    public static function create_from_config(Config $config): self
    {
        return new self(
            self::get_separator($config->get('separator', ', ')),
            (int)$config->get('number_of_items', 20)
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

    public function format(ValueCollection $collection): Value
    {
        $values = [];

        foreach ($collection as $item) {
            $values[] = (string)$item;
        }

        return new Value(null, ac_helper()->html->more($values, $this->limit, $this->separator));
    }

}