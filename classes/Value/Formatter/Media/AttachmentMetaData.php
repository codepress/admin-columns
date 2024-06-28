<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;

class AttachmentMetaData implements Formatter
{

    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function format(Value $value): Value
    {
        $meta = (array)get_post_meta($value->get_id(), '_wp_attachment_metadata', true);

        if ( ! array_key_exists($this->key, $meta)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value($meta[$this->key]);
    }

}