<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\WidthFactory;

final class ComponentFactoryRegistry
{

    private $name_factory;

    private $label_factory;

    private $width_factory;

    public function __construct(
        Setting\ComponentFactory\Name $name_factory
        //        LabelFactory $label_factory,
        //        WidthFactory $width_factory
    )
    {
        $this->name_factory = $name_factory;
        //        $this->label_factory = $label_factory;
        //        $this->width_factory = $width_factory;
    }

    public function get_name_factory(): Setting\ComponentFactory\Name
    {
        return $this->name_factory;
    }

    public function get_label_factory(): LabelFactory
    {
        return $this->label_factory;
    }

    public function get_width_factory(): WidthFactory
    {
        return $this->width_factory;
    }

}