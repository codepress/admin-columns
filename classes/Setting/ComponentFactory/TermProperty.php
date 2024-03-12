<?php

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;

class TermProperty implements ComponentFactory
{

    private const NAME = 'term_property';

    private $term_link;

    public function __construct(TermLink $term_link)
    {
        $this->term_link = $term_link;
    }

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Term Display', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    OptionCollection::from_array(
                        [
                            ''     => __('Title'),
                            'slug' => __('Slug'),
                            'id'   => __('ID'),
                        ]
                    ),
                    (string)$config->get(self::NAME)
                )
            )
            ->set_children(
                new Children(
                    new ComponentCollection([
                        $this->term_link->create($config),
                    ])
                )
            )
            ->set_formatter(
                new Formatter\Term\TermProperty($this->get_term_property((string)$config->get(self::NAME)))
            );

        return $builder->build();
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