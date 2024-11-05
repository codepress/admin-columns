<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Type\PostTypeSlug;

class Taxonomy implements ComponentFactory
{

    private PostTypeSlug $post_type;

    public function __construct(PostTypeSlug $post_type)
    {
        $this->post_type = $post_type;
    }

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Taxonomy', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'taxonomy',
                    OptionCollection::from_array($this->get_taxonomies()),
                    (string)$config->get('taxonomy')
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    protected function get_taxonomies(): array
    {
        $taxonomies = get_object_taxonomies((string)$this->post_type, 'objects');

        $options = [];

        foreach ($taxonomies as $taxonomy) {
            if ('post_format' === $taxonomy->name) {
                continue;
            }

            $options[$taxonomy->name] = sprintf('%s (%s)', $taxonomy->label, $taxonomy->name);
        }

        natcasesort($options);

        return $options;
    }

}