<?php

namespace AC\Column\User;

use AC\Column;

class Login extends Column
{

    public function __construct()
    {
        $this->set_type('column-user_login')
             ->set_label(__('Username', 'codepress-admin-columns'));
    }

    public function get_value($user_id)
    {
        $user = get_userdata($user_id);

        $value = $user->user_login;

        if (current_user_can('edit_user', $user->ID)) {
            $value = sprintf('<a href="%s">%s</a>', get_edit_user_link($user->ID), $value);
        }

        if (in_array($user->user_login, get_super_admins(), true)) {
            $value .= ' &mdash; ' . __('Super Admin');
        }

        return $value;
    }

    public function get_raw_value($user_id)
    {
        return get_userdata($user_id)->user_login;
    }

}