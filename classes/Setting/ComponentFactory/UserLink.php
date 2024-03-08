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

final class UserLink implements ComponentFactory
{

    public const PROPERTY_EDIT_USER = 'edit_user';
    public const PROPERTY_VIEW_POSTS = 'view_user_posts';
    public const PROPERTY_VIEW_AUTHOR = 'view_author';
    public const PROPERTY_EMAIL = 'email_user';

    public function create(Config $config, Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Link To', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'user_link_to',
                    OptionCollection::from_array($this->get_input_options()),
                    (string)$config->get('user_link_to')
                )
            )
            ->set_formatter(new Formatter\User\UserLink((string)$config->get('user_link_to')));

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    protected function get_input_options(): array
    {
        return [
            ''                         => __('None'),
            self::PROPERTY_EDIT_USER   => __('Edit User Profile', 'codepress-admin-columns'),
            self::PROPERTY_EMAIL       => __('User Email', 'codepress-admin-columns'),
            self::PROPERTY_VIEW_POSTS  => __('View User Posts', 'codepress-admin-columns'),
            self::PROPERTY_VIEW_AUTHOR => __('View Public Author Page', 'codepress-admin-columns'),
        ];
    }

}