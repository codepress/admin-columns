<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media\Video;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class ModalEmbedLink implements Formatter
{

    public function format(Value $value): Value
    {
        // TODO not necessary if aggregate checks null values
        if ( ! $value->get_value()) {
            return $value;
        }

        $link = ac_helper()->html->get_ajax_modal_link(
            __('Play', 'codepress-admin-columns'),
            [
                'title'         => get_the_title($value->get_id()),
                'edit_link'     => get_edit_post_link($value->get_id()),
                'download_link' => $this->create_relative_path($value->get_value()) ?: null,
                'id'            => $value->get_id(),
                'class'         => "-nopadding",
            ]
        );

        return $value->with_value(
            $link
        );
    }

    private function create_relative_path($url)
    {
        return str_replace(site_url(), '', $url);
    }

}