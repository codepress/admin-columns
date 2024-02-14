<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\Formatter;
use AC\Setting\RecursiveFormatterTrait;
use AC\Setting\Type\Value;
use AC\Settings\Setting;

class User extends Setting implements Formatter, AC\Setting\Recursive
{

    use RecursiveFormatterTrait;

    private $user_format;

    private $settings;

    public function __construct(string $user_format, ComponentCollection $settings, Specification $specification = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'display_author_as',
                $this->get_input_options(),
                $user_format
            ),
            __('Display', 'codepress-admin-columns'),
            null,
            $specification
        );

        $this->user_format = $user_format ?: 'first_last_name';
        $this->settings = $settings;
    }

    public function format(Value $value): Value
    {
        $value = $value->with_value(
            ac_helper()->user->get_display_name(
                (int)$value->get_id(),
                $this->user_format
            )
        );
        
        return $this->get_recursive_formatter()
                    ->format($value);
    }

    public function is_parent(): bool
    {
        return false;
    }

    protected function get_input_options(): OptionCollection
    {
        $options = [
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

        natcasesort($options);

        return OptionCollection::from_array($options);
    }

    public function get_children(): ComponentCollection
    {
        return $this->settings;
    }

}