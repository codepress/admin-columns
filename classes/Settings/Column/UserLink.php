<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class UserLink extends Settings\Setting implements AC\Setting\Formatter
{

    public const NAME = 'user_link_to';

    public const PROPERTY_EDIT_USER = 'edit_user';
    public const PROPERTY_VIEW_POSTS = 'view_user_posts';
    public const PROPERTY_VIEW_AUTHOR = 'view_author';
    public const PROPERTY_EMAIL = 'email_user';

    private $post_type;

    private $link_to;

    public function __construct(string $link_to = '', string $post_type = null, Specification $conditions = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'user_link_to',
                OptionCollection::from_array(
                    $this->get_input_options()
                ),
                self::PROPERTY_EDIT_USER
            ),
            __('Link To', 'codepress-admin-columns'),
            null,
            $conditions
        );

        $this->post_type = $post_type;
        $this->link_to = $link_to;
    }

    public function format(Value $value): Value
    {
        $user_id = $value->get_id();

        // TODO test with Custom Field user type
        if ( ! is_numeric($user_id)) {
            return $value;
        }

        $user_id = (int)$user_id;

        switch ($this->link_to) {
            case self::PROPERTY_EDIT_USER:
                if (current_user_can('edit_users')) {
                    $link = add_query_arg('user_id', $user_id, self_admin_url('user-edit.php'));
                }
                break;
            case self::PROPERTY_VIEW_POSTS :
                $link = add_query_arg([
                    'post_type' => $this->post_type,
                    'author'    => $user_id,
                ], 'edit.php');

                break;
            case self::PROPERTY_VIEW_AUTHOR :
                $link = get_author_posts_url($user_id);

                break;
            case self::PROPERTY_EMAIL :
                $email = get_the_author_meta('email', $user_id);

                if ($email) {
                    $link = 'mailto:' . $email;
                }

                break;
            default:
                return $value;
        }

        if (isset($link)) {
            $value = $value->with_value(
                ac_helper()->html->link($link, $value->get_value())
            );
        }

        return $value;
    }

    protected function get_input_options(): array
    {
        return [
            ''                         => __('None'),
            self::PROPERTY_EDIT_USER   => __('Edit User Profile', 'codepress-admin-columns'),
            self::PROPERTY_EMAIL       => __('User Email', 'codepress-admin-columns'),
            self::PROPERTY_VIEW_POSTS  => __('View User Posts', 'codepress-admin-columns'),
            self::PROPERTY_VIEW_AUTHOR => __('View Public Author Page', 'codepress-admin-columns'),
        ];
    }

}