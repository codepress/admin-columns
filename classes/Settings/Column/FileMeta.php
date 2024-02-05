<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;

class FileMeta extends Settings\Setting
{

    public const NAME = 'media_meta_key';

    protected $meta_key;

    public function __construct(
        string $label,
        array $meta_options,
        string $meta_key,
        Specification $specification = null
    ) {
        parent::__construct(
            $label,
            '',
            OptionFactory::create_select(
                'media_meta_key',
                OptionCollection::from_array($meta_options),
                $meta_key
            ),
            $specification
        );
        $this->meta_key = $meta_key;
    }

}