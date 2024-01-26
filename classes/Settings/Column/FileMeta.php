<?php

declare(strict_types=1);

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC\Column;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
=======
use AC\Setting;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings;

class FileMeta extends Settings\Column
{

    public const NAME = 'media_meta_key';

    public function __construct(string $label, array $meta_options, string $default_option)
    {
<<<<<<< HEAD
        $this->name = self::NAME;
        $this->label = $column->get_label();
        $this->input = OptionFactory::create_select(
=======
        $input = Setting\Input\Option\Single::create_select(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
            OptionCollection::from_array($meta_options),
            $default_option
        );

        parent::__construct('media_meta_key', $label, '', $input);
    }

}