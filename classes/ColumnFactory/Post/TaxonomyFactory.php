<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Collection\Separator;
use AC\Formatter\Post\PostTerms;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\DefaultSettingsBuilder;
use AC\Type\PostTypeSlug;

class TaxonomyFactory extends BaseColumnFactory
{

    private ComponentFactory\TaxonomyFactory $taxonomy_factory;

    private ComponentFactory\TermLink $term_link_factory;

    private ComponentFactory\NumberOfItems $number_of_items_factory;

    private ComponentFactory\Separator $separator_factory;

    private PostTypeSlug $post_type;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        ComponentFactory\TaxonomyFactory $taxonomy_factory,
        ComponentFactory\TermLink $term_link_factory,
        ComponentFactory\NumberOfItems $number_of_items_factory,
        ComponentFactory\Separator $separator_factory,
        PostTypeSlug $post_type
    ) {
        parent::__construct($default_settings_builder);

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

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->taxonomy_factory->create($this->post_type)->create($config),
            $this->term_link_factory->create($config),
            $this->number_of_items_factory->create($config),
            $this->separator_factory->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->prepend(new PostTerms((string)$config->get('taxonomy', '')))
                     ->add(Separator::create_from_config($config));
    }

}