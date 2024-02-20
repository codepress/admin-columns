<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

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

        return is_scalar($attachment_meta)
            ? new Value($attachment_meta)
            : new Value(null);
    }

}