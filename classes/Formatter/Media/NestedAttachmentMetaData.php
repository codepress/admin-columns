<?php

declare(strict_types=1);

namespace AC\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class NestedAttachmentMetaData implements Formatter
{

    private $keys;

    public function __construct(array $keys)
    {
        $this->keys = $keys;
    }

    public function format(Value $value): Value
    {
        $meta = get_post_meta($value->get_id(), '_wp_attachment_metadata', true);

        $attachment_meta = ac_helper()->array->get_nested_value($meta, $this->keys);

        if ( ! is_scalar($attachment_meta)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($attachment_meta);
    }

}