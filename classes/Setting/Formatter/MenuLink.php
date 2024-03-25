<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class MenuLink implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            ac_helper()->html->link(
                add_query_arg(['menu' => $value->get_id()], admin_url('nav-menus.php')),
                $value->get_value()
            )
        );
    }

}