<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC;
use AC\Expression\StringComparisonSpecification;
use AC\FormatterCollection;
use AC\Setting\AttributeCollection;
use AC\Setting\AttributeFactory;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\Type\Option;

class UserProperty extends BaseComponentFactory
{

    public const KEY = 'display_author_as';
    public const PROPERTY_DISPLAY_NAME = 'display_name';
    public const PROPERTY_EMAIL = 'user_email';
    public const PROPERTY_FULL_NAME = 'first_last_name';
    public const PROPERTY_FIRST_NAME = 'first_name';
    public const PROPERTY_ID = 'ID';
    public const PROPERTY_LAST_NAME = 'last_name';
    public const PROPERTY_LOGIN = 'user_login';
    public const PROPERTY_NICENAME = 'user_nicename';
    public const PROPERTY_URL = 'user_url';
    public const PROPERTY_NICKNAME = 'nickname';
    public const PROPERTY_ROLES = 'roles';
    public const PROPERTY_GRAVATAR = 'gravatar';

    protected function get_label(Config $config): ?string
    {
        return __('User Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'display_author_as',
            $this->get_input_options(),
            $config->get('display_author_as') ?: 'display_name',
            null,
            false,
            new AttributeCollection([
                AttributeFactory::create_refresh(),
            ])
        );
    }

    protected function add_formatters(Config $config, FormatterCollection $formatters): void
    {
        $property = $config->get('display_author_as');

        switch ($property) {
            case self::PROPERTY_GRAVATAR:
                $formatters->add(new AC\Formatter\User\Property('user_email'));
                $formatters->add(new AC\Formatter\Avatar((int)$config->get('gravatar_size', '60')));

                break;
            case self::PROPERTY_FULL_NAME:
                $formatters->add(new AC\Formatter\User\FullName());
                break;
            case self::PROPERTY_DISPLAY_NAME:
            case self::PROPERTY_EMAIL:
            case self::PROPERTY_FIRST_NAME:
            case self::PROPERTY_ID:
            case self::PROPERTY_LAST_NAME:
            case self::PROPERTY_LOGIN:
            case self::PROPERTY_NICENAME:
            case self::PROPERTY_URL:
            case self::PROPERTY_NICKNAME:
                $formatters->add(new AC\Formatter\User\Property((string)$property));
                break;
            case self::PROPERTY_ROLES:
                $formatters->add(new AC\Formatter\User\TranslatedRoles());
        }
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            $this->get_children_component_collection($config)
        );
    }

    protected function get_children_component_collection(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            new Component(
                __('Image Size', 'codepress-admin-columns'),
                null,
                Number::create_single_step(
                    'gravatar_size',
                    0,
                    100,
                    (int)$config->get('gravatar_size', 60),
                    null,
                    null,
                    'px'
                ),
                StringComparisonSpecification::equal('gravatar')
            ),
        ]);
    }

    protected function get_input_options(): OptionCollection
    {
        $options = new OptionCollection();

        $default_options = [
            self::PROPERTY_DISPLAY_NAME => __('Display Name', 'codepress-admin-columns'),
            self::PROPERTY_FIRST_NAME   => __('First Name', 'codepress-admin-columns'),
            self::PROPERTY_FULL_NAME    => __('Full Name', 'codepress-admin-columns'),
            self::PROPERTY_LAST_NAME    => __('Last Name', 'codepress-admin-columns'),
            self::PROPERTY_NICKNAME     => __('Nickname', 'codepress-admin-columns'),
            self::PROPERTY_LOGIN        => __('User Login', 'codepress-admin-columns'),
            self::PROPERTY_EMAIL        => __('User Email', 'codepress-admin-columns'),
            self::PROPERTY_ID           => __('User ID', 'codepress-admin-columns'),
            self::PROPERTY_NICENAME     => __('User Nicename', 'codepress-admin-columns'),
            self::PROPERTY_URL          => __('User Website', 'codepress-admin-columns'),
        ];

        foreach ($default_options as $key => $label) {
            $options->add(
                new Option($label, $key, __('Default', 'codepress-admin-columns'))
            );
        }

        $other_group = __('Other', 'codepress-admin-columns');

        $options->add(new Option(__('Roles', 'codepress-admin-columns'), self::PROPERTY_ROLES, $other_group));
        $options->add(new Option(__('Gravatar', 'codepress-admin-columns'), self::PROPERTY_GRAVATAR, $other_group));

        return $options;
    }

}