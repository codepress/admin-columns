<?php

namespace AC\Column\Comment;

use AC\Column;

class WordCount extends Column
{

    public function __construct()
    {
        $this->set_type('column-word_count')
             ->set_label(__('Word Count', 'codepress-admin-columns'));
    }

    public function get_raw_value($id)
    {
        return ac_helper()->string->word_count(get_comment($id)->comment_content);
    }

}