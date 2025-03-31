<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter\Collection\Separator;
use AC\Value\Formatter\Post\PostTerms;

class TaxonomyFactory extends BaseColumnFactory
{

    private ComponentFactory\TaxonomyFactory $taxonomy_factory;

    private ComponentFactory\TermLink $term_link_factory;

    private ComponentFactory\NumberOfItems $number_of_items_factory;

    private ComponentFactory\Separator $separator_factory;

    private PostTypeSlug $post_type;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        ComponentFactory\TaxonomyFactory $taxonomy_factory,
        ComponentFactory\TermLink $term_link_factory,
        ComponentFactory\NumberOfItems $number_of_items_factory,
        ComponentFactory\Separator $separator_factory,
        PostTypeSlug $post_type
    ) {
        parent::__construct($base_settings_builder);

        $this->taxonomy_factory = $taxonomy_factory;
        $this->term_link_factory = $term_link_factory;
        $this->number_of_items_factory = $number_of_items_factory;
        $this->separator_factory = $separator_factory;
        $this->post_type = $post_type;
    }

    public function get_column_type(): string
    {
        return 'column-taxonomy';
    }

    public function get_label(): string
    {
        return __('Taxonomy', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->taxonomy_factory->create($this->post_type));
        $factories->add($this->term_link_factory);
        $factories->add($this->number_of_items_factory);
        $factories->add($this->separator_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new PostTerms((string)$config->get('taxonomy', '')));
        $formatters->add(Separator::create_from_config($config));
    }

}