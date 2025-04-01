<?php

declare(strict_types=1);

namespace AC\Setting;

class BaseSettingsBuilder
{

    private ComponentFactory\Name $name;

    private ComponentFactory\Label $label;

    private ComponentFactory\Width $width;

    public function __construct(
        ComponentFactory\Name $name,
        ComponentFactory\Label $label,
        ComponentFactory\Width $width
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->width = $width;
    }

    public function build(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->name->create($config),
            $this->label->create($config),
            $this->width->create($config),
        ], 5);
    }

}