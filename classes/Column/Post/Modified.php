<?php

declare(strict_types=1);

namespace AC\Column\Post;

use AC\Column;
use AC\Column\Value;
use AC\Setting\ComponentCollection;
use AC\Settings;

class Modified extends Column implements Value
{

    public function __construct()
    {
        parent::__construct(
            'column-modified',
            __('Last Modified', 'codepress-admin-columns'),
            new ComponentCollection([
                new Settings\Column\Date(),
            ])
        );
    }

    //    public function renderable(Config $options): Renderable
    //    {
    //        return new Column\Post\Renderable\Modified(
    //            new ValueFormatter($this->get_settings())
    //        );
    //    }
    // TODO
    //    public function get_raw_value($post_id)
    //    {
    //        return get_post_field('post_modified', $post_id);
    //    }

    //    public function register_settings()
    //    {
    //        $date = new Settings\Column\Date();
    //        //$date->set_default( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ) );
    //
    //        $this->add_setting($date);
    //    }

}