<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class UserDisplay extends Settings\Setting implements Formatter
{

    public const NAME = 'display_author_as';

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

    protected $user_format;

    public function __construct(string $user_format, Specification $specification = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'display_author_as',
                OptionCollection::from_array($this->get_input_options()),
                $user_format
            ),
            __('Display', 'codepress-admin-columns'),
            null,
            $specification
        );
        $this->user_format = $user_format;
    }

    public function format(Value $value): Value
    {
        // TODO
        return $value->with_value(
            ac_helper()->user->get_display_name(
                (int)$value->get_value()
            )
        );
    }

    protected function get_input_options(): array
    {
        return [
            self::PROPERTY_DISPLAY_NAME => __('Display Name', 'codepress-admin-columns'),
            self::PROPERTY_FIRST_NAME   => __('First Name', 'codepress-admin-columns'),
            self::PROPERTY_FULL_NAME    => __('Full Name', 'codepress-admin-columns'),
            self::PROPERTY_LAST_NAME    => __('Last Name', 'codepress-admin-columns'),
            self::PROPERTY_NICKNAME     => __('Nickname', 'codepress-admin-columns'),
            self::PROPERTY_ROLES        => __('Roles', 'codepress-admin-columns'),
            self::PROPERTY_LOGIN        => __('User Login', 'codepress-admin-columns'),
            self::PROPERTY_EMAIL        => __('User Email', 'codepress-admin-columns'),
            self::PROPERTY_ID           => __('User ID', 'codepress-admin-columns'),
            self::PROPERTY_NICENAME     => __('User Nicename', 'codepress-admin-columns'),
            self::PROPERTY_URL          => __('User Website', 'codepress-admin-columns'),
        ];
    }
}