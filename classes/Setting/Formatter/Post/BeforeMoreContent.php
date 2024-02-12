<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class BeforeMoreContent implements Formatter
{

    public function format(Value $value): Value
    {
        $content = '';
        $p = get_post($value->get_id());
        $extended = get_extended($p->post_content);

        if ( ! empty($extended['extended'])) {
            $content = $extended['main'];
        }

        return $value->with_value($content);
    }

}