<?php

namespace AC;

class Promos
{

    /**
     * @return Promo[]
     */

    private function all(): array
    {
        return [];
    }

    public function find_active(): ?Promo
    {
        foreach ($this->all() as $promo) {
            if ($promo->is_active()) {
                return $promo;
            }
        }

        return null;
    }

}