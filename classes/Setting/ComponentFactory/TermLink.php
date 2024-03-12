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
use AC\Setting\Formatter;
use AC\Type\PostTypeSlug;

final class TermLink implements ComponentFactory
{

    private $post_type;

    public function __construct(PostTypeSlug $post_type)
    {
        $this->post_type = $post_type;
    }

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Link To', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'term_link_to',
                    OptionCollection::from_array($this->get_input_options()),
                    (string)$config->get('term_link_to')
                )
            )
            ->set_formatter(new Formatter\Term\TermLink((string)$config->get('term_link_to'), $this->post_type));

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    protected function get_input_options(): array
    {
        return [
            ''       => __('None'),
            'filter' => __('Filter by Term', 'codepress-admin-columns'),
            'edit'   => __('Edit Term', 'codepress-admin-columns'),
        ];
    }

}