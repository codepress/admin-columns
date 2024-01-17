<?php

namespace AC\Settings\Column;

use AC\Setting;
use AC\Setting\OptionCollection;
use AC\Settings;

class FileMeta extends Settings\Column
{

    public const NAME = 'media_meta_key';

    public function __construct(string $label, array $meta_options, string $default_option)
    {
        $input = Setting\Input\Option\Single::create_select(
            OptionCollection::from_array($meta_options),
            $default_option
        );

        parent::__construct('media_meta_key', $label, '', $input);
    }

}