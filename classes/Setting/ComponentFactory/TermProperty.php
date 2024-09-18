<?php

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class TermProperty extends Builder
{

    private const NAME = 'term_property';

    protected function get_label(Config $config): ?string
    {
        return __('Term Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            self::NAME,
            OptionCollection::from_array(
                [
                    ''     => __('Title'),
                    'slug' => __('Slug'),
                    'id'   => __('ID'),
                ]
            ),
            $config->get(self::NAME, '')
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $formatters->add(new Formatter\Term\TermProperty($this->get_term_property($config->get(self::NAME, ''))));
    }

    private function get_term_property(string $value): string
    {
        switch ($value) {
            case 'slug':
            case 'id':
                return $value;
            default:
                return 'name';
        }
    }

}