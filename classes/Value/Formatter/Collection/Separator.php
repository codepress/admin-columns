<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Collection;

use AC\Setting\CollectionFormatter;
use AC\Setting\ComponentFactory\Separator as Setting;
use AC\Setting\Config;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Separator implements CollectionFormatter
{

    private string $separator;

    private int $limit;

    public function __construct(?string $separator = null, ?int $limit = null)
    {
        $this->separator = $separator ?? self::get_separator();
        $this->limit = $limit ?? 0;
    }

    // TODO David use create_from_settings, which solves some leaking and dry issues
    public static function create_from_config(Config $config): self
    {
        return new self(
            self::get_separator($config->get('separator', ', ')),
            (int)$config->get('number_of_items', 20)
        );
    }

    public static function create_from_settings(
        Config $config,
        string $separator_key,
        string $number_of_items_key
    ): self {
        return new self(
            self::get_separator($config->get($separator_key)),
            $config->has($number_of_items_key)
                ? (int)$config->get($number_of_items_key)
                : null
        );
    }

    private static function get_separator(?string $setting = null): string
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
                return ', ';
        }

        return self::get_separator(Setting::COMMA);
    }

    public function format(ValueCollection $collection): Value
    {
        $values = [];

        foreach ($collection as $item) {
            $value = (string)$item;

            if ('' === $value) {
                continue;
            }

            $values[] = $value;
        }

        return new Value(
            $collection->get_id(),
            ac_helper()->html->more($values, $this->limit, $this->separator)
        );
    }

}