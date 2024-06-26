<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\StringComparisonSpecification;
use AC\Setting\Children;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\Number;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Setting\Control\Type\Option;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class UserProperty extends Builder
{

    protected const KEY = 'display_author_as';
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
    public const PROPERTY_META = 'custom_field';

    private $show_related_meta;

    public function __construct(
        bool $show_related_meta = false
    ) {
        $this->show_related_meta = $show_related_meta;
    }

    protected function get_label(Config $config): ?string
    {
        return __('User Display', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'display_author_as',
            $this->get_input_options(),
            $config->get('display_author_as') ?: 'display_name'
        );
    }

    protected function get_formatters(Config $config, FormatterCollection $formatters): FormatterCollection
    {
        switch ($config->get('display_author_as')) {
            case self::PROPERTY_GRAVATAR:
                $formatters->add(new Formatter\User\Property('user_email'));
                $formatters->add(new Formatter\Gravatar((int)$config->get('gravatar_size', '60')));

                break;
            default:
                $formatters->add(new Formatter\User\Property((string)$config->get('display_author_as')));
        }

        return $formatters;
    }

    protected function get_children(Config $config): ?Children
    {
        return new Children(
            new ComponentCollection([
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
            ])
        );
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

        if ($this->show_related_meta) {
            $options->add(new Option(__('Custom Field', 'codepress-admin-columns'), self::PROPERTY_META, $other_group));
        }

        return $options;
    }

}