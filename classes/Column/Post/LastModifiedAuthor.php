<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class LastModifiedAuthor extends Column
{

    public function __construct()
    {
        $this->set_type('column-last_modified_author')
             ->set_label(__('Last Modified Author', 'codepress-admin-columns'));
    }

    public function get_value($id): string
    {
        $user_id = get_post_meta($id, '_edit_last', true);

        if ( ! $user_id) {
            return $this->get_empty_char();
        }

        // TODO Stefan make sure this follows new (int) $id for get_formatted_value()
        return $this->get_formatted_value($user_id, $user_id);
    }

    protected function get_user_setting_display(): string
    {
        return (string)$this->get_option('user');
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\User());
    }

}