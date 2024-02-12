<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;

final class ComponentCollectionBuilderFactory
{

    private $name_factory;

    private $width_factory;

    private $label_factory;
    
    public function __construct(
        NameFactory $name_factory,
        WidthFactory $width_factory,
        LabelFactory $label_factory
    ) {
        $this->name_factory = $name_factory;
        $this->width_factory = $width_factory;
        $this->label_factory = $label_factory;
    }

    public function create(): ComponentCollectionBuilder
    {
        return new ComponentCollectionBuilder(
            $this->name_factory,
            $this->width_factory,
            $this->label_factory
        );
    }

}