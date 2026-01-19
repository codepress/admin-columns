<?php

declare(strict_types=1);

namespace AC\Formatter\Media\Video;

use AC\Formatter;
use AC\Type\Value;
use AC\Value\Extended\ExtendedValue;

class ModalEmbedLink implements Formatter
{

    private ExtendedValue $extended_value;

    public function __construct(ExtendedValue $extended_value)
    {
        $this->extended_value = $extended_value;
    }

    public function format(Value $value): Value
    {
        if ( ! $value->get_value()) {
            return $value;
        }

        $link = $this->extended_value->get_link($value->get_id(), __('Play', 'codepress-admin-columns'))
                                     ->with_title(get_the_title($value->get_id()))
                                     ->with_edit_link(get_edit_post_link($value->get_id()))
                                     ->with_download_link(str_replace(site_url(), '', $value->get_value()))
                                     ->with_class("-nopadding");

        return $value->with_value(
            $link->render()
        );
    }

}