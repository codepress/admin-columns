<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\Pro;

class TogglePromotionFactory
{

    public function create(string $label): TogglePromotion
    {
        return new TogglePromotion($label);
    }

}