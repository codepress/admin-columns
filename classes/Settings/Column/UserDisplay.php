<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\ArrayImmutable;
use AC\Setting\Formatter;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\Specification;

class UserDisplay extends Settings\Column implements Formatter
{

    public const NAME = 'display_author_as';

    public function __construct(Column $column, Specification $specification = null)
    {
        $this->name = self::NAME;
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = Input\Option\Single::create_select(
            OptionCollection::from_array($this->get_input_options())
        );

        parent::__construct($column, $specification);
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
            'display_name'    => __('Display Name', 'codepress-admin-columns'),
            'first_name'      => __('First Name', 'codepress-admin-columns'),
            'first_last_name' => __('Full Name', 'codepress-admin-columns'),
            'last_name'       => __('Last Name', 'codepress-admin-columns'),
            'nickname'        => __('Nickname', 'codepress-admin-columns'),
            'roles'           => __('Roles', 'codepress-admin-columns'),
            'user_login'      => __('User Login', 'codepress-admin-columns'),
            'user_email'      => __('User Email', 'codepress-admin-columns'),
            'ID'              => __('User ID', 'codepress-admin-columns'),
            'user_nicename'   => __('User Nicename', 'codepress-admin-columns'),
            'user_url'        => __('User Website', 'codepress-admin-columns'),
        ];
    }

}