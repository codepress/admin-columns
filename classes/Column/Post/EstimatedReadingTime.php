<?php

namespace AC\Column\Post;

use AC\Column;
use AC\Settings;

class EstimatedReadingTime extends Column
{

    public function __construct()
    {
        $this->set_type('column-estimated_reading_time');
        $this->set_label(__('Read Time', 'codepress-admin-columns'));
    }

    public function get_raw_value($post_id)
    {
        return ac_helper()->post->get_raw_field('post_content', (int)$post_id);
    }

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\WordsPerMinute($this));
    }

}