<?php

namespace AC\Setting\ComponentFactory;

use AC\Setting\Children;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;

class TermProperty extends Builder
{

    private const NAME = 'term_property';

    private $term_link;

    public function __construct(TermLink $term_link)
    {
        $this->term_link = $term_link;
    }

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

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
                $this->term_link->create($config),
            ])
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        $formatters->add(new Formatter\Term\TermProperty($this->get_term_property($config->get(self::NAME, ''))));

        return parent::get_formatters($config, $formatters);
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