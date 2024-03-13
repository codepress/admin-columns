<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Relation;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Formatter;

class PostLink implements ComponentFactory
{

    private const NAME = 'post_link_to';

    private $relation;

    public function __construct(Relation $relation)
    {
        $this->relation = $relation;
    }

    public function create(Config $config, Specification $conditions = null): Component
    {
        $value = (string)$config->get(self::NAME);

        $builder = (new ComponentBuilder())
            ->set_label(__('Link To', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    self::NAME,
                    OptionCollection::from_array($this->get_display_options()),
                    $value
                )
            )
            ->set_formatter(new Formatter\Post\PostLink($value));

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    protected function get_display_options(): array
    {
        // Default options
        $options = [
            ''            => __('None'),
            'edit_post'   => __('Edit Post'),
            'view_post'   => __('View Post'),
            'edit_author' => __('Edit Post Author', 'codepress-admin-columns'),
            'view_author' => __('View Public Post Author Page', 'codepress-admin-columns'),
        ];

        if ($this->relation) {
            $relation_options = [
                'edit_post'   => _x('Edit %s', 'post'),
                'view_post'   => _x('View %s', 'post'),
                'edit_author' => _x('Edit %s Author', 'post', 'codepress-admin-columns'),
                'view_author' => _x('View Public %s Author Page', 'post', 'codepress-admin-columns'),
            ];

            $label = $this->relation->get_labels()->singular_name;

            foreach ($relation_options as $k => $option) {
                $options[$k] = sprintf($option, $label);
            }
        }

        return $options;
    }

}