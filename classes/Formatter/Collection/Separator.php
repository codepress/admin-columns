<?php

declare(strict_types=1);

namespace AC\Formatter\Collection;

use AC\CollectionFormatter;
use AC\Setting\ComponentFactory\Separator as Setting;
use AC\Setting\Config;
use AC\Type\Value;
use AC\Type\ValueCollection;

class Separator implements CollectionFormatter
{

    private string $separator;

    private int $limit;

    public function __construct(?string $separator = null, int $limit = 0)
    {
        $this->separator = $separator ?? ', ';
        $this->limit = $limit;
    }

    public static function create_from_config(Config $config, int $limit = 20): self
    {
        return new self(
            self::get_separator($config->get('separator', ', ')),
            (int)$config->get('number_of_items', $limit)
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
            case Setting::NONE:
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
            $value = (string)$item;

            if ('' === $value) {
                continue;
            }

            $values[] = $value;
        }

        return new Value(
            $collection->get_id(),
            $this->implode($values, $this->limit, $this->separator)
        );
    }

    public function implode(array $array, int $limit = 10, string $glue = ', '): string
    {
        if ($limit <= 0 || count($array) <= $limit) {
            return implode($glue, $array);
        }

        $first_set = array_slice($array, 0, $limit);
        $last_set = array_slice($array, $limit);

        ob_start();

        if ($first_set) {
            $first = sprintf('<span class="ac-show-more__part -first">%s</span>', implode($glue, $first_set));

            $more = $last_set
                ? sprintf(
                    '<span class="ac-show-more__part -more">%s%s</span>',
                    $glue,
                    implode($glue, $last_set)
                )
                : '';

            $content = sprintf('<span class="ac-show-more__content">%s%s</span>', $first, $more);

            $toggler = $last_set
                ? sprintf(
                    '<span class="ac-show-more__divider">|</span><a class="ac-show-more__toggle" data-show-more-toggle data-more="%1$s" data-less="%2$s">%1$s</a>',
                    sprintf(__('%s more', 'codepress-admin-columns'), count($last_set)),
                    strtolower(__('Hide', 'codepress-admin-columns'))
                )
                : '';

            echo sprintf('<span class="ac-show-more">%s</span>', $content . $toggler);
        }

        return ob_get_clean();
    }

}