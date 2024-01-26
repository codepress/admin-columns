<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class UserDisplay extends Settings\Column implements Formatter
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

<<<<<<< HEAD
    public function __construct(
        Column $column,
        Specification $specification = null
    ) {
        $this->name = self::NAME;
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
=======
    public function __construct(Specification $specification = null)
    {
        $input = Input\Option\Single::create_select(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
            OptionCollection::from_array($this->get_input_options()),
            self::PROPERTY_DISPLAY_NAME
        );

        parent::__construct(
            'display_author_as',
            __('Display', 'codepress-admin-columns'),
            '',
            $input,
            $specification
        );
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        return $value->with_value(
            ac_helper()->user->get_display_name(
                $value->get_id(),
                $options->get(self::NAME)
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