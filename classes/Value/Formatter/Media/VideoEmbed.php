<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Setting\Formatter;
use AC\Type\Value;
use AC\View\Embed\Video;

class VideoEmbed implements Formatter
{

    public function format(Value $value): Value
    {
        if ( ! $value->get_value()) {
            return $value;
        }

        $view = new Video();
        $view->set_src($value->get_value());

        return $value->with_value($view->render());
    }

}