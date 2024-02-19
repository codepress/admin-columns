<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Media;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;

class AttachmentMetaData implements Formatter
{

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function format(Value $value): Value
    {
        $meta = get_post_meta($value->get_id(), '_wp_attachment_metadata', true);

        return new Value(
            $meta[$this->key] ?? null
        );
    }

}