<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class UserLink extends Settings\Column
    implements Settings\FormatValue
{

    public const NAME = 'user_link_to';

    public const PROPERTY_EDIT_USER = 'edit_user';
    public const PROPERTY_VIEW_POSTS = 'view_user_posts';
    public const PROPERTY_VIEW_AUTHOR = 'view_author';
    public const PROPERTY_EMAIL = 'email_user';

    /**
     * @var string
     */
    protected $user_link_to;

    protected function define_options()
    {
        return [
            self::NAME => self::PROPERTY_EDIT_USER,
        ];
    }

    public function format($value, $user_id)
    {
        if ( ! $user_id) {
            return null;
        }

        $link = false;

        switch ($this->get_user_link_to()) {
            case self::PROPERTY_EDIT_USER :
                if (current_user_can('edit_users')) {
                    $link = add_query_arg('user_id', $user_id, self_admin_url('user-edit.php'));
                }

                break;
            case self::PROPERTY_VIEW_POSTS :
                $link = add_query_arg([
                    'post_type' => $this->column->get_post_type(),
                    'author'    => $user_id,
                ], 'edit.php');

                break;
            case self::PROPERTY_VIEW_AUTHOR :
                $link = get_author_posts_url($user_id);

                break;
            case self::PROPERTY_EMAIL :
                if ($email = get_the_author_meta('email', $user_id)) {
                    $link = 'mailto:' . $email;
                }

                break;
        }

        if ($link) {
            $value = ac_helper()->html->link($link, $value);
        }

        return $value;
    }

    public function create_view()
    {
        $select = $this->create_element('select')->set_options($this->get_display_options());

        $view = new View([
            'label'   => __('Link To', 'codepress-admin-columns'),
            'setting' => $select,
        ]);

        return $view;
    }

    protected function get_display_options()
    {
        $options = [
            self::PROPERTY_EDIT_USER   => __('Edit User Profile', 'codepress-admin-columns'),
            self::PROPERTY_EMAIL       => __('User Email', 'codepress-admin-columns'),
            self::PROPERTY_VIEW_POSTS  => __('View User Posts', 'codepress-admin-columns'),
            self::PROPERTY_VIEW_AUTHOR => __('View Public Author Page', 'codepress-admin-columns'),
        ];

        // resort for possible translations
        natcasesort($options);

        $options = array_merge(['' => __('None')], $options);

        return $options;
    }

    /**
     * @return string
     */
    public function get_user_link_to()
    {
        return $this->user_link_to;
    }

    /**
     * @param string $user_link_to
     *
     * @return bool
     */
    public function set_user_link_to($user_link_to)
    {
        $this->user_link_to = $user_link_to;

        return true;
    }

}