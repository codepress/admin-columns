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
            if ($this->is_active($promo)) {
                return $promo;
            }
        }

        return null;
    }

    private function is_active(Promo $promo): bool
    {
        return $promo->get_date_range()->in_range();
    }

}