<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Type\Value;
use AC\Settings;

// TODO implement formatter (Interface was CollectionFormatter)
class Separator extends Settings\Control implements AC\Setting\Formatter
{

    public const NAME = 'separator';

    private $separator;

    public function __construct(string $separator, Specification $conditions = null)
    {
        $input = AC\Setting\Component\Input\OptionFactory::create_select(
            'separator',
            AC\Setting\Component\OptionCollection::from_array([
                ''                => __('Default', 'codepress-admin-columns'),
                'comma'           => __('Comma Separated', 'codepress-admin-columns'),
                'horizontal_rule' => __('Horizontal Rule', 'codepress-admin-columns'),
                'newline'         => __('New Line', 'codepress-admin-columns'),
                'none'            => __('None', 'codepress-admin-columns'),
                'white_space'     => __('Whitespace', 'codepress-admin-columns'),
            ]),
            $separator
        );

        parent::__construct(
            $input,
            __('Separator', 'codepress-admin-columns'),
            null,
            $conditions
        );

        $this->separator = $separator;
    }

    private function create_separator(): string
    {
        switch ($this->separator) {
            case 'white_space' :
                return ' ';
            case 'newline' :
                return '<br>';
            case 'horizontal_rule' :
                return '<hr>';
            case 'none';
                return '';
            case 'comma' :
            default :
                return ', ';
        }
    }

    public function format(Value $value): Value
    {
        $values = [];

        foreach ($value->get_value() as $_value) {
            $values[] = (string)$_value;
        }

        return $value->with_value(
            implode($this->create_separator(), $values)
        );
    }

}