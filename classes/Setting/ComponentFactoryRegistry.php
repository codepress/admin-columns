<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting;

final class ComponentFactoryRegistry
{

    private $name_factory;

    private $label_factory;

    private $width_factory;

    public function __construct(
        Setting\ComponentFactory\Name $name_factory,
        Setting\ComponentFactory\Label $label_factory,
        Setting\ComponentFactory\Width $width_factory
    ) {
        $this->name_factory = $name_factory;
        $this->label_factory = $label_factory;
        $this->width_factory = $width_factory;
    }

    public function get_name_factory(): Setting\ComponentFactory\Name
    {
        return $this->name_factory;
    }

    public function get_label_factory(): Setting\ComponentFactory\Label
    {
        return $this->label_factory;
    }

    public function get_width_factory(): Setting\ComponentFactory\Width
    {
        return $this->width_factory;
    }

}