<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Setting\Formatter;
use AC\Type\Value;

class Link implements Formatter
{

    private string $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    protected function get_view_link(Value $value): ?string
    {
        return get_attachment_link($value->get_id());
    }

    protected function get_download_link(Value $value): ?string
    {
        $url = wp_get_attachment_url($value->get_id());

        return $url
            ? sprintf('<a href="%s" download>%s</a>', $url, $value->get_value())
            : null;
    }

    public function format(Value $value): Value
    {
        switch ($this->type) {
            case 'view':
                return $value->with_value($this->get_view_link($value));
            case 'download':
                return $value->with_value($this->get_download_link($value));
            default :
                return $value;
        }
    }

}