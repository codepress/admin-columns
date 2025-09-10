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

    public function translate_roles(array $role_names): array
    {
        $roles = [];

        $wp_roles = wp_roles()->roles;

        foreach ($role_names as $role) {
            if (isset($wp_roles[$role])) {
                $roles[$role] = translate_user_role($wp_roles[$role]['name']);
            }
        }

        return $roles;
    }

    public function get_roles_names(array $names): array
    {
        $labels = [];

        $roles = $this->get_roles();

        foreach ($names as $name) {
            $label = $roles[$name] ?? null;

            if ($label) {
                $labels[$name] = $label;
            }
        }

        return $labels;
    }

    public function get_role_name(string $role): ?string
    {
        return $this->get_roles()[$role] ?? null;
    }

    public function get_roles(): array
    {
        $roles = [];
        foreach (wp_roles()->roles as $k => $role) {
            $roles[$k] = translate_user_role($role['name']);
        }

        return $roles;
    }

    /**
     * Fetches remote translations. Expires in 7 days.
     */
    public function get_translations_remote(): array
    {
        $translations = get_site_transient('ac_available_translations');

        if (false !== $translations) {
            return $translations;
        }

        require_once(ABSPATH . 'wp-admin/includes/translation-install.php');

        $translations = wp_get_available_translations();

        set_site_transient('ac_available_translations', wp_get_available_translations(), WEEK_IN_SECONDS);

        return $translations;
    }

    /**
     * @deprecated 5.0
     */
    public function get_display_name($user, ?string $format = null): ?string
    {
        _deprecated_function(__METHOD__, '5.0', 'get_fullname');

        $user = get_userdata($user);

        if ( ! $user) {
            return null;
        }

        return $this->get_formatted_name($user);
    }

    /**
     * @deprecated 5.0
     */
    public function get_user_field(string $field, int $user_id)
    {
        _deprecated_function(__METHOD__, '5.0');

        return get_user_by('id', $user_id)->{$field} ?? null;
    }

    /**
     * @deprecated 5.0
     */
    public function get_user($user): ?WP_User
    {
        _deprecated_function(__METHOD__, '5.0', 'get_userdata');

        if (is_numeric($user)) {
            $user = get_userdata($user);
        }

        return $user instanceof WP_User
            ? $user
            : null;
    }

}