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
use AC\Value\Formatter;

final class CommentLink implements ComponentFactory
{

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $comment_link_to = (string)$config->get('comment_link_to');

        $builder = (new ComponentBuilder())
            ->set_label(
                __('Link To', 'codepress-admin-columns')
            )
            ->set_input(
                OptionFactory::create_select(
                    'comment_link_to',
                    OptionCollection::from_array([
                        ''             => __('None'),
                        'view_comment' => __('View Comment', 'codepress-admin-columns'),
                        'edit_comment' => __('Edit Comment', 'codepress-admin-columns'),
                    ]),
                    $comment_link_to
                )
            )
            ->set_formatter(
                new \AC\Formatter\Comment\CommentLink($comment_link_to)
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

}