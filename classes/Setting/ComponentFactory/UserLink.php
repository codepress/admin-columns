<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter;

final class UserLink extends BaseComponentFactory
{

    public const PROPERTY_EDIT_USER = 'edit_user';
    public const PROPERTY_VIEW_POSTS = 'view_user_posts';
    public const PROPERTY_VIEW_AUTHOR = 'view_author';
    public const PROPERTY_EMAIL = 'email_user';

    private ?PostTypeSlug $post_type;

    public function __construct(?PostTypeSlug $post_type = null)
    {
        $this->post_type = $post_type;
    }

    protected function get_label(Config $config): ?string
    {
        return __('Link To', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'user_link_to',
            OptionCollection::from_array($this->get_input_options()),
            (string)$config->get('user_link_to')
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        if ($config->get('user_link_to') !== '') {
            $formatters->add(new Formatter\User\UserLink(self::PROPERTY_EDIT_USER, $this->post_type));
        }
    }

    protected function get_input_options(): array
    {
        $args = [
            ''                         => __('None'),
            self::PROPERTY_EDIT_USER   => __('Edit User Profile', 'codepress-admin-columns'),
            self::PROPERTY_EMAIL       => __('User Email', 'codepress-admin-columns'),
            self::PROPERTY_VIEW_AUTHOR => __('View Public Author Page', 'codepress-admin-columns'),
        ];

        if ($this->post_type) {
            $args[self::PROPERTY_VIEW_POSTS] = __('View User Posts', 'codepress-admin-columns');
        }

        return $args;
    }

}