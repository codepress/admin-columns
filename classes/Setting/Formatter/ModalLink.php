<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ModalLink implements Formatter
{

    // TODO
    public function format(Value $value): Value
    {
        $link = ac_helper()->html->get_ajax_modal_link(
            $value->get_value(),
            [
                'title' => get_the_title($value->get_id()),
                'edit_link' => get_edit_post_link($value->get_id()),
                'id' => $value->get_id(),
                'class' => "-nopadding",
            ]
        );

        return $value->with_value(
            $link
        );
    }

}