<?php

namespace AC\Column\Post;

use AC\Column;

class Order extends Column
{

    public function __construct()
    {
        $this->set_type('column-order');
        $this->set_label(__('Order', 'codepress-admin-columns'));
    }

    public function get_value($post_id)
    {
        return $this->get_raw_value($post_id);
    }

    public function get_raw_value($post_id)
    {
        return get_post_field('menu_order', $post_id);
    }

}