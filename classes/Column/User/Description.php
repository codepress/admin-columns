<?php

namespace AC\Column\User;

use AC\Column;
use AC\Settings;

class Description extends Column
{

    public function __construct()
    {
        $this->set_type('column-user_description');
        $this->set_label(__('Description', 'codepress-admin-columns'));
    }

    public function get_value($id): string
    {
        return get_the_author_meta('user_description', $id);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\WordLimit());
        $this->add_setting(new Settings\Column\BeforeAfter());
    }

}