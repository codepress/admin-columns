<?php

namespace AC\Type;

use AC\Collection;

class PromoCollection extends Collection
{

    public function __construct(array $promos = [])
    {
        array_map([$this, 'add'], $promos);
    }

    public function add(Promo $promo): void
    {
        $this->data[] = $promo;
    }

    public function current(): Promo
    {
        return current($this->data);
    }

}