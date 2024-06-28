<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Setting\Formatter;
use AC\Type\Value;

class Dimensions implements Formatter
{

    public function format(Value $value): Value
    {
        $meta = get_post_meta($value->get_id(), '_wp_attachment_metadata', true);

        if (empty($meta['width']) || empty($meta['height'])) {
            return $value->with_value(false);
        }

        $label = $meta['width'] . '&nbsp;&times;&nbsp;' . $meta['height'];
        $tooltip = sprintf(__('Width : %s px', 'codepress-admin-columns'), $meta['width']) . "<br/>\n" . sprintf(
                __('Height : %s px', 'codepress-admin-columns'),
                $meta['height']
            );

        return $value->with_value(
            ac_helper()->html->tooltip($label, $tooltip)
        );
    }

}