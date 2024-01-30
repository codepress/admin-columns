<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

use AC\Column\Renderable\ValueFormatter;
use AC\MetaType;

class CustomField extends Formatted
{

    private $meta_type;

    private $meta_key;

    public function __construct(ValueFormatter $formatter, MetaType $meta_type, string $meta_key)
    {
        parent::__construct($formatter);

        $this->meta_type = $meta_type;
        $this->meta_key = $meta_key;
    }

    protected function get_pre_formatted_value($id): string
    {
        return get_metadata(
            (string)$this->meta_type,
            (int)$id,
            $this->meta_key,
            true
        );
    }

}