<?php

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\AggregateBuilder;
use AC\Setting\Formatter\AggregateBuilderFactory;
use AC\Setting\Formatter\Collection\LimitSeparator;
use AC\Setting\Formatter\Post\PostTerms;
use AC\Settings;

class TaxonomyFactory extends ColumnFactory
{

    public function __construct(
        AggregateBuilderFactory $aggregate_formatter_builder_factory,
        ComponentFactoryRegistry $component_factory_registry,
        Settings\Column\TaxonomyFactory $taxonomy_factory,
        Settings\Column\TermLinkFactory $term_link_factory,
        Settings\Column\NumberOfItemsFactory $number_of_items_factory,
        Settings\Column\SeparatorFactory $separator_factory
    ) {
        parent::__construct($aggregate_formatter_builder_factory, $component_factory_registry);

        $this->add_component_factory($taxonomy_factory);
        $this->add_component_factory($term_link_factory);
        $this->add_component_factory($number_of_items_factory);
        $this->add_component_factory($separator_factory);
    }

    public function get_type(): string
    {
        return 'column-taxonomy';
    }

    protected function get_label(): string
    {
        return __('Taxonomy', 'codepress-admin-columns');
    }

    protected function create_formatter_builder(ComponentCollection $components, Config $config): AggregateBuilder
    {
        return parent::create_formatter_builder($components, $config)
                     ->prepend(new PostTerms((string)$config->get('taxonomy')))
                     ->add(LimitSeparator::create_by_config($config));
    }
}