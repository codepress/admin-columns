<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class UserLink extends Settings\Column implements AC\Setting\Formatter
{

    public const NAME = 'user_link_to';

    public const PROPERTY_EDIT_USER = 'edit_user';
    public const PROPERTY_VIEW_POSTS = 'view_user_posts';
    public const PROPERTY_VIEW_AUTHOR = 'view_author';
    public const PROPERTY_EMAIL = 'email_user';

    private $post_type;

    public function __construct(string $post_type = null, Specification $conditions = null)
    {
<<<<<<< HEAD
        $this->name = self::NAME;
        $this->label = __('Link To', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
            OptionCollection::from_array($this->get_input_options()),
=======
        $input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array($this->get_input_options()),
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
            self::PROPERTY_EDIT_USER
        );

        parent::__construct('user_link_to', __('Link To', 'codepress-admin-columns'), null, $input, $conditions);

        $this->post_type = $post_type;
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        switch ($options->get(self::NAME)) {
            case self::PROPERTY_EDIT_USER:
                if (current_user_can('edit_users')) {
                    $link = add_query_arg('user_id', $value->get_id(), self_admin_url('user-edit.php'));
                }

                break;
            case self::PROPERTY_VIEW_POSTS :
                $link = add_query_arg([
                    'post_type' => $this->post_type,
                    'author'    => $value->get_id(),
                ], 'edit.php');

                break;
            case self::PROPERTY_VIEW_AUTHOR :
                $link = get_author_posts_url($value->get_id());

                break;
            case self::PROPERTY_EMAIL :
                if ($email = get_the_author_meta('email', $value->get_id())) {
                    $link = 'mailto:' . $email;
                }

                break;
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