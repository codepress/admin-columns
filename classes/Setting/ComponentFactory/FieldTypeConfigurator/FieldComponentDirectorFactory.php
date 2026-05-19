<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\FieldTypeConfigurator;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactory\FieldTypeFactoryBuilder;
use AC\Setting\Config;

class FieldComponentDirectorFactory implements ComponentFactory
{

    private ConfiguratorRegistry $registry;

    private array $configurator_keys = [];

    public function __construct(ConfiguratorRegistry $registry)
    {
        $this->registry = $registry;
    }

    public function add(string $key): self
    {
        if ( ! $this->registry->has($key)) {
            return $this;
        }

        if ( ! in_array($key, $this->configurator_keys, true)) {
            $this->configurator_keys[] = $key;
        }

        return $this;
    }

    public function add_basic(): self
    {
        $this->add('text')
             ->add('numeric')
             ->add('boolean')
             ->add('color')
             ->add('html')
             ->add('image')
             ->add('url')
             ->add('date');

        return $this;
    }

    public function add_related(): self
    {
        $this->add('related_post')
             ->add('related_user')
             ->add('media');

        return $this;
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $builder = new FieldTypeFactoryBuilder();

        foreach ($this->configurator_keys as $key) {
            $this->registry->get($key)->configure($builder);
        }

        $this->configurator_keys = [];

        return $builder->build()->create($config, $conditions);
    }
}