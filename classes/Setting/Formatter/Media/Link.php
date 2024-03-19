<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class Link extends Formatter\ContentTypeLink
{

    public function __construct(string $type)
    {
        if ($type === 'download') {
            $type = self::VIEW;
        }

        parent::__construct($type);
    }

    protected function get_edit_link(Value $value): ?string
    {
        return get_edit_post_link($value->get_id());
    }

    protected function get_view_link(Value $value): ?string
    {
        return get_attachment_link($value->get_id());
    }

    protected function create_link(string $url, string $value): string
    {
        $attributes = [];

        if ($this->type === self::VIEW) {
            $attributes['download'] = '';
        }

        return ac_helper()->html->link($url, $value, $attributes);
    }
}