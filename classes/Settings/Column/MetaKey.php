<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\MetaType;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Formatter;
use AC\Settings\Control;
use AC\Type\Value;

class MetaKey extends Control implements Formatter
{

    private $meta_key;

    private $meta_type;

    public function __construct(string $meta_key, MetaType $meta_type, Specification $specification = null)
    {
        parent::__construct(
            OptionFactory::create_select_remote(
                'field',
                'ac-custom-field-keys',
                $meta_key,
                [],
                __('Select', 'codepress-admin-columns')
            ),
            __('Field', 'codepress-admin-columns'),
            __('Custom field key', 'codepress-admin-columns'),
            $specification
        );

        $this->meta_key = $meta_key;
        $this->meta_type = $meta_type;
    }

    public function format(Value $value): Value
    {
        return $value->with_value(
            get_metadata(
                (string)$this->meta_type,
                (int)$value->get_id(),
                $this->meta_key,
                true
            )
        );
    }

}