<?php

namespace AC\Helper;

use WP_User;

class User
{

    public function get_formatted_name(WP_User $user): ?string
    {
        return trim($user->first_name . ' ' . $user->last_name)
            ?: $user->display_name
                ?: $user->user_login;
    }

    /**
     * @deprecated 7.0
     */
    public function get_translations_remote(): array
    {
        _deprecated_function(__METHOD__, '7.0', 'AC\Herlper\Translations::get_available_translations()');

        return [];
    }

    /**
     * @deprecated 7.0
     */
    public function get_display_name($user, ?string $format = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0', 'get_fullname');

        $user = get_userdata($user);

        if ( ! $user) {
            return null;
        }

        return $this->get_formatted_name($user);
    }

    /**
     * @deprecated 7.0
     */
    public function get_user_field(string $field, int $user_id)
    {
        _deprecated_function(__METHOD__, '7.0');

        return get_user_by('id', $user_id)->{$field} ?? null;
    }

    /**
     * @deprecated 7.0
     */
    public function get_user($user): ?WP_User
    {
        _deprecated_function(__METHOD__, '7.0', 'get_userdata');

        if (is_numeric($user)) {
            $user = get_userdata($user);
        }

        return $user instanceof WP_User
            ? $user
            : null;
    }

    /**
     * @deprecated 7.0
     */
    public function get_roles_names(array $names): array
    {
        _deprecated_function(__METHOD__, '7.0');

        return [];
    }

    /**
     * @deprecated 7.0
     */
    public function get_role_name(string $role): ?string
    {
        _deprecated_function(__METHOD__, '7.0');

        return $this->get_roles()[$role] ?? null;
    }

    /**
     * @deprecated 7.0
     */
    public function get_roles(): array
    {
        _deprecated_function(__METHOD__, '7.0', 'AC\Helper\UserRoles::find_all_roles');

        $roles = [];
        foreach (wp_roles()->roles as $k => $role) {
            $roles[$k] = translate_user_role($role['name']);
        }

        return $roles;
    }

    /**
     * @deprecated 7.0
     */
    public function translate_roles(array $role_names): array
    {
        _deprecated_function(__METHOD__, '7.0', 'AC\Helper\UserRoles::find_all_roles');

        return [];
    }

}