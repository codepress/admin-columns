<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Collection\Separator;
use AC\Setting\Formatter\Post\PostTerms;
use AC\Setting\FormatterCollection;

class TaxonomyFactory extends BaseColumnFactory
{

    private $taxonomy_factory;

    private $term_link_factory;

    private $number_of_items_factory;

    private $separator_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\Taxonomy $taxonomy_factory,
        ComponentFactory\TermLink $term_link_factory,
        ComponentFactory\NumberOfItems $number_of_items_factory,
        ComponentFactory\Separator $separator_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->taxonomy_factory = $taxonomy_factory;
        $this->term_link_factory = $term_link_factory;
        $this->number_of_items_factory = $number_of_items_factory;
        $this->separator_factory = $separator_factory;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->taxonomy_factory);
        $this->add_component_factory($this->term_link_factory);
        $this->add_component_factory($this->number_of_items_factory);
        $this->add_component_factory($this->separator_factory);
    }

    public function get_type(): string
    {
        return 'column-taxonomy';
    }

    protected function get_label(): string
    {
        return __('Taxonomy', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new PostTerms((string)$config->get('taxonomy')));
        $formatters = parent::get_formatters($components, $config, $formatters);
        $formatters->add(Separator::create_from_config($config));

        return $formatters;
    }

}