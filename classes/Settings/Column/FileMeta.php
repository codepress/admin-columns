<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;

class FileMeta extends Settings\Column
{

    public const NAME = 'media_meta_key';

    public function __construct(string $label, array $meta_options, string $default_option)
    {
        parent::__construct(
            $label,
            '',
            OptionFactory::create_select(
                'media_meta_key',
                OptionCollection::from_array($meta_options),
                $default_option
            )
        );
    }

}