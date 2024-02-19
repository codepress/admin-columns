<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Download implements Formatter
{

    public function format(Value $value): Value
    {
        $url = wp_get_attachment_url($value->get_id());

        if ( ! $url) {
            return $value->with_value(false);
        }

        return $value->with_value(
            sprintf(
                '<a class="ac-download cpacicon-download" href="%s" title="%s" download></a>',
                str_replace(site_url(), '', $url),
                esc_attr(__('Download', 'codepress-admin-columns'))
            )
        );
    }

}