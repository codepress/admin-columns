<?php

namespace AC\Asset;

use AC\Collection;

class Assets extends Collection
{

    /**
     * @return Enqueueable[]
     */
    public function all(): array
    {
        return parent::all();
    }

    public function add(Enqueueable $enqueueable)
    {
        $this->push($enqueueable);

        return $this;
    }

    public function add_collection(Assets $assets)
    {
        array_map([$this, 'add'], $assets->all());
    }

}