<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting;

final class ComponentFactoryRegistry
{

    private $name;

    private $label;

    private $width;

    public function __construct(
        Setting\ComponentFactory\Name $name,
        Setting\ComponentFactory\Label $label,
        Setting\ComponentFactory\Width $width
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->width = $width;
    }

    public function get_name(): Setting\ComponentFactory\Name
    {
        return $this->name;
    }

    public function get_label(): Setting\ComponentFactory\Label
    {
        return $this->label;
    }

    public function get_width(): Setting\ComponentFactory\Width
    {
        return $this->width;
    }

}