<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;

class FileMeta extends Settings\Column
{

    public const NAME = 'media_meta_key';

    public function __construct(Column $column, array $meta_options, $default_option)
    {
        $this->name = self::NAME;
        $this->label = $column->get_label();
        $this->input = OptionFactory::create_select(
            OptionCollection::from_array($meta_options),
            $default_option
        );

        parent::__construct($column);
    }

}