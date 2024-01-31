<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Formatter\Aggregate;
use AC\Setting\SettingCollection;
use AC\Setting\Type\Value;
use AC\Settings\Column;

class User extends Column implements Formatter
{

    private $user_format;

    private $settings;

    public function __construct(string $user_format, SettingCollection $settings, Specification $specification = null)
    {
        parent::__construct(
            __('Display', 'codepress-admin-columns'),
            '',
            OptionFactory::create_select(
                'display_author_as',
                $this->get_input_options(),
                $user_format
            ),
            $specification
        );

        $this->user_format = $user_format ?: 'first_last_name';
        $this->settings = $settings;
    }

    public function format(Value $value): Value
    {
        $user_id = $value->get_value();

        if ( ! is_numeric($user_id)) {
            return $value;
        }

        $user_id = (int)$user_id;

        $value = new Value(
            $user_id,
            ac_helper()->user->get_display_name(
                $user_id,
                $this->user_format
            )
        );

        return Aggregate::from_settings($this->settings)->format($value);
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

    public function get_children(): SettingCollection
    {
        return $this->settings;
    }

}