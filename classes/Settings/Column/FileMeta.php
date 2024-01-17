<?php

namespace AC\Settings\Column;

use AC;
use AC\Column;
use AC\Settings;

class FileMeta extends Settings\Column
{

    public const NAME = 'media_meta_key';

    public function __construct(Column $column, array $meta_options, $default_option)
    {
        // TODO
        $this->name = self::NAME;
        $this->label = $column->get_label();
        $this->input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array($meta_options),
            $default_option
        );

        parent::__construct($column);
    }

}