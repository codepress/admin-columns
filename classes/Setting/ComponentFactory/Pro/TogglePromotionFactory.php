<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory\Pro;

class TogglePromotionFactory
{

    public function create(string $label, string $feature = ''): TogglePromotion
    {
        return new TogglePromotion($label, $feature);
    }

}