<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

/**
 * @since 2.0
 */
class BeforeMoreTag extends Column
{

    public function __construct()
    {
        $this->set_type('column-before_moretag');
        $this->set_label(__('More Tag', 'codepress-admin-columns'));
    }

    public function get_raw_value($post_id)
    {
        $value = false;

        $p = get_post($post_id);
        $extended = get_extended($p->post_content);

        if ( ! empty($extended['extended'])) {
            $value = $extended['main'];
        }

        return $value;
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\WordLimit());
    }

}