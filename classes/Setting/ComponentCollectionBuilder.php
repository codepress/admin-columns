<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;
use AC\Settings\Column\LabelFactory;
use AC\Settings\Column\NameFactory;
use AC\Settings\Column\WidthFactory;
use AC\Settings\SettingFactory;

class ComponentCollectionBuilder
{

    private $name_factory;

    private $width_factory;

    private $label_factory;

    private $factories = [];

    public function __construct(
        NameFactory $name_factory,
        WidthFactory $width_factory,
        LabelFactory $label_factory
    ) {
        $this->name_factory = $name_factory;
        $this->width_factory = $width_factory;
        $this->label_factory = $label_factory;
    }

    public function add_defaults(): self
    {
        $this->add_name()
             ->add_label()
             ->add_width();

        return $this;
    }

    public function add_name(): self
    {
        $this->add($this->name_factory);

        return $this;
    }

    public function add_label(): self
    {
        $this->add($this->label_factory);

        return $this;
    }

    public function add_width(): self
    {
        $this->add($this->width_factory);

        return $this;
    }

    public function add(SettingFactory $factory, Specification $specification = null): self
    {
        $this->factories[] = [
            'factory'       => $factory,
            'specification' => $specification,
        ];

        return $this;
    }

    public function build(Config $config): ComponentCollection
    {
        $components = [];

        foreach ($this->factories as $factory) {
            $components[] = $factory['factory']->create($config, $factory['specification']);
        }

        return new ComponentCollection($components);
    }

}