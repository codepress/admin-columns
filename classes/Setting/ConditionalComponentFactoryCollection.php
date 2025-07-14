<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\Specification;
use AC\Collection;

final class ConditionalComponentFactoryCollection extends Collection
{

    public function add(ComponentFactory $factory, Specification $conditions = null): self
    {
        $this->data[] = new ConditionalComponentFactory($factory, $conditions);

        return $this;
    }

    public function current(): ConditionalComponentFactory
    {
        return current($this->data);
    }

}