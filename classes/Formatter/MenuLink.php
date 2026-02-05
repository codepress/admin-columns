<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\Helper;
use AC\Type\Value;

class MenuLink implements Formatter
{

    public function format(Value $value): Value
    {
        return $value->with_value(
            Helper\Html::create()->link(
                add_query_arg(['menu' => $value->get_id()], admin_url('nav-menus.php')),
                $value->get_value()
            )
        );
    }

}