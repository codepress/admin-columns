<?php

namespace AC\Asset;

use AC\Collection;

class Assets extends Collection
{

    public function __construct(array $enqueueables = [])
    {
        array_map([$this, 'add'], $enqueueables);
    }

    /**
     * @return Enqueueable[]
     */
    public function all(): array
    {
        return $this->data;
    }

    public function add(Enqueueable $enqueueable): self
    {
        $this->data[] = $enqueueable;

        return $this;
    }

    public function current(): Enqueueable
    {
        return current($this->data);
    }

    public function add_collection(Assets $assets)
    {
        array_map([$this, 'add'], $assets->all());
    }

}